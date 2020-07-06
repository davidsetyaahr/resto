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
<h3>Laporan Penjualan</h3>
<h5>Tanggal: {{date('d-m-Y', strtotime($_GET['dari'])).' s/d '.date('d-m-Y', strtotime($_GET['sampai']))}}</h5>
<br>
</center>
<table width="100%" cellspacing="0" cellpadding="5">
    <thead>
    <tr>
        <th>#</th>
        <th>Kode Penjualan</th>
        <th>Waktu</th>
        <th>Customer</th>
        <th>Jenis Order</th>
        <th>Keterangan</th>
        <th>Jumlah Item</th>
        <th>Jumlah Qty</th>
        <th>Diskon</th>
        <th>Total</th>
    </tr>
    </thead>
    <tbody>
    <?php 
        $item = 0;
        $qty = 0;
        $total = 0;
        $total_diskon = 0;
    ?>
    @foreach ($penjualan as $value)
    <?php
        $item = $item + $value->jumlah_item;
        $qty = $qty + $value->jumlah_qty;
        $total_diskon = $total_diskon + $value->total_diskon + $value->total_diskon_tambahan;
        $subtotal = $value->total_harga - $value->total_diskon + $value->total_ppn - $value->total_diskon_tambahan;
        if ($value->isTravel=='True') {
            $biaya_travel = ($subtotal - $value->total_ppn) * 10/100;
            $subtotal = $subtotal - $biaya_travel;
        }
        $total = $total + $subtotal;
    ?>
            <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$value->kode_penjualan}}</td>
            <td>{{date('d-m-Y H:i', strtotime($value->waktu))}}</td>
            <td>{{$value->nama_customer}}</td>
            <td>{{$value->jenis_order}}</td>
            @if ($value->isTravel=='True')
                <td>Travel</td>
            @else
                <td>Umum</td>
            @endif
            <td>{{$value->jumlah_item}}</td>
            <td>{{$value->jumlah_qty}}</td>
            <td>{{number_format($value->total_diskon + $value->total_diskon_tambahan,0,',','.')}}</td>
            <td>{{number_format($subtotal,0,',','.')}}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
        <td colspan='6'><center><b>TOTAL</b></center></td>
        <td>{{$item}}</td>
        <td>{{$qty}}</td>
        <td>{{number_format($total_diskon,0,',','.')}}</td>
        <td>{{number_format($total,0,',','.')}}</td>
        </tr>
    </tfoot>
</table>    
</body>
</html>

<script>
    window.print()
</script>