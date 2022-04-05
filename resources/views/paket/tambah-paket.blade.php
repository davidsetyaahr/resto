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
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                  @endif
                <form action="{{route('paket.store')}}" method="post">
                    @csrf
                    <label for="">Menu</label>
                    <select name="menu[]" id="" multiple="multiple" class="form-control select2 @error('menu') is-invalid @enderror">
                        @foreach ($menu as $item)
                        @php
                            $selected = in_array($item->kode_menu,$paket) ? 'selected' : '';   
                        @endphp
                        <option value="{{$item->kode_menu}}" {{$selected}}>{{$item->nama}}</option>
                        @endforeach
                    </select>
                    @error('menu')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                    <br>
                    <br>
                    {{-- <br> --}}
                    <button type="submit" class="btn btn-primary"><span class="fa fa-save"></span> Simpan</button>
                    <button type="reset" class="btn btn-secondary"><span class="fa fa-times"></span> Reset</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection