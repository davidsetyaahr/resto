<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('assets/css/argon.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}" type="text/css">
    <title>Laporan Penjualan</title>
</head>
<body>
<center>
<h3>Laporan Penjualan  (Menu Tidak Terjual)</h3>
<h5>Tanggal: {{date('d-m-Y', strtotime($_GET['dari'])).' s/d '.date('d-m-Y', strtotime($_GET['sampai']))}}</h5>
<br>
</center>
<table width="100%" cellspacing="0" cellpadding="5">
    <thead>
    <tr>
        <th>#</th>
        <th>Kode Menu</th>
        <th>Nama Menu</th>
        <th>HPP</th>
        <th>Harga Jual</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($penjualan as $value)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$value->kode_menu}}</td>
            <td>{{$value->nama}}</td>
            <td>{{$value->hpp}}</td>
            <td>{{number_format($value->harga_jual,0,',','.')}}</td>
            <td>{{$value->status}}</td>
        </tr>
        @endforeach
    </tbody>
</table>    
</body>
</html>

<script>
    window.print()
</script>