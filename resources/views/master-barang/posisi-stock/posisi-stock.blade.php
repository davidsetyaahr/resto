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
            <div class="row align-items-center">
                <div class="col-4">
                    <label for="" class="form-control-label">Dari Tanggal</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><span class="fa fa-calendar"></span></span>
                        </div>
                        <input type="text" class="datepicker form-control" name="dari" value="{{Request::get('dari')}}">
                    </div>                
                </div>
                <div class="col-4">
                    <label for="" class="form-control-label">Sampai Tanggal</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><span class="fa fa-calendar"></span></span>
                        </div>
                        <input type="text" class="datepicker form-control" name="sampai" value="{{Request::get('sampai')}}">
                    </div>                
                </div>
                <div class="col-4">
                    <label for="" class="form-control-label">Kode Barang</label>
                    <select name="kode_barang" class="form-control select2">
                      <option value="">--Semua Barang--</option>
                      @foreach ($allBarang as $item)
                        <option value="{{$item->kode_barang}}" {{$item->kode_barang == Request::get('kode_barang') ? 'selected' : ''}} > {{$item->kode_barang . ' ~ ' . $item->nama}} </option>
                      @endforeach
                    </select>
                </div>
                <div class="col mt-4">
                <button type="submit" class="btn btn-primary"><span class="fa fa-save"></span> Simpan</button>
                  <button type="reset" class="btn btn-secondary"><span class="fa fa-times"></span> Reset</button>
                </div>
              </div>
            </form>
            @if ($barang)
              @php
                  $dari = Request::get('dari');
                  $sampai = Request::get('sampai');
                  $kode = Request::get('kode');
              @endphp
              <a href="{{ route('posisi-stock').'?dari='.$dari.'&sampai='.$sampai.'&print=true' }}" class="btn btn-sm btn-success float-right py-2 px-3"><span class="fa fa-print"></span> Print</a>
              <br>
              <div class="table-responsive mt-4">
                <table class="table align-items-center table-flush table-hover table-striped">
                  <thead class="thead-light">
                    <tr>
                      <th>#</th>
                      <th>Barang</th>
                      <th>Qty Awal</th>
                      <th>Saldo Awal</th>
                      <th colspan="2">Jumlah Masuk</th>
                      <th colspan="2">Jumlah Keluar</th>
                      <th>Qty Akhir</th>
                      <th>Saldo Akhir</th>
                      <th>Harga Satuan</th>
                    </tr>
                  </thead>
                  <tbody class="list">
                  @php 
                    $dari = Request::get('dari');
                    $sampai = Request::get('sampai');
                    $kode = Request::get('kode');

                    $ttlQAwal = $ttlSAwal = $ttlQMasuk = $ttlSMasuk = $ttlQKeluar = $ttlSKeluar = $ttlQAkhir = $ttlSAkhir = 0;
                  @endphp
                  @foreach ($barang as $value)
                  @php
                    $qtyAwal = $value->stock_awal;
                    $saldoAwal = $value->saldo_awal;

                    $pembelianAwal = \DB::table(\DB::raw('detail_pembelian as dt'))->select(\DB::raw('SUM(dt.qty) as jmlQty'), \DB::raw('SUM(dt.subtotal) as jumlah'))->join('pembelian as p', 'p.kode_pembelian', '=', 'dt.kode_pembelian')->where('p.tanggal', '<', "$dari")->where('dt.kode_barang', $value->kode_barang)->get()[0];

                    $qtyAwal += $pembelianAwal->jmlQty;
                    $saldoAwal += $pembelianAwal->jumlah;
                    
                    $pemakaianAwal = \DB::table(\DB::raw('detail_pemakaian as dt'))->select(\DB::raw('SUM(dt.qty) as jmlQty'), \DB::raw('SUM(dt.subtotal_saldo) as jumlah'))->join('pemakaian as p', 'p.kode_pemakaian', '=', 'dt.kode_pemakaian')->where('p.tanggal', '<', "$dari")->where('dt.kode_barang', $value->kode_barang)->get()[0];

                    $qtyAwal -= $pemakaianAwal->jmlQty;
                    $saldoAwal -= $pemakaianAwal->jumlah;

                    $ttlQAwal += $qtyAwal;
                    $ttlSAwal += $saldoAwal;

                    $pembelian = \DB::table(\DB::raw('detail_pembelian as dt'))->select(\DB::raw('SUM(dt.qty) as jmlQty'), \DB::raw('SUM(dt.subtotal) as jumlah'))->join('pembelian as p', 'p.kode_pembelian', '=', 'dt.kode_pembelian')->whereBetween('p.tanggal', [$dari, $sampai])->where('dt.kode_barang', $value->kode_barang)->get()[0];
                    $qMasuk = $pembelian->jmlQty == '' ? 0 : $pembelian->jmlQty;
                    $sMasuk = $pembelian->jumlah == '' ? 0 : $pembelian->jumlah;

                    $ttlQMasuk += $qMasuk;
                    $ttlSMasuk += $sMasuk;

                    $pemakaian = \DB::table(\DB::raw('detail_pemakaian as dt'))->select(\DB::raw('SUM(dt.qty) as jmlQty'), \DB::raw('SUM(dt.subtotal_saldo) as jumlah'))->join('pemakaian as p', 'p.kode_pemakaian', '=', 'dt.kode_pemakaian')->whereBetween('p.tanggal', [$dari, $sampai])->where('dt.kode_barang', $value->kode_barang)->get()[0];
                    $qKeluar = $pemakaian->jmlQty == '' ? 0 : $pemakaian->jmlQty;
                    $sKeluar = $pemakaian->jumlah == '' ? 0 : $pemakaian->jumlah;

                    $ttlQKeluar += $qKeluar;
                    $ttlSKeluar += $sKeluar;

                    $qAkhir = $qtyAwal + $qMasuk - $qKeluar;
                    $saldoAkhir = $saldoAwal + $sMasuk - $sKeluar;

                    $ttlQAkhir = $ttlQAkhir + $qAkhir;
                    $ttlSAkhir = $ttlSAkhir + $saldoAkhir;
                  @endphp
                    <tr>
                      <td>{{$loop->iteration}}</td>
                      <td>{{$value->kode_barang . ' ~ ' . $value->nama}}</td>
                      <td>{{$qtyAwal}}</td>
                      <td>{{$saldoAwal}}</td>
                      <td>{{$qMasuk}}</td>
                      <td>{{number_format($sMasuk,0,',','.')}}</td>
                      <td>{{$qKeluar}}</td>
                      <td>{{number_format($sKeluar,0,',','.')}}</td>
                      <td>{{$qAkhir}}</td>
                      <td>{{number_format($saldoAkhir,0,',','.')}}</td>
                      <td>{{number_format($saldoAkhir / $qAkhir,0,',','.')}}</td>
                    </tr>
                  @endforeach
                  </tbody>
                  <tfoot class="bg-dark text-white">
                      <tr>
                          <td colspan='2' class='text-center'><b>TOTAL</b></td>
                          <td>{{$ttlQAwal}}</td>
                          <td>{{number_format($ttlSAwal,0,',','.')}}</td>
                          <td>{{$ttlQMasuk}}</td>
                          <td>{{number_format($ttlSMasuk,0,',','.')}}</td>
                          <td>{{$ttlQKeluar}}</td>
                          <td>{{number_format($ttlSKeluar,0,',','.')}}</td>
                          <td>{{$ttlQAkhir}}</td>
                          <td>{{number_format($ttlSAkhir,0,',','.')}}</td>
                          <td></td>
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