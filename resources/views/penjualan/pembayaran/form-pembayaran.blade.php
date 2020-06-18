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
              <div class="card-body">
              <h6 class="heading-small text-muted mb-4">Informasi Umum</h6>
                  <div class="row pl-lg-4">
                    <div class="col-md-4 mb-2">
                        <label for="" class="form-control-label">Kode Penjualan</label>
                        <input type="text" class="form-control" id="kode" name='kode_penjualan' value="{{$penjualan->kode_penjualan}}" readonly>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label for="" class="form-control-label">Nama Customer</label>
                        <input type="text" class="form-control @error('nama_customer') is-invalid @enderror" name='nama_customer' value="{{$penjualan->nama_customer}}" readonly>
                    </div>

                    <div class="col-md-4 mb-2">
                        <label for="" class="form-control-label">No Handphone</label>
                        <input type="number" class="form-control @error('no_hp') is-invalid @enderror" name='no_hp' value="{{$penjualan->no_hp}}" readonly>
                    </div>
                    
                    <div class="col-md-4 mb-2">
                        <label for="" class="form-control-label">No Meja</label>
                        <input type="number" class="form-control @error('no_meja') is-invalid @enderror" name='no_meja' value="{{$penjualan->no_meja}}" readonly>
                    </div>
                    
                    <div class="col-md-4 mb-2">
                        <label for="" class="form-control-label">Jenis Order</label>
                        <select name="jenis_order" class="form-control select2 @error('jenis_order') is-invalid @enderror " disabled>
                          <option value="">--Pilih Jenis Order--</option>
                          <option value="Dine In" {{$penjualan->jenis_order == 'Dine In' ? 'selected' : ''}} >Dine In</option>
                          <option value="Take Away" {{$penjualan->jenis_order == 'Take Away' ? 'selected' : ''}} >Take Away</option>
                        </select>
                    </div>
                  </div>
                  <hr class="my-4">
                  <h6 class="heading-small text-muted mb-4">Detail Penjualan</h6>
                  <table class="table align-items-center table-flush">
                    <thead class="thead-light">
                      <tr>
                        <th>#</th>
                        <th>Kode Menu</th>
                        <th>Nama</th>
                        <th>Quantity</th>
                        <th>Harga</th>
                        <th>Diskon</th>
                        <th>Subtotal</th>
                      </tr>
                    </thead>
                    <tbody class="list">
                    @foreach ($detail as $value)
                      @php
                          $nama = \App\Menu::select('nama')->where('kode_menu', $value->kode_menu)->get()[0]->nama;
                      @endphp
                      <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$value->kode_menu}}</td>
                        <td>{{$nama}}</td>
                        <td>{{$value->qty}}</td>
                        <td>{{number_format($value->sub_total + $value->diskon,0,',','.')}}</td>
                        <td>{{number_format($value->diskon,0,',','.')}}</td>
                        <td>{{number_format($value->sub_total,0,',','.')}}</td>
                      </tr>
                    @endforeach
                    </tbody>
                  </table>
                  <hr>
                  <div class="row">
                    <div class="col-4 mb-2">
                      <label for=""><strong>Total</strong></label>
                      <input type="number" name="total" id="total" class="form-control" value="{{($penjualan->total_harga - $penjualan->total_diskon) + $penjualan->total_ppn }}" readonly>
                      <span class="text-muted"><i>*sudah termasuk PPN 10%</i></span>
                    </div>
                    <div class="col-4 mb-2">
                      <label for=""><strong>Potongan</strong></label>
                      <input type="number" name="diskon_tambahan" id="diskon_tambahan" class="form-control" value="{{old('diskon_tambahan', 0)}}">
                    </div>
                    <div class="col-4 mb-2">
                      <label for=""><strong>Grand Total</strong></label>
                      <input type="number" name="grand_total" id="grand_total" class="form-control" value="{{($penjualan->total_harga - $penjualan->total_diskon) + $penjualan->total_ppn }}" readonly>
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
                  </div>
                  <div class="mt-4">
                  <button type="submit" class="btn btn-primary"><span class="fa fa-save"></span> Simpan</button>
                  <button type="reset" class="btn btn-secondary"><span class="fa fa-times"></span> Reset</button>
                  </div>
              </div>
            </form>
        </div>
    </div>
</div>
@endsection