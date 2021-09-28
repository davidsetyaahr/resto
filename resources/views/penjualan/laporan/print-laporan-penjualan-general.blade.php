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
@if ($_GET['tipe_pembayaran'])
    <h5>Tipe Pembayatan : {{$_GET['tipe_pembayaran']}} </h5>
@endif
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
            <th>Jumlah Item</th>
            <th>Jumlah Qty</th>
            <th>Diskon</th>
            <th>Room Charge</th>
            <th>PPN</th>
            <th>Total</th>
            <th>Tipe</th>
            <th>Keterangan</th>
            </tr>
    </thead>
    <tbody>
    <?php 
        $item = 0;
        $qty = 0;
        $total = 0;
        $total_diskon = 0;
        $total_ppn = 0;
        $total_room_charge  = 0;
        $no = 1;
    ?>
    @foreach ($penjualan as $key => $value)
    <?php
        $item = $item + $value->jumlah_item;
        $qty = $qty + $value->jumlah_qty;
        $total_diskon = $total_diskon + $value->total_diskon + $value->total_diskon_tambahan;
        $total_ppn = $total_ppn + $value->total_ppn;
        $subtotal = $value->total_harga - ($value->total_diskon +  $value->total_diskon_tambahan);
        if ($value->isTravel=='True') {
            $biaya_travel = ($subtotal - $value->total_ppn - $value->room_charge) * 10/100;
            $subtotal = $subtotal - $biaya_travel;
        }
        $total = $total + $subtotal;
        $total_room_charge = $total_room_charge + $value->room_charge;
        $currentDate = date('ymd', strtotime($value->waktu));
        $checkDate = $key > 1 ? date('ymd', strtotime($penjualan[$key - 1]->waktu)) : $currentDate; //untuk check tanggal dari indeks sebelumnya
        if ($currentDate != $checkDate) { //jika tanggal indeks sekarang tidak sama dengan tanggal indeks sebelumnya (ganti hari); maka no kembali ke 1 lagi
            $no = 1;
        }
    ?>
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{'INV'.$currentDate.'-'.sprintf('%04d', $no)}}</td>
                <td>{{date('d-m-Y H:i', strtotime($value->waktu))}}</td>
                <td>{{$value->nama_customer}}</td>
                <td>{{$value->jenis_order}}</td>
                <td>{{$value->jumlah_item}}</td>
                <td>{{$value->jumlah_qty}}</td>
                <td>{{number_format($value->total_diskon + $value->total_diskon_tambahan,0,',','.')}}</td>
                <td>{{number_format($value->room_charge)}}</td>
                <td>{{number_format($value->total_ppn)}}</td>
                <td>{{number_format($subtotal,0,',','.')}}</td>
                <td>{{$value->jenis_bayar}}</td>

                @if ($value->isTravel=='True')
                    <td>Travel</td>
                @else
                    <td>Umum</td>
                @endif
                </tr>
                @php
                    $no++;
                @endphp
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan='5' class='text-center'><b>TOTAL</b></td>
            <td>{{$item}}</td>
            <td>{{$qty}}</td>
            <td>{{number_format($total_diskon,0,',','.')}}</td>
            <td>{{number_format($total_room_charge,0,',','.')}}</td>
            <td>{{number_format($total_ppn,0,',','.')}}</td>
            <td>{{number_format($total,0,',','.')}}</td>
            <td></td>
            <td></td>

        </tr>
    </tfoot>
</table>    
</body>
</html>

@if (isset($_GET['xls']))
    @php
        $name = 'Laporan Penjualan ' . date('d-m-Y', strtotime($_GET['dari'])).' s/d '.date('d-m-Y', strtotime($_GET['sampai'])).'.xls';
        header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename=$name");
    @endphp
@else
    <script>
        window.print()
    </script>
@endif