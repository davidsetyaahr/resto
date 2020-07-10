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
            <form action="{{ route('pembayaran.save', $penjualan->kode_penjualan) }}" method="post">
              @csrf
              @method('put')
              <input type="hidden" id="kode" name='kode_penjualan' value="{{$penjualan->kode_penjualan}}" readonly>
              <div class="card-body">
              <h6 class="heading-small text-muted mb-4">Informasi Umum</h6>
                  <div class="row">
                    <div class="col-md-3 mb-2">
                        <label for="" class="form-control-label">Nama Customer</label>
                        <div class="form-line-check">
                          <span class='fa fa-check-circle'></span>
                          <input type="text" class="form-control form-line @error('nama_customer') is-invalid @enderror" name='nama_customer' value="{{$penjualan->nama_customer}}" readonly>
                        </div>
                    </div>

                    <div class="col-md-3 mb-2">
                        <label for="" class="form-control-label">No Handphone</label>
                        <div class="form-line-check">
                          <span class='fa fa-check-circle'></span>
                          <input type="number" class="form-control form-line @error('no_hp') is-invalid @enderror" name='no_hp' value="{{$penjualan->no_hp}}" readonly>
                      </div>
                    </div>
                    
                    <div class="col-md-3 mb-2">
                        <label for="" class="form-control-label">No Meja</label>
                        <div class="form-line-check">
                          <span class='fa fa-check-circle'></span>
                          <select class='form-control form-line' name='id_meja'>
                            <option value="{{$penjualan->id_meja}}">{{$penjualan->nama_meja}}</option>
                          </select>
                        </div>
                    </div>

                    <div class="col-md-3 mb-2">
                        <label for="" class="form-control-label">Jenis Order</label>
                        <div class="form-line-check">
                          <span class='fa fa-check-circle'></span>
                          <input type="text" class="form-control form-line @error('jenis_order') is-invalid @enderror" name='jenis_order' value="{{$penjualan->jenis_order}}" readonly>
                      </div>
                    </div>
                  </div>
                  <h6 class="heading-small text-muted my-4">Detail Penjualan</h6>
                  <table class="table align-items-center table-flush table-striped table-hover">
                    <thead class="thead-light">
                      <tr>
                        <th>#</th>
                        <th>Menu</th>
                        <th>Kode Menu</th>
                        <th>Nama</th>
                        <th>Quantity</th>
                        <th>Harga</th>
                        <th>Diskon</th>
                        <th>Subtotal</th>
                      </tr>
                    </thead>
                    <tbody class="list">
                    @php 
                      $qty = 0;
                      $harga = 0;
                      $diskon = 0;
                      $subtotal = 0;
                    @endphp
                    @foreach ($detail as $value)
                      @php
                          $menu = \App\Menu::select('nama','foto')->where('kode_menu', $value->kode_menu)->get()[0];
                          $qty = $qty + $value->qty;
                          $diskon = $diskon + $value->diskon;
                          $subtotal = $subtotal + $value->sub_total;
                      @endphp
                      <tr>
                        <td>{{$loop->iteration}}</td>
                        <td><img src="{{asset('assets/img/menu/'.$menu->foto)}}" width='150px' class='img-thumbnail' alt=""></td>
                        <td>{{$value->kode_menu}}</td>
                        <td>{{$menu->nama}}</td>
                        <td>{{$value->qty}}</td>
                        <td>{{number_format($value->sub_total + $value->diskon,0,',','.')}}</td>
                        <td>{{number_format($value->diskon,0,',','.')}}</td>
                        <td>{{number_format($value->sub_total,0,',','.')}}</td>
                      </tr>
                    @endforeach
                      <tr>
                        <td colspan='4' class='text-center'>PPN(10%)</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>{{number_format($penjualan->total_ppn,0,',','.')}}</td>
                      </tr>
                    </tbody>
                    <tfoot class='bg-dark text-white'>
                      <tr>
                        <td colspan='4' class='text-center'>TOTAL</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>{{number_format(($penjualan->total_harga - $penjualan->total_diskon) + $penjualan->total_ppn,0,',','.')}}</td>
                      </tr>
                    </tfoot>
                  </table>
                  <hr>
                  <div class="row">
                      <input type="hidden" name="total" id="total" class="form-control" value="{{($penjualan->total_harga - $penjualan->total_diskon) + $penjualan->total_ppn }}" readonly>
                    <div class="col-4 mb-2">
                      <label for=""><strong>Diskon(%)</strong></label>
                      <input type="number" name="diskon" class="form-control diskon_tambahan" data-tipe='persen' value="{{old('diskon', 0)}}">
                    </div>
                    <div class="col-4 mb-2">
                      <label for=""><strong>Potongan</strong></label>
                      <input type="number" name="diskon_tambahan" class="form-control diskon_tambahan" value="{{old('diskon_tambahan', 0)}}" data-tipe='rp'>
                    </div>
                    <div class="col-4 mb-2">
                      <label for=""><strong>Terbayar</strong></label>
                      <input type="number" name="bayar" id="bayar" class="form-control @error('bayar') is-invalid @enderror" value="{{old('bayar')}}">
                      @error('bayar')
                        <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                    </div>
                    <div class="col-4 mb-2">
                      <label for=""><strong>Kembalian</strong></label>
                      <input type="number" name="kembalian" id="kembalian" class="form-control" value="" readonly>
                    </div>
                    <div class="col-4 mb-2">
                      <label for=""><strong>Metode Pembayaran</strong></label>
                      <select name="jenis_bayar" class="form-control select2 @error('bayar') is-invalid @enderror" id="jenis_bayar">
                        <option value="Tunai" {{old('jenis_bayar') == 'Tunai' ? 'selected' : ''}}>Tunai</option>
                        <option value="Debit" {{old('jenis_bayar') == 'Debit' ? 'selected' : ''}}>Debit</option>
                      </select>
                      @error('jenis_bayar')
                        <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                    </div>
                    <div class="col-4 mb-2">
                      <div id="debit_only">
                        <label for=""><strong>Nomor Kartu</strong></label>
                        <input type="number" name="no_kartu" id="no_kartu" class="form-control" value="{{old('no_kartu')}}">
                        {{-- @error('jenis_bayar')
                          <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                          </span>
                        @enderror --}}
                      </div>
                    </div>
                    <div class="col-md-8 mt-4">
                      <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="isTravel" name="isTravel" value="True" {{old('isTravel') == 'True' ? 'checked' : ''}}>
                        <label class="custom-control-label" for="isTravel">Travel</label>
                        <br>
                        <h5 class="text-default">*Khusus Customer Travel</h5>
                      </div>
                    </div>
                    <div class="col-md-4 mt-3">
                      <h1 class='text-dark'>Grand Total : Rp. <span class="text-orange" id='idrGrandTotal'>{{number_format($penjualan->total_harga - $penjualan->total_diskon + $penjualan->total_ppn,0,',','.')}}</span></h1>
                      <input type="hidden" name="grand_total" id="grand_total" class="form-control form-line text-lg text-orange font-weight-bold" value="{{($penjualan->total_harga - $penjualan->total_diskon) + $penjualan->total_ppn }}">
                    </div>
                    <div class="col-md-3 mt-3">
                        <button type="submit" class="btn btn-primary"><span class="fa fa-save"></span> Simpan</button>
                        <button type="reset" class="btn btn-secondary"><span class="fa fa-times"></span> Reset</button>

                    </div>
                  </div>
                  <div class="mt-4">
                  </div>
              </div>
            </form>
        </div>
    </div>
</div>
@endsection