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
                    <form class="navbar-search navbar-search-light" id="navbar-search-main">
                        <div class="form-group mb-0">
                        <div class="input-group input-group-alternative input-group-merge">
                            <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            </div>
                            <input class="form-control" placeholder="Search" type="text">
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
                    <th>#</th>
                    <th>Kode Supplier</th>
                    <th>Nama Supplier</th>
                    <th>No HP</th>
                    <th>Alamat</th>
                    <th>Keterangan</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody class="list">
                @foreach ($supplier as $value)
                      <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$value->kode_supplier}}</td>
                        <td>{{$value->nama_supplier}}</td>
                        <td>{{$value->no_hp}}</td>
                        <td>{{$value->alamat}}</td>
                        <td>{{$value->keterangan}}</td>
                        <td class="text-right">
                          <div class="dropdown">
                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <i class="fas fa-ellipsis-v"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                              <a class="dropdown-item" href="{{ route('supplier.edit', $value->kode_supplier) }}">Edit</a>
                              <form action="{{ route('supplier.destroy', $value->kode_supplier) }}" method="post">
                                @csrf
                                @method('delete')
                                <button type="button" class="mr-1 dropdown-item" onclick="confirm('{{ __("Apakah anda yakin ingin menghapus?") }}') ? this.parentElement.submit() : ''">
                                  Hapus
                                </button>
                              </form>
                              
                            </div>
                          </div>
                        </td>
                      </tr>
                  @endforeach
                </tbody>
              </table>
              {{$supplier->appends(Request::all())->links()}}
            </div>
         </div>
    </div>
</div>
@endsection