<!-- <center>
    <img src="{{asset('assets/img/logobaratha.png')}}" width='50px' alt="">
</center>
 --><table border='1px' width='100%'>
  <thead class="thead-light">
    <tr>
      <th>#</th>
      <th>Kode Kas Keluar</th>
      <th>Tanggal</th>
      <th>Nominal</th>
      <th>Keterangan</th>
      <th>Penanggung Jawab</th>
    </tr>
</thead>
<tbody class="list">
  @php $total = 0; @endphp
  @foreach ($laporan as $value)
  @php 
      $total += $value->nominal; 
  @endphp
    <tr>
      <td>{{$loop->iteration}}</td>
      <td>{{$value->kode_kas}}</td>
      <td>{{date('d-m-Y', strtotime($value->tanggal))}}</td>
      <td>{{number_format($value->nominal,0,',','.')}}</td>
      <td>{{$value->keterangan}}</td>
      <td>{{$value->penanggung_jawab}}</td>
    </tr>
  @endforeach
</tbody>
<tfoot class="bg-dark text-white">
    <tr>
      <td colspan='3' class='text-center'><b>TOTAL</b></td>
      <td>{{number_format($total,0,',','.')}}</td>
      <td colspan='2'></td>
    </tr>
</tfoot>
</table>
<script>
window.print()
</script>