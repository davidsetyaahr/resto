<?php 
//justification
$tengah = Chr(27) . Chr(97) . Chr(1);
$kiri = Chr(27) . Chr(97) . Chr(0);

if(count($dapur)!=0){
    $item = '';
    $keterangan = '';
    foreach ($dapur as $key => $hasil) {
        $item .=$hasil->qty." ".$hasil->nama."\n";
        $keterangan .= $hasil->keterangan;
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
    $Data .= "ORDERAN DAPUR\n";
    $Data .=$penjualan->nama_meja."\n";
    $Data .=$penjualan->kode_penjualan."\n";
    $Data .="--------------------------------------------\n";
    $Data .=$item;
    if($keterangan<>Null || $keterangan<>''){
        $Data .=$keterangan;
    }
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
    copy($file,"//192.168.137.1/Dapur"); # Lakukan cetak
    unlink($file);
}
if(count($bar)!=0){
    $item = '';
    $keterangan = '';
    foreach ($bar as $key => $hasil) {
        $item .=$hasil->qty." ".$hasil->nama."\n";
        $keterangan .= $hasil->keterangan;
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
    $Data .= "ORDERAN BAR\n";
    $Data .=$penjualan->nama_meja."\n";
    $Data .=$penjualan->kode_penjualan."\n";
    $Data .="--------------------------------------------\n";
    $Data .=$item;
    if($keterangan<>Null || $keterangan<>''){
        $Data .=$keterangan;
    }
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
    copy($file,"//192.168.137.1/Bar"); # Lakukan cetak
    unlink($file);
}

?>

<script>
      window.location.href = 'http://localhost:3301/newresto/penjualan/penjualan/create'
</script>