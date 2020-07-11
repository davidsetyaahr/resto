<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('assets/css/argon.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}" type="text/css">
    <title>Laporan Kas</title>
</head>
<body>
<center>
<h3>Laporan Kas</h3>
<h5>Tanggal: {{date('d-m-Y', strtotime($_GET['dari'])).' s/d '.date('d-m-Y', strtotime($_GET['sampai']))}}</h5>
<br>
</center>
<table width="100%" cellspacing="0" cellpadding="5">
    <thead>
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
    <tbody>
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
    <tfoot>
        <tr>
          <td colspan='3' class='text-center'><b>TOTAL</b></td>
          <td>{{number_format($totalMasuk,0,',','.')}}</td>
          <td>{{number_format($totalKeluar,0,',','.')}}</td>
          <td colspan='2'></td>
        </tr>
    </tfoot>
</table>    
</body>
</html>

<script>
    window.print()
</script>