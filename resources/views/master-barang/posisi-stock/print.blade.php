<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('assets/css/argon.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}" type="text/css">
    <title>Posisi Stock</title>
</head>
<body>
<center>
<h3>Posisi Stock</h3>
<h5>Tanggal: {{date('d-m-Y', strtotime($_GET['dari'])).' s/d '.date('d-m-Y', strtotime($_GET['sampai']))}}</h5>
@if ($_GET['kode_barang'])
    <h5>Kode Barang : {{$_GET['kode_barang']}} </h5>
@endif
<br>
</center>
<table width="100%" cellspacing="0" cellpadding="5">
    <thead>
      <tr>
        <th>#</th>
        <th>Barang</th>
        <th>Qty Awal</th>
        <th>Saldo Awal</th>
        <th colspan="2">Jumlah Masuk</th>
        <th colspan="2">Jumlah Keluar</th>
        <th>Qty Akhir</th>
        <th>Saldo Akhir</th>
        <th>Harga Satuan</th>
      </tr>
    </thead>
    <tbody>
      @php 
      $dari = Request::get('dari');
      $sampai = Request::get('sampai');
      $kode = Request::get('kode_barang');
    
      $ttlQAwal = $ttlSAwal = $ttlQMasuk = $ttlSMasuk = $ttlQKeluar = $ttlSKeluar = $ttlQAkhir = $ttlSAkhir = 0;
      @endphp
      @foreach ($barang as $value)
      @php
        $qtyAwal = $value->stock_awal;
        $saldoAwal = $value->saldo_awal;
    
        $pembelianAwal = \DB::table(\DB::raw('detail_pembelian as dt'))->select(\DB::raw('SUM(dt.qty) as jmlQty'), \DB::raw('SUM(dt.subtotal) as jumlah'))->join('pembelian as p', 'p.kode_pembelian', '=', 'dt.kode_pembelian')->where('p.tanggal', '<', "$dari")->where('dt.kode_barang', $value->kode_barang)->get()[0];
    
        $qtyAwal += $pembelianAwal->jmlQty;
        $saldoAwal += $pembelianAwal->jumlah;
        
        $pemakaianAwal = \DB::table(\DB::raw('detail_pemakaian as dt'))->select(\DB::raw('SUM(dt.qty) as jmlQty'), \DB::raw('SUM(dt.subtotal_saldo) as jumlah'))->join('pemakaian as p', 'p.kode_pemakaian', '=', 'dt.kode_pemakaian')->where('p.tanggal', '<', "$dari")->where('dt.kode_barang', $value->kode_barang)->get()[0];
    
        $qtyAwal -= $pemakaianAwal->jmlQty;
        $saldoAwal -= $pemakaianAwal->jumlah;
    
        $ttlQAwal += $qtyAwal;
        $ttlSAwal += $saldoAwal;
    
        $pembelian = \DB::table(\DB::raw('detail_pembelian as dt'))->select(\DB::raw('SUM(dt.qty) as jmlQty'), \DB::raw('SUM(dt.subtotal) as jumlah'))->join('pembelian as p', 'p.kode_pembelian', '=', 'dt.kode_pembelian')->whereBetween('p.tanggal', [$dari, $sampai])->where('dt.kode_barang', $value->kode_barang)->get()[0];
        $qMasuk = $pembelian->jmlQty == '' ? 0 : $pembelian->jmlQty;
        $sMasuk = $pembelian->jumlah == '' ? 0 : $pembelian->jumlah;
    
        $ttlQMasuk += $qMasuk;
        $ttlSMasuk += $sMasuk;
    
        $pemakaian = \DB::table(\DB::raw('detail_pemakaian as dt'))->select(\DB::raw('SUM(dt.qty) as jmlQty'), \DB::raw('SUM(dt.subtotal_saldo) as jumlah'))->join('pemakaian as p', 'p.kode_pemakaian', '=', 'dt.kode_pemakaian')->whereBetween('p.tanggal', [$dari, $sampai])->where('dt.kode_barang', $value->kode_barang)->get()[0];
        $qKeluar = $pemakaian->jmlQty == '' ? 0 : $pemakaian->jmlQty;
        $sKeluar = $pemakaian->jumlah == '' ? 0 : $pemakaian->jumlah;
    
        $ttlQKeluar += $qKeluar;
        $ttlSKeluar += $sKeluar;
    
        $qAkhir = $qtyAwal + $qMasuk - $qKeluar;
        $saldoAkhir = $saldoAwal + $sMasuk - $sKeluar;
    
        $ttlQAkhir = $ttlQAkhir + $qAkhir;
        $ttlSAkhir = $ttlSAkhir + $saldoAkhir;
      @endphp
        <tr>
          <td>{{$loop->iteration}}</td>
          <td>{{$value->kode_barang . ' ~ ' . $value->nama}}</td>
          <td>{{$qtyAwal}}</td>
          <td>{{$saldoAwal}}</td>
          <td>{{$qMasuk}}</td>
          <td>{{number_format($sMasuk,0,',','.')}}</td>
          <td>{{$qKeluar}}</td>
          <td>{{number_format($sKeluar,0,',','.')}}</td>
          <td>{{$qAkhir}}</td>
          <td>{{number_format($saldoAkhir,0,',','.')}}</td>
          <td>{{number_format($saldoAkhir / $qAkhir,0,',','.')}}</td>
        </tr>
      @endforeach
    </tbody>
    <tfoot>
      <tr>
        <td colspan='2' class='text-center'><b>TOTAL</b></td>
        <td>{{$ttlQAwal}}</td>
        <td>{{number_format($ttlSAwal,0,',','.')}}</td>
        <td>{{$ttlQMasuk}}</td>
        <td>{{number_format($ttlSMasuk,0,',','.')}}</td>
        <td>{{$ttlQKeluar}}</td>
        <td>{{number_format($ttlSKeluar,0,',','.')}}</td>
        <td>{{$ttlQAkhir}}</td>
        <td>{{number_format($ttlSAkhir,0,',','.')}}</td>
        <td></td>
    </tr>
    </tfoot>
</table>    
</body>
</html>

<script>
    window.print()
</script>