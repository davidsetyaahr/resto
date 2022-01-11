@extends('common/template')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center mt-3">
                        <div class="col-8">
                            <h3 class="mb-0">{{ $pageInfo }}</h3>
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

                        <form action="" method="get">
                            <div class="row align-items-center">
                                <div class="col-4">
                                    <label for="" class="form-control-label">Dari Tanggal</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><span class="fa fa-calendar"></span></span>
                                        </div>
                                        <input type="text" class="datepicker form-control" name="dari"
                                            value="{{ isset($_GET['dari']) ? $_GET['dari'] : '' }}" required>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <label for="" class="form-control-label">Sampai Tanggal</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><span class="fa fa-calendar"></span></span>
                                        </div>
                                        <input type="text" class="datepicker form-control" name="sampai"
                                            value="{{ isset($_GET['sampai']) ? $_GET['sampai'] : '' }}" required>
                                    </div>
                                </div>
                                <div class="col mt-4">
                                    <button type="submit" class="btn btn-primary"><span class="fa fa-save"></span>
                                        Simpan</button>
                                    <button type="reset" class="btn btn-secondary"><span class="fa fa-times"></span>
                                        Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <br>
                    <div class="table-responsive" style='min-height:180px'>
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Meja</th>
                                    <th>Kode Penjualan</th>
                                    <th>Waktu Pembayaran</th>
                                    <th>Customer</th>
                                    <th>Jenis Order</th>
                                    <th>Total Harga</th>
                                    <th>Diskon</th>
                                    <th>Status Pembayaran</th>
                                    <th>Deleted At</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                @php
                                    $page = Request::get('page');
                                    $no = !$page || $page == 1 ? 1 : ($page - 1) * 10 + 1;
                                    $total = 0;
                                @endphp
                                @foreach ($penjualan as $value)
                                    @php
                                        $total += $value->deleted_at == null ? $value->total_harga : 0;
                                    @endphp
                                    <tr>
                                        <td>{{ $no }}</td>
                                        <td>{{ $value->nama_meja }}</td>
                                        <td>{{ $value->kode_penjualan }}</td>
                                        <td>{{ date('d-m-Y H:i:s', strtotime($value->waktu_bayar)) }}</td>
                                        <td>{{ $value->nama_customer }}</td>
                                        <td>{{ $value->jenis_order }}</td>
                                        <td>{{ number_format($value->total_harga, 0, ',', '.') }}</td>
                                        <td>{{ number_format($value->total_diskon + $value->total_diskon_tambahan, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            @if ($value->status_bayar == 'Belum Bayar')
                                                <span
                                                    class="badge badge-pill badge-danger">{{ $value->status_bayar }}</span>
                                            @else
                                                <span
                                                    class="badge badge-pill badge-success">{{ $value->status_bayar }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-pill badge-danger">{{ $value->deleted_at }}</span>
                                        </td>
                                        <td class="text-right">
                                            <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                    @if (isset($value->deleted_at))
                                                    <a class="dropdown-item"
                                                    href="{{ route('penjualan.restore', $value->kode_penjualan) }}">Kembalikan</a>
                                                    @else
                                                    <a class="dropdown-item"
                                                    href="{{ route('penjualan.soft-delete', $value->kode_penjualan) }}">Hapus
                                                    Transaksi</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @php $no++ @endphp

                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="6">Total</th>
                                    <th>{{ number_format($total, 0, ',', '.') }}</th>
                                    <th colspan="4"></th>
                                </tr>
                            </tfoot>
                        </table>
                        {{-- <br> --}}
                        {{ $penjualan->appends(Request::all())->links() }}
                    </div>
                </div>

            </div>
        </div>
    @endsection
