<!-- <center>
    <img src="{{asset('assets/img/logobaratha.png')}}" width='50px' alt="">
</center>
@php
  $bulan = Request::get('bulan');
  $tahun = Request::get('tahun');

  $penjualan = $totalPenjualan->ttlHarga - $totalPenjualan->ttlDiskon - $totalPenjualan->ttlDiskonTambahan;

  $labaRugi = $penjualan + $totalKasMasuk->ttlKasMasuk - $totalPemakaian->ttlPemakaian - $totalKasKeluar->ttlKasKeluar;
@endphp
 --><table border='1px' width='100%'>
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
      {{number_format($penjualan, 0, ',', '.')}}
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
<script>
window.print()
</script>