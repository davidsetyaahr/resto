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
    echo 'a';
    foreach ($detail as $key => $hasild) {
      $detailPesanan = \DB::table('detail_penjualan as dp')->select('dp.diskon','dp.sub_total','m.nama','m.harga_jual')->join('menu as m','dp.kode_menu','m.kode_menu')->where('dp.kode_menu', $hasild['kode_menu'])->where('dp.kode_penjualan', $penjualan->kode_penjualan)->get()[0];
      $hargaJual = $detailPesanan->harga_jual;
      $diskon = $detailPesanan->diskon;
      $sub_total = $detailPesanan->sub_total;
      $item .= $kiri;
      $item .= number_format($hasild['qty'], 0, ",", ".") . " " . $hasild['nama'] . " @ " . number_format($hargaJual-$diskon, 0, ",", ".")."\n";
      $item .= $kanan;
      $item .= number_format($sub_total-$diskon, 0, ",", ".") . "\n";
      $subtotal+=$sub_total-$diskon;
    }
    $ppn = 10*$subtotal/100;
    $room_charge = $penjualan->jenis_order == 'Room Order' ?  10*$subtotal/100 : 0;
    $total = $subtotal + $ppn + $room_charge;
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
    $Data .= "Baratha Hotel & Resto\n";
    $Data .= "Jl. Saliwiryo Pranowo Gg.Taman No.11\n";
    $Data .= "Bondowoso\n";
    $Data .= "(0332) 424555\n";
    $Data .= "www.barathahotel.id\n";
    $Data .= "\n";
    $Data .= $kiri;
    $Data .= date('d-m-Y H:i', strtotime($penjualan->waktu))."\n";
    $Data .= $text."\n";
    $Data .= $penjualan->nama_customer."\n";
    if ($penjualan->jenis_order == 'Room Order') {
      $Data .= $penjualan->nama_meja. " " . $penjualan->nomor_kamar ."\n";
    }
    else{
      $Data .= $penjualan->nama_meja."\n";
    }
    $Data .= $penjualan->kode_penjualan."\n";
    $Data .= "------------------------------------------------\n";
    $Data .= $item;
    $Data .= $kiri;
    $Data .= "------------------------------------------------\n";
    $Data .= "Sub Total " . str_pad(number_format($subtotal, 0, ",", "."), 38, $spasi, STR_PAD_LEFT) . "\n";
    $Data .= "PPN" . str_pad(number_format($ppn, 0, ",", "."), 45, $spasi, STR_PAD_LEFT) . "\n";
    if ($penjualan->jenis_order == 'Room Order') {
      $Data .= "Room Charge" . str_pad(number_format($room_charge, 0, ",", "."), 37, $spasi, STR_PAD_LEFT) . "\n";
    }
    $Data .= "------------------------------------------------\n";
    $Data .= "Total " . str_pad(number_format($total, 0, ",", "."), 42, $spasi, STR_PAD_LEFT) . "\n";

    if(isset($_GET['payment'])){
      $ttlDiskon = $penjualan->total_diskon_tambahan;
      $charge = $penjualan->charge;
      if($ttlDiskon > 0){
        $Data .= "Potongan" . str_pad(number_format($ttlDiskon, 0, ",", "."), 40, $spasi, STR_PAD_LEFT) . "\n";
      }
      if ($charge > 0) {
        $Data .= "Charge" . str_pad(number_format($charge, 0, ",", "."), 42, $spasi, STR_PAD_LEFT) . "\n";
      }
      if ($charge > 0 || $ttlDiskon > 0) {
        $Data .= "------------------------------------------------\n";
        $Data .= "Grand Total" . str_pad(number_format($total - $ttlDiskon + $charge, 0, ",", "."), 37, $spasi, STR_PAD_LEFT) . "\n";
      }
      $Data .= "------------------------------------------------\n";
      $Data .= "Bayar" . str_pad(number_format($penjualan->bayar, 0, ",", "."), 43, $spasi, STR_PAD_LEFT) . "\n";
      $Data .= "Kembalian" . str_pad(number_format($penjualan->kembalian, 0, ",", "."), 39, $spasi, STR_PAD_LEFT) . "\n";
    }        

    $Data .= $tengah;
    $Data .= "\n";
    $Data .= "\n";

    $Data .= "Learn,Share,Be Successfull\n";        
    $Data .= "\n";
    $Data .= "\n";
    $Data .= "\n";
    $Data .= "\n";
    $Data .= "\n";
    $Data .= "\n";
    $Data .= "\n";
    $Data .= Chr(29).Chr(86).Chr(49); #Auto Cutter
    // fwrite($handle, $Data);
    // fclose($handle);
    // copy($file,"//192.168.137.105/Kasir"); # Lakukan cetak
    // unlink($file);
    echo 'a';
    echo "<pre>";
    print_r ($Data);
    echo "</pre>";
    
?>
<script>
      //  window.location.href = 'http://192.168.137.105:3301/newresto/penjualan/penjualan'
</script>