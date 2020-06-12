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
            <form action="{{ route('kategori-barang.update', $kategori_barang->id_kategori_barang) }}" method="post">
              @csrf
              @method('put')
              <div class="card-body">
                  <label for="" class="form-control-label">Kategori Barang</label>
                  <input type="text" name="kategori_barang" value="{{old('kategori_barang', $kategori_barang->kategori_barang)}}" class="form-control @error('kategori_barang') is-invalid @enderror" placeholder="ex : Bumbu">
                  @error('kategori_barang')
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