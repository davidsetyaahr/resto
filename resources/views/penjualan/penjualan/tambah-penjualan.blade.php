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
            <form action="{{ route('penjualan.store') }}" method="post">
              @csrf
              <div class="card-body">
              <h6 class="heading-small text-muted mb-4">Informasi Umum</h6>
                  <div class="row pl-lg-4">
                    <div class="col-md-4 mb-2">
                        <label for="" class="form-control-label">Kode Penjualan</label>
                        <input type="text" class="form-control" id="kode" name='kode_penjualan' value="{{$kode_penjualan}}" readonly>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label for="" class="form-control-label">Nama Customer</label>
                        <input type="text" class="form-control @error('nama_customer') is-invalid @enderror" name='nama_customer' value="{{old('nama_customer')}}">
                        @error('nama_customer')
                          <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-2">
                        <label for="" class="form-control-label">No Handphone</label>
                        <input type="number" class="form-control @error('no_hp') is-invalid @enderror" name='no_hp' value="{{old('no_hp')}}">
                        @error('no_hp')
                          <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                    </div>
                    
                    <div class="col-md-4 mb-2">
                        <label for="" class="form-control-label">No Meja</label>
                        <input type="number" class="form-control @error('no_meja') is-invalid @enderror" name='no_meja' value="{{old('no_meja')}}">
                        @error('no_meja')
                          <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                    </div>
                    
                    <div class="col-md-4 mb-2">
                        <label for="" class="form-control-label">Jenis Order</label>
                        <select name="jenis_order" class="form-control select2 @error('jenis_order') is-invalid @enderror ">
                          <option value="">--Pilih Jenis Order--</option>
                          <option value="Dine In" {{old('jenis_order') == 'Dine In' ? 'selected' : ''}} >Dine In</option>
                          <option value="Take Away" {{old('jenis_order') == 'Take Away' ? 'selected' : ''}} >Take Away</option>
                        </select>
                        @error('jenis_order')
                          <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                    </div>
                  </div>
                  <hr class="my-4">
                  <h6 class="heading-small text-muted mb-4">Detail Penjualan</h6>
                  <div class="pl-lg-4" id='urlAddDetail' data-url="{{url('penjualan/penjualan/addDetailPenjualan')}}">
                  @if(!is_null(old('kode_menu')))
                    @php $no = 0 @endphp
                    @foreach(old('kode_menu') as $n => $value)
                      @php $no++ @endphp
                      @include('penjualan.penjualan.tambah-detail-penjualan',['hapus' => false, 'no' => $no, 'menu' => $menu])
                    @endforeach
                  @else
                    @include('penjualan.penjualan.tambah-detail-penjualan',['hapus' => false, 'no' => 1, 'menu' => $menu])
                  @endif
                  </div>
                  <div class="row">
                    <div class="col-3">
                      <h2 class='pl-4 mt-3'>Total : <span id='total_harga' class="text-orange">0</span></h2>
                    </div>
                    <div class="col-3">
                      <h2 class='pl-4 mt-3'>PPN : <span id='total_ppn' class="text-orange">0</span></h2>
                    </div>
                    <div class="col-3">
                      <h2 class='pl-4 mt-3'>Grand Total : <span id='grand_total' class="text-orange">0</span></h2>
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