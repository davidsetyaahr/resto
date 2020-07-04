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
            <form action="{{ route('user.update', $user->id) }}" method="post">
              @csrf
              @method('put')
              <div class="card-body">
                  <label for="" class="form-control-label">Nama User</label>
                  <input type="text" name="nama" value="{{old('nama', $user->nama)}}" class="form-control @error('nama') is-invalid @enderror" placeholder="ex : Lebron James">
                  @error('nama')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                  <br>

                  <label for="" class="form-control-label">Gender</label>
                  <br>                  
                  <div class="custom-control custom-radio custom-control-inline  ml-2 mr-5">
                    <input type="radio" value="Laki-laki" id="customRadioInline1" name="gender" class="custom-control-input" @error('gender') is-invalid @enderror" {{old('gender', $user->gender) == 'Laki-laki' ? 'checked' : ''}}>
                    <label class="custom-control-label" for="customRadioInline1">Laki-laki</label>
                  </div>
                  
                  <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" value="Perempuan" id="customRadioInline2" name="gender" class="custom-control-input" @error('gender') is-invalid @enderror" {{old('gender', $user->gender) == 'Perempuan' ? 'checked' : ''}}>
                    <label class="custom-control-label" for="customRadioInline2">Perempuan</label>
                  </div>
                  @error('gender')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                  <br>
                  <br>

                  <label for="" class="form-control-label">No Handphone</label>
                  <input type="number" name="no_hp" value="{{old('no_hp', $user->no_hp)}}" class="form-control @error('no_hp') is-invalid @enderror" placeholder="ex : 085258******">
                  @error('no_hp')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                  <br>

                  <label for="" class="form-control-label ">Alamat</label>
                  <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror">{{old('alamat', $user->alamat)}}</textarea>
                  @error('alamat')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror                  

                  <hr>

                  <label for="" class="form-control-label">Username</label>
                  <input type="text" name="username" value="{{old('username', $user->username)}}" class="form-control @error('username') is-invalid @enderror" placeholder="ex : lebron">
                  @error('username')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                  <br>

                  <label for="" class="form-control-label">Email</label>
                  <input type="email" name="email" value="{{old('email', $user->email)}}" class="form-control @error('email') is-invalid @enderror" placeholder="ex : lebron@baratha.com">
                  @error('email')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                  <br>
                  
                  @if (\Auth::user()->level == 'Owner')
                    <label for="" class="form-control-label">Level</label>
                    <br>                  
                    <div class="custom-control custom-radio custom-control-inline  ml-2 mr-5">
                      <input type="radio" value="Accounting" id="level1" name="level" class="custom-control-input" @error('level') is-invalid @enderror" {{old('level', $user->level) == 'Accounting' ? 'checked' : ''}}>
                      <label class="custom-control-label" for="level1">Accounting</label>
                    </div>
                    
                    <div class="custom-control custom-radio custom-control-inline mr-5">
                      <input type="radio" value="Kasir" id="level2" name="level" class="custom-control-input" @error('level') is-invalid @enderror" {{old('level', $user->level) == 'Kasir' ? 'checked' : ''}}>
                      <label class="custom-control-label" for="level2">Kasir</label>
                    </div>
                    
                    <div class="custom-control custom-radio custom-control-inline mr-5">
                      <input type="radio" value="Owner" id="level3" name="level" class="custom-control-input" @error('level') is-invalid @enderror" {{old('level', $user->level) == 'Owner' ? 'checked' : ''}}>
                      <label class="custom-control-label" for="level3">Owner</label>
                    </div>
                    
                    <div class="custom-control custom-radio custom-control-inline mr-5">
                      <input type="radio" value="Waiters" id="level4" name="level" class="custom-control-input" @error('level') is-invalid @enderror" {{old('level', $user->level) == 'Waiters' ? 'checked' : ''}}>
                      <label class="custom-control-label" for="level4">Waiters</label>
                    </div>
                    @error('level')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                    <br>
                    <br>
                  @endif
                  

                  <button type="submit" class="btn btn-primary"><span class="fa fa-save"></span> Simpan</button>
                  <button type="reset" class="btn btn-secondary"><span class="fa fa-times"></span> Reset</button>
              </div>
            </form>
        </div>
    </div>
</div>
@endsection