<!-- <center>
    <img src="{{asset('assets/img/logobaratha.png')}}" width='50px' alt="">
</center>
 --><table border='1px' width='100%'>
    <thead class="thead-light">
        <tr>
            <th>#</th>
            <th>Kode Pemakaian</th>
            <th>Tanggal Pemakaian</th>
            <th>Jumlah Quantity</th>
            <th>Total Saldo Pemakaian</th>
        </tr>
    </thead>
    <tbody class="list">
        @php $qty = 0; $total = 0; @endphp
        @foreach ($laporan as $value)
        @php 
            $qty += $value->jumlah_qty;
            $total += $value->total_saldo; 
        @endphp
          <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$value->kode_pemakaian}}</td>
            <td>{{date('d-m-Y', strtotime($value->tanggal))}}</td>
            <td>{{$value->jumlah_qty}}</td>
            <td>{{number_format($value->total_saldo,0,',','.')}}</td>
          </tr>
        @endforeach
    </tbody>
    <tfoot class="bg-dark text-white">
        <tr>
            <td colspan='3' class='text-center'><b>TOTAL</b></td>
            <td>{{$qty}}</td>
            <td>{{number_format($total,0,',','.')}}</td>
        </tr>
    </tfoot>
</table>
<script>
window.print()
</script>