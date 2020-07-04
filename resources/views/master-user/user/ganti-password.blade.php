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
            <div class="card-body py-0 row">
              <div class="col-12">
                  @if (session('status'))
                      <br>
                      <div class="alert alert-success alert-dismissible fade show" role="alert">
                          {{ session('status') }}
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                          </button>
                      </div>
                  @endif
              </div>
            </div>
            <form action="{{ route('user.update-password', \Auth::user()->id) }}" method="post">
              @csrf
              @method('put')
              <div class="card-body">
                <label for="" class="form-control-label">Password Lama</label>
                <input type="password" name="old_password" value="{{old('old_password')}}" class="form-control @error('old_password') is-invalid @enderror" placeholder="ex : ******">
                @error('old_password')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
                <br>

                <label for="" class="form-control-label">Password Baru</label>
                <input type="password" name="password" value="{{old('password')}}" class="form-control @error('password') is-invalid @enderror" placeholder="ex : ******">
                @error('password')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
                <br>

                <label for="" class="form-control-label">Konfirmasi Password</label>
                <input type="password" name="konfirmasi_password" value="{{old('konfirmasi_password')}}" class="form-control @error('konfirmasi_password') is-invalid @enderror" placeholder="ex : ******">
                @error('konfirmasi_password')
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