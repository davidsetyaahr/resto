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
                    <form action="{{ route('user.index') }}" class="navbar-search navbar-search-light" id="navbar-search-main">
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
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col" class="sort" data-sort="name">#</th>
                    <th scope="col" class="sort" data-sort="name">Nama</th>
                    <th scope="col" class="sort" data-sort="name">Gender</th>
                    <th scope="col" class="sort" data-sort="name">Email</th>
                    <th scope="col" class="sort" data-sort="name">No Hp</th>
                    <th scope="col" class="sort" data-sort="name">Alamat</th>
                    <th scope="col" class="sort" data-sort="name">Level</th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody class="list">
                  @php
                    $page = Request::get('page');
                    $no = !$page || $page == 1 ? 1 : ($page - 1) * 10 + 1;
                  @endphp
                  @foreach ($user as $value)
                      <tr>
                        <td>{{$no}}</td>
                        <td>{{$value->nama}}</td>
                        <td>{{$value->gender}}</td>
                        <td>{{$value->email}}</td>
                        <td>{{$value->no_hp}}</td>
                        <td>{{$value->alamat}}</td>
                        <td>{{$value->level}}</td>
                        <td class="text-right">
                          <div class="dropdown">
                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <i class="fas fa-ellipsis-v"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                              <a class="dropdown-item" href="{{ route('user.edit', $value->id) }}">Edit</a>
                              {{-- fitur delete sementara nonaktif --}}
                              {{-- <form action="{{ route('user.destroy', $value->kode_barang) }}" method="post">
                                @csrf
                                @method('delete')
                                <button type="button" class="mr-1 dropdown-item" onclick="confirm('{{ __("Apakah anda yakin ingin menghapus?") }}') ? this.parentElement.submit() : ''">
                                  Hapus
                                </button>
                              </form> --}}
                              
                            </div>
                          </div>
                        </td>
                      </tr>
                      @php
                          $no++
                      @endphp
                  @endforeach
                </tbody>
              </table>
              {{$user->appends(Request::all())->links()}}
            </div>
         </div>
    </div>
</div>
@endsection