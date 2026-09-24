<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PDO;

/**
 * Full copy of the main database into the `replica` connection (read-only use).
 *
 * Every table is loaded into `<table>__sync` first and swapped in with a single
 * RENAME, so readers of the replica never see a half-filled table.
 * Triggers are not copied on purpose: the replica only receives rows, it must
 * not recalculate anything on its own.
 */
class SyncReplica extends Command
{
    protected $signature = 'db:sync-replica';

    protected $description = 'Full sync of the main database into the read-only replica database';

    public function handle()
    {
        $src = DB::connection();
        $dst = DB::connection('replica');
        $srcDb = $src->getDatabaseName();
        $dstDb = $dst->getDatabaseName();

        if (!$dstDb || ($dstDb === $srcDb && $src->getConfig('host') === $dst->getConfig('host'))) {
            $this->error('Set REPLICA_DB_DATABASE to a database other than the main one.');
            return 1;
        }

        $started = microtime(true);
        $srcPdo = $src->getPdo();
        $srcPdo->exec('SET SESSION TRANSACTION ISOLATION LEVEL REPEATABLE READ');
        $srcPdo->exec('START TRANSACTION WITH CONSISTENT SNAPSHOT, READ ONLY');

        $dst->statement("SET SESSION sql_mode = ''");
        $dst->statement('SET SESSION foreign_key_checks = 0');
        $dst->statement('SET SESSION unique_checks = 0');

        $tables = $this->objects($src, 'BASE TABLE');
        $views = $this->objects($src, 'VIEW');

        try {
            foreach ($tables as $table) {
                $rows = $this->copyTable($src, $dst, $table);
                $this->line("$table: $rows rows");
            }
        } finally {
            $srcPdo->exec('COMMIT');
        }

        // Swap every table in one statement.
        $existing = $this->objects($dst, 'BASE TABLE');
        $renames = [];
        foreach ($tables as $table) {
            if (in_array($table, $existing)) {
                $renames[] = "`$table` TO `{$table}__old`";
            }
            $renames[] = "`{$table}__sync` TO `$table`";
        }
        foreach ($this->objects($dst, 'VIEW') as $view) {
            $dst->statement("DROP VIEW `$view`");
        }
        $dst->statement('RENAME TABLE ' . implode(', ', $renames));

        // Drop the previous copies and tables that no longer exist in the main database.
        foreach ($this->objects($dst, 'BASE TABLE') as $table) {
            if (!in_array($table, $tables)) {
                $dst->statement("DROP TABLE `$table`");
            }
        }

        foreach ($views as $view) {
            $sql = $src->selectOne("SHOW CREATE VIEW `$view`")->{'Create View'};
            $sql = preg_replace('/^CREATE .*? VIEW/', 'CREATE VIEW', $sql);
            $dst->statement(str_replace("`$srcDb`.", '', $sql));
        }

        $this->info(sprintf('Synced %d tables and %d views from %s to %s in %.1fs.', count($tables), count($views), $srcDb, $dstDb, microtime(true) - $started));
        return 0;
    }

    private function copyTable($src, $dst, $table)
    {
        $create = $src->selectOne("SHOW CREATE TABLE `$table`")->{'Create Table'};
        // Foreign key names are unique per database, so they would clash with the live
        // copy. The replica is read-only, it does not need them.
        $create = preg_replace('/,\n\s*CONSTRAINT `[^`]+` FOREIGN KEY[^\n]*?(?=,?\n)/', '', $create);
        $create = preg_replace('/^CREATE TABLE `[^`]+`/', "CREATE TABLE `{$table}__sync`", $create);

        $dst->statement("DROP TABLE IF EXISTS `{$table}__sync`");
        $dst->statement($create);

        $pdo = $src->getPdo();
        $pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);
        $total = 0;
        try {
            $stmt = $pdo->query("SELECT * FROM `$table`");
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $batch = [];
            $size = null;
            foreach ($stmt as $row) {
                $size = $size ?: max(1, intdiv(20000, count($row)));
                $batch[] = $row;
                if (count($batch) >= $size) {
                    $this->insert($dst, $table, $batch);
                    $total += count($batch);
                    $batch = [];
                }
            }
            if ($batch) {
                $this->insert($dst, $table, $batch);
                $total += count($batch);
            }
        } finally {
            $pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
        }

        return $total;
    }

    private function insert($dst, $table, array $rows)
    {
        $columns = '`' . implode('`, `', array_keys($rows[0])) . '`';
        $placeholders = '(' . implode(', ', array_fill(0, count($rows[0]), '?')) . ')';
        $values = [];
        foreach ($rows as $row) {
            foreach ($row as $value) {
                $values[] = $value;
            }
        }
        // Straight to PDO: going through Laravel fires a query event per batch, and
        // Ignition keeps every one of them (with bindings) in memory.
        $dst->getPdo()
            ->prepare("INSERT INTO `{$table}__sync` ($columns) VALUES " . implode(', ', array_fill(0, count($rows), $placeholders)))
            ->execute($values);
    }

    private function objects($connection, $type)
    {
        $rows = $connection->select('SHOW FULL TABLES WHERE Table_type = ?', [$type]);

        return array_map(function ($row) {
            return array_values((array) $row)[0];
        }, $rows);
    }
}
