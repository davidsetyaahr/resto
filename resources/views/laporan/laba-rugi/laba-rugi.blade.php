@extends('common/template')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                <div class="col-8">
                  <h3 class="mb-0">{{$pageInfo}}</h3>
                </div>
              </div>
            </div>
            <div class="card-body">
            <form action="" method="get">
            @php
              $tahun = date('Y');
            @endphp
            <div class="row align-items-center">
                <div class="col-4">
                    <label for="" class="form-control-label">Bulan</label>
                    <select name="bulan" class="form-control select2" required>
                      <option value="">--Pilih Bulan--</option>
                      <option value="01" {{Request::get('bulan') == '01' ? 'selected' : ''}}>Januari</option>
                      <option value="02" {{Request::get('bulan') == '02' ? 'selected' : ''}}>Februari</option>
                      <option value="03" {{Request::get('bulan') == '03' ? 'selected' : ''}}>Maret</option>
                      <option value="04" {{Request::get('bulan') == '04' ? 'selected' : ''}}>April</option>
                      <option value="05" {{Request::get('bulan') == '05' ? 'selected' : ''}}>Mei</option>
                      <option value="06" {{Request::get('bulan') == '06' ? 'selected' : ''}}>Juni</option>
                      <option value="07" {{Request::get('bulan') == '07' ? 'selected' : ''}}>Juli</option>
                      <option value="08" {{Request::get('bulan') == '08' ? 'selected' : ''}}>Agustus</option>
                      <option value="09" {{Request::get('bulan') == '09' ? 'selected' : ''}}>September</option>
                      <option value="10" {{Request::get('bulan') == '10' ? 'selected' : ''}}>Oktober</option>
                      <option value="11" {{Request::get('bulan') == '11' ? 'selected' : ''}}>November</option>
                      <option value="12" {{Request::get('bulan') == '12' ? 'selected' : ''}}>Desember</option>
                    </select>
                </div>
                <div class="col-4">
                    <label for="" class="form-control-label">Tahun</label>
                    <select name="tahun" class="form-control select2" required>
                      <option value="">--Pilih Tahun--</option>
                      <option value="2020" {{$tahun == '2020' ? 'selected' : ''}}>2020</option>
                      <option value="2021" {{$tahun == '2021' ? 'selected' : ''}}>2021</option>
                      <option value="2022" {{$tahun == '2022' ? 'selected' : ''}}>2022</option>
                      <option value="2023" {{$tahun == '2023' ? 'selected' : ''}}>2023</option>
                      <option value="2024" {{$tahun == '2024' ? 'selected' : ''}}>2024</option>
                      <option value="2025" {{$tahun == '2025' ? 'selected' : ''}}>2025</option>
                    </select>
                </div>
                <div class="col mt-4">
                <button type="submit" class="btn btn-primary"><span class="fa fa-save"></span> Simpan</button>
                  <button type="reset" class="btn btn-secondary"><span class="fa fa-times"></span> Reset</button>
                </div>
              </div>
            </form>
            @if (isset($_GET['bulan']))
              @php
                  $total = 0;
                  $total_diskon = 0;
                  $total_ppn = 0;
              @endphp
              @foreach ($penjualan as $value)
                @php
                  $total_diskon = $total_diskon + $value->total_diskon + $value->total_diskon_tambahan;
                  $total_ppn = $total_ppn + $value->total_ppn;
                  $subtotal = $value->total_harga - $value->total_diskon + $value->total_ppn - $value->total_diskon_tambahan + $value->room_charge;
                  if ($value->isTravel=='True') {
                      $biaya_travel = ($subtotal - $value->total_ppn - $value->room_charge) * 10/100;
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
              <a href="{{ route('laba-rugi').'?bulan='.$bulan.'&tahun='.$tahun.'&print=true' }}" class="btn btn-sm btn-success float-right py-2 px-3"><span class="fa fa-print"></span> Print</a>
              <br>
              <div class="table-responsive mt-4">
                <table class="table align-items-center table-flush table-hover table-striped">
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
              </div>
            @endif
            </div>
        </div>
    </div>
</div>
@endsection