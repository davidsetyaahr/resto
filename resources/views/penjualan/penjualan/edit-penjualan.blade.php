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
              <form data-url="{{url('penjualan/penjualan/filter')}}" method="" id="formFilterMenu">
              <div class="row mb-4">
                  <div class="col-md-12">
                    <div class="input-group mb-2">
                      <div class="input-group-prepend mr-0">
                        <select name="" id="idKategori" class='form-control px-4 select2'>
                          <option value="">Semua Kategori</option>
                          @foreach($kategori as $data)
                            <option value="{{$data->id_kategori_menu}}">{{$data->kategori_menu}}</option>
                          @endforeach
                        </select>
                      </div>
                      <input type="text" class="form-control" id="key" placeholder="Cari Menu Disini..">
                        <div class="input-group-append">
                          <button class="btn btn-primary">Filter</button>
                        </div>
                    </div>                    
                  </div>                
                </div>
              </form>
              <div class="row row-menu" id="tag_container" data-urlDiskon="{{route('get-diskon')}}">
                @include('penjualan.penjualan.loop-menu')
              </div>
            </div>
        </div>
    </div>
</div>
<div class="box-penjualan {{!is_null(old('kode_menu')) ? 'show' : ''}}">
<div class="toggle-cart"><span class="fa fa-shopping-cart"></span></div>

  <div class="relative">

  <form action="{{ route('penjualan.update', $penjualan->kode_penjualan) }}" method="post" class='formEdit'>
    @csrf
    @method('put')

    <div class="container py-4">
      <div class="col-md-12">
      @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <br>
      @endif
        <input type="hidden" name="kode_penjualan" id='kode' value='{{$penjualan->kode_penjualan}}'>
        <h2>{{$penjualan->kode_penjualan}}</h2>
        <hr class="mt-1">
        <div class="row">
        <div class="col-md-6 form-line">
            <select name="id_meja" id="" class="form-control select2 @error('id_meja') is-invalid @enderror">
              <option value="">Nomor Meja</option>
              <option value="{{$mejaSelected->id_meja}}" {{old('id_meja', $penjualan->id_meja) == $mejaSelected->id_meja ? 'selected' : ''}}>{{$mejaSelected->nama_meja}}</option>
              @foreach($meja as $meja)
                <option value="{{$meja->id_meja}}" {{old('id_meja', $penjualan->id_meja) == $meja->id_meja ? 'selected' : ''}}>{{$meja->nama_meja}}</option>
              @endforeach
            </select>
            @error('id_meja')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>

          <div class="col-md-6">
            <input type="text" class="form-control form-line @error('nama_customer') is-invalid @enderror" name='nama_customer' value="{{old('nama_customer',$penjualan->nama_customer)}}" placeholder='Nama Customer' autocomplete='off'>
            @error('nama_customer')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="col-md-6 mt-3">
            <input type="number" class="form-control form-line @error('no_hp') is-invalid @enderror" name='no_hp' value="{{old('no_hp',$penjualan->no_hp)}}" placeholder='Nomor Hp' autocomplete='off'>
              @error('no_hp')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
              @enderror
            </div>
            <div class="col-md-6 mt-3">
              <select name="jenis_order" class="form-control form-line @error('jenis_order') is-invalid @enderror " id="jenis_order">
              <option value="">Jenis Order</option>
              <option value="Dine In" {{old('jenis_order', $penjualan->jenis_order) == 'Dine In' ? 'selected' : ''}} >Dine In</option>
              <option value="Take Away" {{old('jenis_order',$penjualan->jenis_order) == 'Take Away' ? 'selected' : ''}} >Take Away</option>
              <option value="Room Order" {{old('jenis_order',$penjualan->jenis_order) == 'Room Order' ? 'selected' : ''}} >Room Order</option>
            </select>
            @error('jenis_order')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
          <div class="col-md-6 mt-3">
            <input type="text" class="form-control form-line @error('nomor_kamar') is-invalid @enderror" name='nomor_kamar' value="{{old('nomor_kamar', $penjualan->nomor_kamar)}}" placeholder='Nomor Kamar' autocomplete='off' id="nomor_kamar">
            @error('nomor_kamar')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
        </div>
        <h2 class='mt-4'>Keranjang Menu</h2>
        <div class="table-responsive">
        <table class="table align-items-center table-flush keranjang table-striped table-hover">
          <thead class="thead-light">
            <tr>
              <th width='10%'>#</th>
              <th width='25%' class='text-center'>Menu</th>
              <th width='25%' class='text-center'>Qty</th>
              <th width='15%' class='text-center'>Diskon</th>
              <th width='15%' class='text-center'>Subtotal</th>
              <th width='10%'></th>
            </tr>
          </thead>
          <?php 
            $qty = 0;
            $diskon = 0;
            $subtotal = 0;
            $total = 0;
            $no = 0;
            if(is_null(old('kode_menu'))){
              $detailMenu = collect($detail)->map(function($x){ return (array) $x; })->toArray();
            }
            else{
              $detailMenu = [];
              foreach (old('kode_menu') as $i => $value) {
                $detailMenu[] = array(
                  'id_detail' => old('id_detail.'.$i),
                  'kode_menu' => old('kode_menu.'.$i),
                  'nama_menu' => old('nama_menu.'.$i),
                  'qty' => old('qty.'.$i),
                  'harga' => old('harga.'.$i),
                  'diskon' => old('diskon.'.$i),
                  'subtotal' => old('subtotal.'.$i),
                  'keterangan' => old('keterangan.'.$i)
                );
              }
            }
          ?>
            <tbody class='tbodyLoop' data-no="{{count($detailMenu)}}">
              @foreach($detailMenu as $i => $value)
              @php 
                $no++; 
                $qty = $qty + $value['qty'];
                $diskon = $diskon + $value['diskon'];
                $subtotal = $subtotal + $value['subtotal'];
              @endphp
                <tr data-tr='{{$no}}' class='tr'>
                    <td colspan='6' class='p-0'>
                      <table width='100%'>
                        <tr>
                          <td width='10%' class='no'>{{$no}}</td>
                          <td width='25%'>
                          <input type='hidden' name='id_detail[]' class='idDetail' value="{{$value['id_detail_penjualan']}}"> 
                          <input type='hidden' name='kode_menu[]' class='inputKodeMenu' value="{{$value['kode_menu']}}"> 
                          <input type='hidden' name='nama_menu[]' class='inputNamaMenu' value="{{$value['nama_menu']}}">
                          {{$value['nama_menu']}}</td>
                          <td width='25%' class='px-0'>
                              <div class="change-qty">
                                  <input type="hidden" name="harga[]" value="{{$value['harga']}}" class="inputHarga">
                                  <button class='btnqty' data-tipe='min'>-</button>
                                  <input type='text' name='qty[]' value="{{$value['qty']}}" class='form-control text-center inputQty' readonly>
                                  <button class='btnqty' data-tipe='plus'>+</button>
                              </div>
                          </td>
                          <td width='15%' class='tdDiskon'><input type='hidden' class='inputDiskon' name='diskon[]' value="{{$value['diskon']}}"> {{number_format($value['diskon'],0,',','.')}}</td>
                          <td width='15%' class='tdSubtotal'><input type='hidden' name='subtotal[]' value="{{$value['subtotal']}}" class='inputSubtotal'> <span> {{number_format($value['subtotal'],0,',','.')}}</span></td>
                          <td width='10%'><a href='' title="Hapus" class='deleteCart'><span class='fa fa-trash fa-lg'></span></a></td>
                        </tr>
                        <tr>
                            <td colspan='6' class='p-0'>
                                <input type="text" class="form-control form-line" name="keterangan[]" value="{{$value['keterangan']}}" placeholder="Keterangan">
                            </td>
                        </tr>
                      </table>
                    </td>
                </tr>
              @endforeach
            </tbody>

          <?php
            $total = $subtotal - $diskon;
          ?>
          <tfoot class='bg-dark text-white'>
            <td colspan='2' class='text-center'></td>
            <td id='tfootQty' class='text-center'>{{$qty}}</td>
            <td id='tfootDiskon'>{{number_format($diskon,0,',','.')}}</td>
            <td colspan='2' id='tfootSubtotal'>{{number_format($subtotal,0,',','.')}}</td>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
  <div class="sticky-bottom">
    <div class="row">
      <div class="col-md-6 pl-5">
        <h2 class='mt-2'>Total : <span id='total' class="text-primary"> {{number_format($total,0,',','.')}}</span></h2>
      </div>
      <div class="col-md-6 pr-0">
        <button class='btn py-3 btn-primary btn-block'>CHECKOUT</button>
      </div>
    </div>
  </div>
</form>
</div>
</div>

<script>
    var body = document.querySelector('body')
    body.classList.add('fullpage')
    body.classList.add('penjualan')
    var span = document.querySelector('.fullpage-version span')
    <?php 
      if(\Auth::user()->level!='Waiters'){
        ?>    
    span.classList.add('fa-chevron-right')
    <?php } else{ ?>
        body.classList.add('waiters')
    <?php } ?>
</script>
@endsection