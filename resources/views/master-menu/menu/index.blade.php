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
                    <form action="{{ route('menu.index') }}" class="navbar-search navbar-search-light" id="navbar-search-main">
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
            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col" class="sort" data-sort="name">#</th>
                    <th scope="col" class="sort" data-sort="budget">Kode</th>
                    <th scope="col" class="sort" data-sort="status">Nama</th>
                    <th scope="col" class="sort" data-sort="name">Kategori</th>
                    <th scope="col" class="sort" data-sort="name">HPP</th>
                    <th scope="col" class="sort" data-sort="name">Harga Jual</th>
                    <th scope="col" class="sort" data-sort="name">Status</th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody class="list">
                    @php
                        $no = 1;    
                    @endphp
                    @foreach ($menus as $value)
                        <tr>
                            <td>{{$no}}</td>
                            <td>{{$value->kode_menu}}</td>
                            <td>{{$value->nama}}</td>
                            <td>{{$value->kategori->kategori_menu}}</td>
                            <td>{{$value->hpp}}</td>
                            <td>{{$value->harga_jual}}</td>
                            <td>{{$value->status}}</td>
                            <td class="text-right">
                                <div class="dropdown">
                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                <a class="dropdown-item" href="{{ route('menu.edit', $value->kode_menu) }}">Edit</a>
                                    {{--<a class="dropdown-item" href="#">hapus</a>--}}
                                </div>
                                </div>
                            </td>
                        </tr>
                        @php
                            $no++;
                        @endphp
                    @endforeach
                  </tr>
                </tbody>
              </table>
            </div>
         </div>
    </div>
</div>
@endsection