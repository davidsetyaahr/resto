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
            <ul class="nav nav-tabs mb-4">
                <li <?= $_GET['tipe']=='general' ? 'class="active"' : '' ?>><a href="?tipe=general">Laporan General</a></li>
                <li <?= $_GET['tipe']=='menu-favorit' ? 'class="active"' : '' ?>><a href="?tipe=menu-favorit">Menu Terfavorit</a></li>
                <li <?= $_GET['tipe']=='tidak-terjual' ? 'class="active"' : '' ?>><a href="?tipe=tidak-terjual">Menu Tidak Terjual</a></li>
            </ul>

            <form action="" method="get">
                <input type="hidden" value="{{$_GET['tipe']}}" name='tipe'>
            <div class="row align-items-center">
                <div class="col-4">
                    <label for="" class="form-control-label">Dari Tanggal</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><span class="fa fa-calendar"></span></span>
                        </div>
                        <input type="text" class="datepicker form-control" name="dari" value="{{isset($_GET['dari']) ? $_GET['dari'] : ''}}">
                    </div>                
                </div>
                <div class="col-4">
                    <label for="" class="form-control-label">Sampai Tanggal</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><span class="fa fa-calendar"></span></span>
                        </div>
                        <input type="text" class="datepicker form-control" name="sampai" value="{{isset($_GET['sampai']) ? $_GET['sampai'] : ''}}">
                    </div>                
                </div>
                <div class="col mt-4">
                <button type="submit" class="btn btn-primary"><span class="fa fa-save"></span> Simpan</button>
                  <button type="reset" class="btn btn-secondary"><span class="fa fa-times"></span> Reset</button>
                </div>
              </div>
            </form>
            @if(isset($_GET['dari']) && isset($_GET['sampai']))
                @if($_GET['tipe']=='general')
                    @include('penjualan.laporan.laporan-penjualan-general')
                @elseif($_GET['tipe']=='menu-favorit')
                    @include('penjualan.laporan.laporan-penjualan-menu-favorit')
                @elseif($_GET['tipe']=='tidak-terjual')
                    @include('penjualan.laporan.laporan-penjualan-menu-tidak-terjual')
                @endif
            @endif
            </div>
        </div>
    </div>
</div>
@endsection