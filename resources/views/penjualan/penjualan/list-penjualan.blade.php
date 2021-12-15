@extends('common/template')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                <div class="col">
                  <?php 
                    foreach ($meja as $key => $value) {
                      $cek = \DB::table('penjualan as p')->select('p.id_meja')->join('meja as m','p.id_meja','m.id_meja')->where('p.id_meja',$value->id_meja)->where('m.nama_meja','!=','Hotel Room')->where('status_bayar','Belum Bayar')->count();
                      if($cek==0){
                        $url = url('penjualan/penjualan/create?id_meja='.$value->id_meja);
                        $class = 'success';
                      }
                      else{
                        $getKodePenjualan = \DB::table('penjualan')->select('kode_penjualan')->where('id_meja', '=', $value->id_meja)->where('status_bayar','Belum Bayar')->get()[0];
                        $url = url('penjualan/'.$getKodePenjualan->kode_penjualan.'/edit');
                        $class = 'danger';
                      }
                  ?>
                    <a href="{{$url}}" class='my-1 btn btn-{{$class}}'>{{$value->nama_meja}}</a>
                  <?php
                    }
                  ?>
                </div>
              </div> 
              <div class="row align-items-center mt-3">
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
            <div class="table-responsive" style='min-height:180px'>
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th>#</th>
                    <th>Meja</th>
                    <th>Kode Penjualan</th>
                    <th>Waktu</th>
                    <th>Customer</th>
                    <th>Jenis Order</th>
                    <th>Total Harga</th>
                    <th>Diskon</th>
                    <th>Kasir</th>
                    <th>Status</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody class="list">
                @php
                  $page = Request::get('page');
                  $no = !$page || $page == 1 ? 1 : ($page - 1) * 10 + 1;
                @endphp
                @foreach ($penjualan as $value)
                      <tr>
                        <td>{{$no}}</td>
                        <td>{{$value->nama_meja}}</td>
                        <td>{{$value->kode_penjualan}}</td>
                        <td>{{date('d-m-Y H:i:s', strtotime($value->waktu))}}</td>
                        <td>{{$value->nama_customer}}</td>
                        <td>{{$value->jenis_order}}</td>
                        <td>{{number_format($value->total_harga,0,',','.')}}</td>
                        <td>{{number_format($value->total_diskon + $value->total_diskon_tambahan,0,',','.')}}</td>
                        <td>{{$value->kasir}}</td>
                        <td>
                          @if ($value->status_bayar == 'Belum Bayar')
                          <span class="badge badge-pill badge-danger">{{$value->status_bayar}}</span>
                          @else
                          <span class="badge badge-pill badge-success">{{$value->status_bayar}}</span>
                          @endif
                        </td>
                        <td class="text-right">
                          <div class="dropdown">
                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <i class="fas fa-ellipsis-v"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                            <?php 
                              if(\Auth::user()->level!='Waiters'){
                            ?>
                              <a class="dropdown-item" href="{{ route('cetak-bill', $value->kode_penjualan) }}">Cetak Bill</a>
                              <a class="dropdown-item" href="{{ route('cetak-bill', $value->kode_penjualan) }}?tableCheck=check">Table Check</a>
                              <a class="dropdown-item" href="{{ route('penjualan.bayar', $value->kode_penjualan) }}?tab=normal">Pembayaran</a>
                              <a class="dropdown-item" href="{{ route('piutang.change-to-piutang', $value->kode_penjualan) }}">Piutang</a>
                              <?php } ?>
                              <a class="dropdown-item" href="{{ url('penjualan/'.$value->kode_penjualan.'/edit') }}">Edit</a>
                              <form action="{{ route('penjualan.destroy', $value->kode_penjualan) }}" method="post">
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
                      @php $no++ @endphp

                  @endforeach
                </tbody>
              </table>
              {{$penjualan->appends(Request::all())->links()}}
            </div>
         </div>
         
    </div>
</div>
@endsection