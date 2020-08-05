<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('assets/css/argon.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}" type="text/css">
    <title>Laporan Laba Rugi</title>
</head>
<body>
<center>
<h3>Laporan Laba Rugi</h3>
<br>
</center>
@php
    $total = 0;
    $total_diskon = 0;
    $total_ppn = 0;
@endphp
@foreach ($penjualan as $value)
  @php
    $total_diskon = $total_diskon + $value->total_diskon + $value->total_diskon_tambahan;
    $total_ppn = $total_ppn + $value->total_ppn;
    $subtotal = $value->total_harga - $value->total_diskon - $value->total_diskon_tambahan;
    if ($value->isTravel=='True') {
        $biaya_travel = ($subtotal) * 10/100;
        $subtotal = $subtotal - $biaya_travel;
    }
    $total = $total + $subtotal;
  @endphp
@endforeach
@php
  $bulan = Request::get('bulan');
  $tahun = Request::get('tahun');
  $labaRugi = $total + $totalKasMasuk->ttlKasMasuk - $totalPemakaian->ttlPemakaian - $totalKasKeluar->ttlKasKeluar;
@endphp
<table border='1px' width='100%'>
  <thead class="thead-light">
    <tr>
      <th style="text-align: center">Keterangan</th>
      <th style="text-align: center">Jumlah</th>
    </tr>
  </thead>
<tbody class="list">
  <tr>
    <td colspan="2"><strong>Pemasukan</strong></td>
  </tr>
  <tr>
    <td style="text-align: center">Penjualan</td>
    <td style="text-align: center">
      {{number_format($total, 0, ',', '.')}}
    </td>
  </tr>
  <tr>
    <td style="text-align: center">Kas Masuk</td>
    <td style="text-align: center">
      {{number_format($totalKasMasuk->ttlKasMasuk, 0, ',', '.')}}
    </td>
  </tr>
  <tr>
    <td colspan="2"><strong>Pengeluaran</strong></td>
  </tr>
  <tr>
    <td style="text-align: center">Pemakaian</td>
    <td style="text-align: center">
      {{number_format($totalPemakaian->ttlPemakaian, 0, ',', '.')}}
    </td>
  </tr>
  <tr>
    <td style="text-align: center">Kas Keluar</td>
    <td style="text-align: center">
      {{number_format($totalKasKeluar->ttlKasKeluar, 0, ',', '.')}}
    </td>
  </tr>
</tbody>
<tfoot class="bg-dark text-white">
  <tr>
    <td class="text-center"><b>Laba Rugi</b></td>
    <td class="text-center"><b>{{number_format($labaRugi, 0, ',', '.')}}</b></td>
  </tr>
</tfoot>
</table>  
</body>
</html>

<script>
    window.print()
</script>