@extends('common/template')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                <div class="col-6">
                  <h3 class="mb-0">{{$pageInfo}}</h3>
                </div>
                <div class="col-6 text-right">
                <?php 
                    if(isset($_GET['tipe']) && isset($_GET['dari'])){
                ?>
                @if ($_GET['tipe'] == 'general' || $_GET['tipe'] == 'khusus')
                    <a target="_blank" href="<?= route('laporan-penjualan.print')."?tipe=$_GET[tipe]&dari=$_GET[dari]&sampai=$_GET[sampai]&tipe_pembayaran=$_GET[tipe_pembayaran]" ?>" class="btn btn-info btn-sm"><span class="fa fa-print"></span> Cetak Laporan</a>
                    <a target="_blank" href="<?= route('laporan-penjualan.print')."?tipe=$_GET[tipe]&dari=$_GET[dari]&sampai=$_GET[sampai]&tipe_pembayaran=$_GET[tipe_pembayaran]&xls=true" ?>" class="btn btn-info btn-sm"><span class="fa fa-file-excel"></span> Export XLS</a>
                @else
                    <a target="_blank" href="<?= route('laporan-penjualan.print')."?tipe=$_GET[tipe]&dari=$_GET[dari]&sampai=$_GET[sampai]"?>" class="btn btn-info btn-sm"><span class="fa fa-print"></span> Cetak Laporan</a>
                    <a target="_blank" href="<?= route('laporan-penjualan.print')."?tipe=$_GET[tipe]&dari=$_GET[dari]&sampai=$_GET[sampai]&xls=true"?>" class="btn btn-info btn-sm"><span class="fa fa-file-excel"></span> Export XLS</a>
                @endif
                <?php } ?>
                </div>
              </div>
            </div>
            <div class="card-body">
            <ul class="nav nav-tabs mb-4">
                <li <?= $_GET['tipe']=='general' ? 'class="active"' : '' ?>><a href="?tipe=general">Laporan General</a></li>
                @if (auth()->user()->level == 'Owner' || auth()->user()->level == 'Accounting')
                <li <?= $_GET['tipe']=='khusus' ? 'class="active"' : '' ?>><a href="?tipe=khusus">Laporan Khusus</a></li>
                @endif
                <li <?= $_GET['tipe']=='menu-favorit' ? 'class="active"' : '' ?>><a href="?tipe=menu-favorit">Menu Terfavorit</a></li>
                <li <?= $_GET['tipe']=='tidak-terjual' ? 'class="active"' : '' ?>><a href="?tipe=tidak-terjual">Menu Tidak Terjual</a></li>
            </ul>

            <form action="" method="get">
                <input type="hidden" value="{{$_GET['tipe']}}" name='tipe'>
            <div class="row align-items-center">
                <div class="col-3">
                    <label for="" class="form-control-label">Dari Tanggal</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><span class="fa fa-calendar"></span></span>
                        </div>
                        <input type="text" class="datepicker form-control" name="dari" value="{{isset($_GET['dari']) ? $_GET['dari'] : ''}}" required>
                    </div>                
                </div>
                <div class="col-3">
                    <label for="" class="form-control-label">Sampai Tanggal</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><span class="fa fa-calendar"></span></span>
                        </div>
                        <input type="text" class="datepicker form-control" name="sampai" value="{{isset($_GET['sampai']) ? $_GET['sampai'] : ''}}" required>
                    </div>                
                </div>
                @if ($_GET['tipe'] == 'general' || $_GET['tipe'] == 'khusus')
                <div class="col-3">
                    <label for="" class="form-control-label">Tipe Pembayaran</label>
                    <select name="tipe_pembayaran" id="" class="select2 form-control">
                        <option value="">Semua Tipe</option>
                        <option value="Tunai" {{Request::get('tipe_pembayaran') == 'Tunai' ? 'selected' : ''}} >Tunai</option>
                        <option value="BCA" {{Request::get('tipe_pembayaran') == 'BCA' ? 'selected' : ''}} >BCA</option>
                        <option value="BRI" {{Request::get('tipe_pembayaran') == 'BRI' ? 'selected' : ''}} >BRI</option>
                        <option value="Bank Lain" {{Request::get('tipe_pembayaran') == 'Bank Lain' ? 'selected' : ''}} >Bank Lain</option>
                    </select>
                </div>
                @endif
                <div class="col mt-4">
                <button type="submit" class="btn btn-primary"><span class="fa fa-save"></span> Simpan</button>
                  <button type="reset" class="btn btn-secondary"><span class="fa fa-times"></span> Reset</button>
                </div>
              </div>
            </form>
            @if(isset($_GET['dari']) && isset($_GET['sampai']))
                @if($_GET['tipe']=='general')
                    @include('penjualan.laporan.laporan-penjualan-general')
                @elseif($_GET['tipe']=='khusus')
                    @include('penjualan.laporan.laporan-penjualan-khusus')
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