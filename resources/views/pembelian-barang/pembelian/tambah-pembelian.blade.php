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
            <form action="{{ route('supplier.store') }}" method="post">
              @csrf
              <div class="card-body">
              <h6 class="heading-small text-muted mb-4">Informasi Umum</h6>
                  <div class="row pl-lg-4">
                    <div class="col-md-4">
                        <label for="" class="form-control-label">Kode Pembelian</label>
                        <input type="text" class="form-control" name='kode_pembelian' value='' readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="" class="form-control-label">Tanggal Pembelian</label>
                        <div class="input-group mb-3">
                          <div class="input-group-prepend">
                              <span class="input-group-text"><span class="fa fa-calendar"></span></span>
                          </div>
                        <input type="text" name="tanggal" value="{{old('tanggal')}}" class="form-control datepicker @error('tanggal') is-invalid @enderror">
                        </div>
                        @error('tanggal')
                          <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="" class="form-control-label">Kode Supplier</label>
                        <select name="" class="form-control select2 @error('kode_supplier') is-invalid @enderror" id="">
                          <option value=''>---Select---</option>
                          @foreach($supplier as $value)
                            <option value="{{$value->kode_supplier}}">{{$value->nama_supplier.' ~ '.$value->nama_supplier}}</option>
                          @endforeach
                        </select>
                        @error('kategori_barang')
                          <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                    </div>
                  </div>
                  <hr class="my-4">
                  <h6 class="heading-small text-muted mb-4">Detail Pembelian</h6>
                  <div class="row pl-lg-4">
                    <div class="col-md-4">
                        <label for="" class="form-control-label">Barang</label>
                        <select name="" class="form-control select2 @error('kode_barang') is-invalid @enderror" id="">
                          <option value=''>---Select---</option>
                        </select>
                        @error('kode_barang')
                          <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="" class="form-control-label">Qty</label>
                        <input type="number" name="qty" value="{{old('qty')}}" class="form-control @error('qty') is-invalid @enderror">
                        @error('qty')
                          <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="" class="form-control-label">Harga (Satuan)</label>
                        <input type="number" name="harga" value="{{old('harga')}}" class="form-control @error('harga') is-invalid @enderror">
                        @error('harga')
                          <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                    </div>
                  </div>
                  <br>
                  <button type="submit" class="btn btn-primary"><span class="fa fa-save"></span> Simpan</button>
                  <button type="reset" class="btn btn-secondary"><span class="fa fa-times"></span> Reset</button>
              </div>
            </form>
        </div>
    </div>
</div>
@endsection