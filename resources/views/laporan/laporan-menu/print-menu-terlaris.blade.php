<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('assets/css/argon.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}" type="text/css">
    <title>Laporan Menu Terlaris</title>
</head>
<body>
<center>
<h3>Laporan Menu Terlaris</h3>
<h5>Tanggal: {{date('d-m-Y', strtotime($_GET['dari'])).' s/d '.date('d-m-Y', strtotime($_GET['sampai']))}}</h5>
<br>
</center>
<table width="100%" cellspacing="0" cellpadding="5">
    <thead>
      <tr>
        <th>#</th>
        <th>Kode Barang</th>
        <th>Nama Barang</th>
        <th>Jumlah Penjualan</th>
      </tr>
    </thead>
    <tbody>
      @php $qty = 0; @endphp
      @foreach ($laporan as $value)
      @php 
          $qty += $value->jml;
      @endphp
        <tr>
          <td>{{$loop->iteration}}</td>
          <td>{{$value->kode_menu}}</td>
          <td>{{$value->nama}}</td>
          <td>{{$value->jml}}</td>
        </tr>
      @endforeach
    </tbody>
    <tfoot>
      <tr>
        <td colspan='3' class='text-center'><b>TOTAL</b></td>
        <td>{{$qty}}</td>
    </tr>
    </tfoot>
</table>    
</body>
</html>

@if (isset($_GET['xls']))
    @php
        $name = 'Laporan Menu Terlaris ' . date('d-m-Y', strtotime($_GET['dari'])).' s/d '.date('d-m-Y', strtotime($_GET['sampai'])).'.xls';
        header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename=$name");
    @endphp
@else
    <script>
        window.print()
    </script>
@endif