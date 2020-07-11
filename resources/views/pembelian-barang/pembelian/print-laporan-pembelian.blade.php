<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('assets/css/argon.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}" type="text/css">
    <title>Laporan Pembelian Barang</title>
</head>
<body>
<center>
<h3>Laporan Pembelian Barang</h3>
<h5>Tanggal: {{date('d-m-Y', strtotime($_GET['dari'])).' s/d '.date('d-m-Y', strtotime($_GET['sampai']))}}</h5>
<br>
</center>
<table width="100%" cellspacing="0" cellpadding="5">
    <thead>
      <tr>
        <th>#</th>
        <th>Kode Pembelian</th>
        <th>Tanggal Pembelian</th>
        <th>Kode Supplier</th>
        <th>Jumlah Item</th>
        <th>Jumlah Qty</th>
        <th>Total</th>
      </tr>
    </thead>
    <tbody>
        @php $qty = 0; $total = 0; @endphp
        @foreach ($laporan as $value)
        @php 
            $qty = $qty + $value->jumlah_qty; 
            $total = $qty + $value->total; 
        @endphp
                <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$value->kode_pembelian}}</td>
                <td>{{$value->tanggal}}</td>
                <td>{{$value->kode_supplier}}</td>
                <td>{{$value->jumlah_item}}</td>
                <td>{{$value->jumlah_qty}}</td>
                <td>{{number_format($value->total,0,',','.')}}</td>
                </tr>
            @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan='5' class='text-center'><b>TOTAL</b></td>
            <td>{{$qty}}</td>
            <td>{{number_format($total,0,',','.')}}</td>
        </tr>
    </tfoot>
</table>    
</body>
</html>

<script>
    window.print()
</script>