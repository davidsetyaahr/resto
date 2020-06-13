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
            <form action="{{ route('kategori-menu.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <label for="" class="form-control-label">Kategori Menu</label>
                <input type="text" name="kategori_menu" class="form-control" value="{{old('kategori_menu')}}" @error('kategori_menu') is-invalid @enderror placeholder="ex : Minuman">
                @error('kategori_menu')
                    <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                    </span>
                @enderror
                    <br>
                    <button class="btn btn-primary"><span class="fa fa-save"></span> Simpan</button>
                    <button class="btn btn-secondary"><span class="fa fa-times"></span> Reset</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection