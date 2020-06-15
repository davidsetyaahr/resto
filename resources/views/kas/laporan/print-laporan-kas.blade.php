<!-- <center>
    <img src="{{asset('assets/img/logobaratha.png')}}" width='50px' alt="">
</center>
 --><table border='1px' width='100%'>
  <thead class="thead-light">
    <tr>
      <th>#</th>
      <th>Kode Kas Keluar</th>
      <th>Tanggal</th>
      <th>Nominal Masuk</th>
      <th>Nominal Keluar</th>
      <th>Keterangan</th>
      <th>Penanggung Jawab</th>
    </tr>
</thead>
<tbody class="list">
  @php $totalMasuk = $totalKeluar = 0; @endphp
  @foreach ($laporan as $value)
  @if ($value->tipe == 'Masuk')
    @php 
        $totalMasuk += $value->nominal;
    @endphp
  @else
    @php 
        $totalKeluar += $value->nominal;
    @endphp
  @endif
    <tr>
      <td>{{$loop->iteration}}</td>
      <td>{{$value->kode_kas}}</td>
      <td>{{date('d-m-Y', strtotime($value->tanggal))}}</td>
      @if ($value->tipe == 'Masuk')
      <td>{{number_format($value->nominal,0,',','.')}}</td>
      <td>-</td>
      @else
      <td>-</td>
      <td>{{number_format($value->nominal,0,',','.')}}</td>
      @endif
      <td>{{$value->keterangan}}</td>
      <td>{{$value->penanggung_jawab}}</td>
    </tr>
  @endforeach
</tbody>
<tfoot class="bg-dark text-white">
    <tr>
      <td colspan='3' class='text-center'><b>TOTAL</b></td>
      <td>{{number_format($totalMasuk,0,',','.')}}</td>
      <td>{{number_format($totalKeluar,0,',','.')}}</td>
      <td colspan='2'></td>
    </tr>
</tfoot>
</table>
<script>
window.print()
</script>