<!-- <center>
    <img src="{{asset('assets/img/logobaratha.png')}}" width='50px' alt="">
</center>
 --><table border='1px' width='100%'>
    <thead class="thead-light">
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
    <tbody class="list">
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
    <tfoot class="bg-dark text-white">
        <tr>
            <td colspan='5' class='text-center'><b>TOTAL</b></td>
            <td>{{$qty}}</td>
            <td>{{number_format($total,0,',','.')}}</td>
        </tr>
    </tfoot>
</table>
<script>
window.print()
</script>