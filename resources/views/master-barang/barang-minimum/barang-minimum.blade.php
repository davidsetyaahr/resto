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
                <div class="col-4 text-right">
                    <form action="{{ route('barang-minimum') }}" class="navbar-search navbar-search-light" id="navbar-search-main">
                        <div class="form-group mb-0">
                        <div class="input-group input-group-alternative input-group-merge">
                            <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            </div>
                            <input name="keyword" class="form-control" placeholder="Search" type="text" value="{{Request::get('keyword')}}">
                        </div>
                        </div>
                        <button type="button" class="close" data-action="search-close" data-target="#navbar-search-main" aria-label="Close">
                        <span aria-hidden="true">×</span>
                        </button>
                    </form>
                </div>
              </div>
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
            <div class="table-responsive">
              <table class="table table-hover align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col" class="sort" data-sort="name">#</th>
                    <th scope="col" class="sort" data-sort="name">Kode Barang</th>
                    <th scope="col" class="sort" data-sort="name">Nama Barang</th>
                    <th scope="col" class="sort" data-sort="name">Stock</th>
                    <th scope="col" class="sort" data-sort="name">Satuan</th>
                    <th scope="col" class="sort" data-sort="name">Saldo</th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody class="list">
                  @php
                      $no = 1;
                  @endphp
                  @foreach ($barangMinimum as $value)
                      <tr>
                        <td>{{$no}}</td>
                        <td>{{$value->kode_barang}}</td>
                        <td>{{$value->nama}}</td>
                        <td>{{$value->stock}}</td>
                        <td>{{$value->satuan}}</td>
                        <td>{{$value->saldo}}</td>
                      </tr>
                      @php
                          $no++
                      @endphp
                  @endforeach
                </tbody>
              </table>
            </div>
         </div>
    </div>
</div>
@endsection