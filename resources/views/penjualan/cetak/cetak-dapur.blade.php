<?php 
//justification
$tengah = Chr(27) . Chr(97) . Chr(1);
$kiri = Chr(27) . Chr(97) . Chr(0);
$updateText = isset($_GET['update']) ? '(TAMBAHAN)' : '';
if(count($dapur)!=0){
    $item = '';
    $keterangan = '';
    if(empty($_GET['update'])){
        foreach ($dapur as $key => $hasil) {
            $keterangan = $hasil->keterangan!='' ?  "(".$hasil->keterangan.")" : '';
            $item .=$hasil->qty." ".$hasil->nama.$keterangan."\n";
        }
    }
    else{
        foreach ($dapur as $key => $hasil) {
            $keterangan = $hasil['keterangan']<>Null || $hasil['keterangan']<>'' ?  "(".$hasil['keterangan'].")" : '';
            $item .=$hasil['qty']." ".$hasil['nama'].$keterangan."\n";
        }
    }
    //jam order
    $jam_order=Date('d-m-Y H:i:s',strtotime($penjualan->waktu));

    //echo $item;

    //print dapur
    $tmpdir = sys_get_temp_dir();
    $file = tempnam($tmpdir, 'ctk');
    $handle = fopen($file, 'w');
    $condensed = Chr(27) . Chr(33) . Chr(4);
    $bold1 = Chr(27) . Chr(69);
    $bold0 = Chr(27) . Chr(70);
    $initialized = chr(27).chr(64);
    $condensed1 = chr(15);
    $condensed0 = chr(18);
    $Data = $initialized;
    $Data .= $condensed1;
    $Data .= "\n";
    $Data .= "\n";
    $Data .= $bold1;
    $Data .= "OORDERAN DAPUR ".$updateText."\n";
    // $Data .=$penjualan->nama_meja."\n";
    if ($penjualan->jenis_order == 'Room Order') {
        $Data .= $penjualan->nama_meja. " " . $penjualan->nomor_kamar ."\n";
    }
    else{
        $Data .= $penjualan->nama_meja."\n";
    }
    $Data .=$penjualan->kode_penjualan."\n";
    $Data .=$penjualan->jenis_order."\n";
   $Data .="--------------------------------------------\n";
    $Data .=$item;
    $Data .="--------------------------------------------\n";
    $Data .=$tengah;
    $Data .=$jam_order;
    $Data .= "\n";
    $Data .=$kiri;
    $Data .="--------------------------------------------\n";
    $Data .= "\n";
    $Data .= "\n";
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
    copy($file,"//192.168.137.105/Dapur"); # Lakukan cetak
    unlink($file);
    // echo "<pre>";
    // print_r($Data);
    // echo "</pre>";
}
if(count($bar)!=0){
    $item = '';
    $keterangan = '';
    if(empty($_GET['update'])){
        foreach ($bar as $key => $hasil) {
            $keterangan = $hasil->keterangan<>Null || $hasil->keterangan<>'' ?  "(".$hasil->keterangan.")" : '';
            $item .=$hasil->qty." ".$hasil->nama.$keterangan."\n";
        }
    }
    else{
        foreach ($bar as $key => $hasil) {
            $keterangan = $hasil['keterangan']<>Null || $hasil['keterangan']<>'' ?  "(".$hasil['keterangan'].")" : '';
            $item .=$hasil['qty']." ".$hasil['nama'].$keterangan."\n";
        }
    }
    //jam order
    $jam_order=Date('d-m-Y H:i:s',strtotime($penjualan->waktu));

    //echo $item;

    //print dapur
    $tmpdir = sys_get_temp_dir();
    $file = tempnam($tmpdir, 'ctk');
    $handle = fopen($file, 'w');
    $condensed = Chr(27) . Chr(33) . Chr(4);
    $bold1 = Chr(27) . Chr(69);
    $bold0 = Chr(27) . Chr(70);
    $initialized = chr(27).chr(64);
    $condensed1 = chr(15);
    $condensed0 = chr(18);
    $Data = $initialized;
    $Data .= "\n";
    $Data .= "\n";
    $Data .= $bold1;
    $Data .= "OORDERAN BAR ".$updateText."\n";
    // $Data .=$penjualan->nama_meja."\n";
    if ($penjualan->jenis_order == 'Room Order') {
        $Data .= $penjualan->nama_meja. " " . $penjualan->nomor_kamar ."\n";
    }
    else{
        $Data .= $penjualan->nama_meja."\n";
    }
    $Data .=$penjualan->kode_penjualan."\n";
    $Data .=$penjualan->jenis_order."\n";

    $Data .="--------------------------------------------\n";
    $Data .=$item;
    $Data .="--------------------------------------------\n";
    $Data .=$tengah;
    $Data .=$jam_order;
    $Data .= "\n";
    $Data .=$kiri;
    $Data .="--------------------------------------------\n";
    $Data .= "\n";
    $Data .= "\n";
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
    copy($file,"//192.168.137.105/Bar"); # Lakukan cetak
    unlink($file);
    // echo "<pre>";
    // print_r($Data);
    // echo "</pre>";

}
if(isset($_GET['update'])){
    $updateText = '(TAMBAHAN)';
    $url = 'http://192.168.137.105:3301/newresto/penjualan/'.$penjualan->kode_penjualan.'/edit';
}
else{
    $updateText = '';
    $url = 'http://192.168.137.105:3301/newresto/penjualan/penjualan/create';
}

?>
<script>
      window.location.href = '<?php echo $url ?>'
</script>
