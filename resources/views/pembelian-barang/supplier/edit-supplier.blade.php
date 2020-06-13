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
            <form action="{{ route('supplier.update', $supplier->kode_supplier) }}" method="post">
              @csrf
              @method('put')
              <div class="card-body">
                  <label for="" class="form-control-label">Kode Supplier</label>
                  <input type="text" name="kode_supplier" value="{{old('kode_supplier', $supplier->kode_supplier)}}" class="form-control @error('kode_supplier') is-invalid @enderror">
                  @error('kode_supplier')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                  <br>
                  <label for="" class="form-control-label">Nama Supplier</label>
                  <input type="text" name="nama_supplier" value="{{old('nama_supplier', $supplier->nama_supplier)}}" class="form-control @error('nama_supplier') is-invalid @enderror">
                  @error('nama_supplier')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                  <br>
                  <label for="" class="form-control-label">No Hp</label>
                  <input type="text" name="no_hp" value="{{old('no_hp',$supplier->no_hp)}}" class="form-control @error('no_hp') is-invalid @enderror">
                  @error('no_hp')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                  <br>
                  <label for="" class="form-control-label">Alamat</label>
                  <input type="text" name="alamat" value="{{old('alamat',$supplier->alamat)}}" class="form-control @error('alamat') is-invalid @enderror">
                  @error('alamat')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                  <br>
                  <label for="" class="form-control-label">Keterangan</label>
                  <input type="text" name="keterangan" value="{{old('keterangan',$supplier->keterangan)}}" class="form-control @error('keterangan') is-invalid @enderror">
                  @error('keterangan')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                  <br>
                  <button type="submit" class="btn btn-primary"><span class="fa fa-save"></span> Simpan</button>
                  <button type="reset" class="btn btn-secondary"><span class="fa fa-times"></span> Reset</button>
              </div>
            </form>
        </div>
    </div>
</div>
@endsection