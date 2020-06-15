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
                    <form action="{{ route('kas.index') }}" class="navbar-search navbar-search-light" id="navbar-search-main">
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
                    <th scope="col" class="sort" data-sort="name">Kode Kas</th>
                    <th scope="col" class="sort" data-sort="name">Tanggal</th>
                    <th scope="col" class="sort" data-sort="budget">Nominal Masuk</th>
                    <th scope="col" class="sort" data-sort="budget">Nominal Keluar</th>
                    <th scope="col" class="sort" data-sort="name">Keterangan</th>
                    <th scope="col" class="sort" data-sort="name">Penanggung Jawab</th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody class="list">
                  @php
                    $page = Request::get('page');
                    $no = !$page || $page == 1 ? 1 : ($page - 1) * 10 + 1;
                  @endphp
                  @foreach ($kas as $value)
                      <tr>
                        <td>{{$no}}</td>
                        <td>{{$value->kode_kas}}</td>
                        <td>{{date('d-m-Y', strtotime($value->tanggal))}}</td>
                        @if ($value->tipe == 'Masuk')
                          <td>{{number_format($value->nominal,0,',','.')}}</td>
                          <td>-</td>
                        @else
                          <td>-</td>
                          <td>{{number_format($value->nominal,0,',','.')}}</td>
                        @endif
                        <td>{{$value->keterangan}}</td>
                        <td>{{$value->penanggung_jawab}}</td>
                        <td class="text-right">
                          <div class="dropdown">
                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <i class="fas fa-ellipsis-v"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                              <a class="dropdown-item" href="{{ route('kas.edit', $value->kode_kas) }}">Edit</a>
                              {{-- fitur delete sementara nonaktif --}}
                              <form action="{{ route('kas.destroy', $value->kode_kas) }}" method="post">
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
                      @php
                          $no++
                      @endphp
                  @endforeach
                </tbody>
                <tfoot>
                  <tr>
                    <td>
                      {{$kas->appends(Request::all())->links()}}
                    </td>
                  </tr>
                </tfoot>
              </table>
            </div>
         </div>
    </div>
</div>
@endsection