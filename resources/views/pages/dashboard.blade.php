@extends('common/template')
@section('content')
<!-- Header -->
<div class="header bg-white pb-6">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
      </div>
      <!-- Card stats -->
      <div class="row">
        <div class="col-xl-3 col-md-6">
          <div class="card card-stats">
            <!-- Card body -->
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <h5 class="card-title text-uppercase text-muted mb-2">Jumlah Menu</h5>
                  <span class="h2 font-weight-bold mb-1"> {{\App\Menu::get()->count()}} </span>
                </div>
                <div class="col-auto mt-1">
                  <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                    <i class="ni ni-bullet-list-67"></i>
                  </div>
                </div>
              </div>
              <p class="mt-4 mb-0 text-sm">
                {{-- <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                <span class="text-nowrap">Since last month</span> --}}
              </p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-md-6">
          <div class="card card-stats">
            <!-- Card body -->
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <h5 class="card-title text-uppercase text-muted mb-2">Jumlah Barang</h5>
                  <span class="h2 font-weight-bold mb-1"> {{\App\Barang::get()->count()}} </span>
                </div>
                <div class="col-auto mt-1">
                  <div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow">
                    <i class="ni ni-box-2"></i>
                  </div>
                </div>
              </div>
              <p class="mt-4 mb-0 text-sm">
                {{-- <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                <span class="text-nowrap">Since last month</span> --}}
              </p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-md-6">
          <div class="card card-stats">
            <!-- Card body -->
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <h5 class="card-title text-uppercase text-muted mb-2">Penjualan</h5>
                  @php
                      $month = date('m');
                      $year = date('Y');
                  @endphp
                  <span class="h2 font-weight-bold mb-1">
                    @if (auth()->user()->level == 'Kasir')
                      {{\App\Penjualan::whereMonth('waktu', $month)->whereYear('waktu', $year)->whereNull('deleted_at')->count()}}
                    @else
                      {{\App\Penjualan::whereMonth('waktu', $month)->whereYear('waktu', $year)->count()}}
                    @endif
                  </span>
                </div>
                <div class="col-auto mt-1">
                  <div class="icon icon-shape bg-gradient-green text-white rounded-circle shadow">
                    <i class="ni ni-cart"></i>
                  </div>
                </div>
              </div>
              <p class="mt-4 mb-0 text-sm">
                {{-- <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span> --}}
                {{-- <span class="text-nowrap">*Bulan Ini</span> --}}
              </p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-md-6">
          <div class="card card-stats">
            <!-- Card body -->
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <h5 class="card-title text-uppercase text-muted mb-2">Pembelian Barang</h5>
                  <span class="h2 font-weight-bold mb-1"> {{\App\Pembelian::whereMonth('tanggal', $month)->whereYear('tanggal', $year)->count()}} </span>
                </div>
                <div class="col-auto mt-1">
                  <div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                    <i class="ni ni-bag-17"></i>
                  </div>
                </div>
              </div>
              <p class="mt-4 mb-0 text-sm">
                {{-- <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span> --}}
                {{-- <span class="text-nowrap">*Bulan Ini</span> --}}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-xl-12">
        <div class="card bg-default">
          <div class="card-header bg-transparent">
            <div class="row align-items-center">
              <div class="col">
                <h6 class="text-light text-uppercase ls-1 mb-1">Grafik Penjualan</h6>
                <h5 class="h3 text-white mb-0">Jumlah Penjualan Per Bulan</h5>
              </div>
            </div>
          </div>
          <div class="card-body">
            <!-- Chart -->
            {!! $penjualanChart->container() !!}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection