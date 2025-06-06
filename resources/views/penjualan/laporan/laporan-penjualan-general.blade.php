`<div class="table-responsive mt-3">
    <table class="table align-items-center table-flush">
    <thead class="thead-light">
        <tr>
        <th>#</th>
        <th>Kode Penjualan</th>
        <th>Waktu Pembayaran</th>
        <th>Customer</th>
        <th>Jenis Order</th>
        <th>Jumlah Item</th>
        <th>Jumlah Qty</th>
        <th>Diskon</th>
        <th>Room Charge</th>
        <th>PPN</th>
        <th>Total</th>
        <th>Tipe</th>
        <th>Kasir</th>
        <th>Keterangan</th>
        </tr>
    </thead>
    <tbody class="list">
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
        $subtotal = $value->total_harga - ($value->total_diskon + $value->total_diskon_tambahan);
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
            <td>{{date('d-m-Y H:i', strtotime($value->waktu_bayar))}}</td>
            <td>{{$value->nama_customer}}</td>
            <td>{{$value->jenis_order}}</td>
            <td>{{$value->jumlah_item}}</td>
            <td>{{$value->jumlah_qty}}</td>
            <td>{{number_format($value->total_diskon + $value->total_diskon_tambahan,0,',','.')}}</td>
            <td>{{number_format($value->room_charge)}}</td>
            <td>{{number_format($value->total_ppn)}}</td>
            <td>{{number_format($subtotal,0,',','.')}}</td>
            <td>{{$value->jenis_bayar}}</td>
            <td>{{$value->nama}}</td>
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
    <tfoot class="bg-dark text-white">
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
        <td></td>

        </tr>
    </tfoot>
    </table>
</div>
