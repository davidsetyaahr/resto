
<?php 
    if(isset($_GET['payment'])){
      $text = 'Payment Bill';
    }
    else{
      $text = 'Guest Bill';
    }
    //justification
    $tengah = Chr(27) . Chr(97) . Chr(1);
    $kiri = Chr(27) . Chr(97) . Chr(0);
    $kanan = Chr(27) . Chr(97) . Chr(2);
    $spasi = " ";
        $subtotal = 0;
        $item = '';
        foreach ($detail as $key => $hasild) {
          $item .= $kiri;
          $item .= number_format($hasild->qty, 0, ",", ".") . " " . $hasild->nama . " @ " . number_format($hasild->harga_jual, 0, ",", ".") . "\n";
          $item .= $kanan;
          $item .= number_format($hasild->sub_total, 0, ",", ".") . "\n";
          $subtotal+=$hasild->sub_total;
        }
        $ppn = 10*$subtotal/100;
        $total = $subtotal + $ppn;
        $tmpdir = sys_get_temp_dir();
        $file = tempnam($tmpdir, 'ctk');
        $handle = fopen($file, 'w');
        $condensed = Chr(27) . Chr(33) . Chr(4);
        $bold1 = Chr(27) . Chr(69);
        $bold0 = Chr(27) . Chr(70);
        $initialized = chr(27) . chr(64);
        $condensed1 = chr(15);
        $condensed0 = chr(18);
        $Data = $initialized;
        $Data .= $condensed1;
        $Data .= $tengah;
        $Data .= "Baratha Coffee\n";
        $Data .= "Jl. Saliwiryo Pranowo Gg.Taman No.11\n";
        $Data .= "Bondowoso\n";
        $Data .= "(0332) 424555\n";
        $Data .= "www.barathahotel.id\n";
        $Data .= "\n";
        $Data .= $kiri;
        $Data .= $text."\n";
        $Data .= $penjualan->nama_meja."\n";
        $Data .= $penjualan->kode_penjualan."\n";
        $Data .= "------------------------------------------------\n";
        $Data .= $item;
        $Data .= $kiri;
        $Data .= "------------------------------------------------\n";
        $Data .= "Sub Total " . str_pad(number_format($subtotal, 0, ",", "."), 38, $spasi, STR_PAD_LEFT) . "\n";
        $Data .= "Total(+PPN)" . str_pad(number_format($total, 0, ",", "."), 37, $spasi, STR_PAD_LEFT) . "\n";
        if(isset($_GET['payment'])){
          $ttlDiskon = $penjualan->total_diskon + $penjualan->total_diskon_tambahan;
          if($ttlDiskon > 0){
            $Data .= "Diskon" . str_pad(number_format($ttlDiskon, 0, ",", "."), 42, $spasi, STR_PAD_LEFT) . "\n";
          }
          $Data .= "------------------------------------------------\n";
          $Data .= "Bayar" . str_pad(number_format($penjualan->bayar, 0, ",", "."), 43, $spasi, STR_PAD_LEFT) . "\n";
          $Data .= "Kembalian" . str_pad(number_format($penjualan->kembalian, 0, ",", "."), 39, $spasi, STR_PAD_LEFT) . "\n";
        }        

        
        $Data .= "\n";
        $Data .= "\n";
        $Data .= "\n";
        $Data .= "\n";
        $Data .= "\n";
        $Data .= "\n";
        $Data .= "\n";
        $Data .= Chr(29).Chr(86).Chr(49); #Auto Cutter
        fwrite($handle, $Data);
        fclose($handle);
        copy($file,"//192.168.137.105/Kasir"); # Lakukan cetak
        unlink($file);

?>
<script>
       window.location.href = 'http://192.168.137.105:3301/newresto/penjualan/penjualan'
</script>