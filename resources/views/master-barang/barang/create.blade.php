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
            <form action="{{ route('barang.store') }}" method="post">
              @csrf
              <div class="card-body">
                  <label for="" class="form-control-label">Kode Barang</label>
                  <input type="text" name="kode_barang" value="{{old('kode_barang')}}" class="form-control @error('kode_barang') is-invalid @enderror" placeholder="ex : BR0001">
                  @error('kode_barang')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                  <br>

                  <label for="" class="form-control-label">Nama Barang</label>
                  <input type="text" name="nama" value="{{old('nama')}}" class="form-control @error('nama') is-invalid @enderror" placeholder="ex : Beras">
                  @error('nama')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                  <br>

                  <label for="" class="form-control-label">Kategori Barang</label>
                  <select name="id_kategori_barang" class="form-control select2 @error('id_kategori_barang') is-invalid @enderror" id="">
                    <option value="">---Pilih Kategori---</option>
                    @foreach ($kategoris as $item)
                      <option value="{{$item->id_kategori_barang}}" {{old('id_kategori_barang') == $item->id_kategori_barang ? 'selected' : ''}} > {{$item->kategori_barang}} </option>
                    @endforeach
                  </select>
                  @error('id_kategori_barang')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                  <br>
                  <br>

                  <label for="" class="form-control-label">Satuan</label>
                  <input type="text" name="satuan" value="{{old('satuan')}}" class="form-control @error('satuan') is-invalid @enderror" placeholder="ex : Kg">
                  @error('satuan')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                  <br>

                  <label for="" class="form-control-label">Stock Awal</label>
                  <input type="number" name="stock" value="{{old('stock',0)}}" class="form-control @error('stock') is-invalid @enderror" placeholder="ex : 25">
                  @error('stock')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                  <br>

                  <label for="" class="form-control-label">Saldo Awal</label>
                  <input type="number" name="saldo" value="{{old('saldo',0)}}" class="form-control @error('saldo') is-invalid @enderror" placeholder="ex : 4000000">
                  @error('saldo')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                  <br>

                  <label for="" class="form-control-label">Mininum Stock</label>
                  <input type="number" name="minimum_stock" value="{{old('minimum_stock',0)}}" class="form-control @error('minimum_stock') is-invalid @enderror" placeholder="ex : 25">
                  @error('minimum_stock')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                  <br>
                  
                  <label for="" class="form-control-label">Exp Date</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><span class="fa fa-calendar"></span></span>
                    </div>
                    <input type="text" name="exp_date" value="{{old('exp_date')}}" class="form-control datepicker @error('exp_date') is-invalid @enderror" placeholder="ex : 2024-08-08">
                  </div> 
                  @error('exp_date')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                  <br>

                  <label for="" class="form-control-label">Lokasi Penyimpanan</label>
                  <input type="text" name="tempat_penyimpanan" value="{{old('tempat_penyimpanan')}}" class="form-control @error('tempat_penyimpanan') is-invalid @enderror" placeholder="ex : Rak A">
                  @error('tempat_penyimpanan')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                  <br>

                  <label for="" class="form-control-label ">Keterangan</label>
                  <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror">{{old('keterangan')}}</textarea>
                  @error('keterangan')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                  <br>
                  {{-- <br> --}}
                  <button type="submit" class="btn btn-primary"><span class="fa fa-save"></span> Simpan</button>
                  <button type="reset" class="btn btn-secondary"><span class="fa fa-times"></span> Reset</button>
              </div>
            </form>
        </div>
    </div>
</div>
@endsection