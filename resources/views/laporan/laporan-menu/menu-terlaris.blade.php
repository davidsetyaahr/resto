@extends('common/template')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0">{{ $pageInfo }}</h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="" method="get">
                        <div class="row align-items-center">
                            <div class="col-4">
                                <label for="" class="form-control-label">Dari Tanggal</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><span class="fa fa-calendar"></span></span>
                                    </div>
                                    <input type="text" class="datepicker form-control" name="dari"
                                        value="{{ Request::get('dari') }}">
                                </div>
                            </div>
                            <div class="col-4">
                                <label for="" class="form-control-label">Sampai Tanggal</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><span class="fa fa-calendar"></span></span>
                                    </div>
                                    <input type="text" class="datepicker form-control" name="sampai"
                                        value="{{ Request::get('sampai') }}">
                                </div>
                            </div>
                            <div class="col-4">
                                <label for="" class="form-control-label">Kategori Menu</label>
                                <select name="id_kategori_menu" id="id_kategori_menu" class="form-control select2">
                                    <option value="">--Semua Kategori--</option>
                                    @foreach ($kategoriMenu as $item)
                                        <option value="{{ $item->id_kategori_menu }}"
                                            {{ Request::get('id_kategori_menu') == $item->id_kategori_menu ? 'selected' : '' }}>
                                            {{ $item->kategori_menu }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col mt-4">
                                <button type="submit" class="btn btn-primary"><span class="fa fa-save"></span>
                                    Simpan</button>
                                <button type="reset" class="btn btn-secondary"><span class="fa fa-times"></span>
                                    Reset</button>
                            </div>
                        </div>
                    </form>
                    @if ($laporan)
                        @php
                            $dari = Request::get('dari');
                            $sampai = Request::get('sampai');
                            $id_kategori_menu = Request::get('id_kategori_menu');
                        @endphp
                        <hr class="my-4">
                        <a href="{{ route('print-menu-terlaris') . '?dari=' . $dari . '&sampai=' . $sampai . '&id_kategori_menu=' . $id_kategori_menu . '&print=true' }}"
                            target="_blank" class="btn btn-sm btn-success float-right py-2 px-3"><span
                                class="fa fa-print"></span> Print</a>
                        <a href="{{ route('print-menu-terlaris') . '?dari=' . $dari . '&sampai=' . $sampai . '&id_kategori_menu=' . $id_kategori_menu . '&print=true&xls=true' }}"
                            target="_blank" class="btn btn-sm btn-success float-right py-2 px-3"><span
                                class="fa fa-file-excel"></span> Export XLS </a>
                        <br>
                        <div class="table-responsive mt-4">
                            <table class="table align-items-center table-flush table-hover table-striped">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Kode Barang</th>
                                        <th>Nama Barang</th>
                                        <th>Jumlah Penjualan</th>
                                    </tr>
                                </thead>
                                <tbody class="list">
                                    @php $qty = 0; @endphp
                                    @foreach ($laporan as $value)
                                        @php
                                            $qty += $value->jml;
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $value->kode_menu }}</td>
                                            <td>{{ $value->nama }}</td>
                                            <td>{{ $value->jml }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-dark text-white">
                                    <tr>
                                        <td colspan='3' class='text-center'><b>TOTAL</b></td>
                                        <td>{{ $qty }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
