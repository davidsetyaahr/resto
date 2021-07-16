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
            <form action="{{ route('pembayaran.save', $penjualan->kode_penjualan) }}" method="post" id="<?php echo $_GET['tab']=='split-bill' ? 'split-bill-form' : '' ?>">
              @csrf
              @method('put')
              <input type="hidden" id="kode" name='kode_penjualan' value="{{$penjualan->kode_penjualan}}" readonly>
              <div class="card-body">
              <h6 class="heading-small text-muted mb-4">Informasi Umum</h6>
                  <div class="row">
                    <div class="col-md-3 mb-2">
                        <label for="" class="form-control-label">Nama Customer</label>
                        <div class="form-line-check">
                          <span class='fa fa-check-circle'></span>
                          <input type="text" class="form-control form-line @error('nama_customer') is-invalid @enderror" name='nama_customer' value="{{$penjualan->nama_customer}}" readonly>
                        </div>
                    </div>

                    <div class="col-md-3 mb-2">
                        <label for="" class="form-control-label">No Handphone</label>
                        <div class="form-line-check">
                          <span class='fa fa-check-circle'></span>
                          <input type="number" class="form-control form-line @error('no_hp') is-invalid @enderror" name='no_hp' value="{{$penjualan->no_hp}}" readonly>
                      </div>
                    </div>
                    
                    <div class="col-md-3 mb-2">
                        <label for="" class="form-control-label">No Meja</label>
                        <div class="form-line-check">
                          <span class='fa fa-check-circle'></span>
                          <select class='form-control form-line' name='id_meja'>
                            <option value="{{$penjualan->id_meja}}">{{$penjualan->nama_meja}}</option>
                          </select>
                        </div>
                    </div>

                    <div class="col-md-3 mb-2">
                        <label for="" class="form-control-label">Jenis Order</label>
                        <div class="form-line-check">
                          <span class='fa fa-check-circle'></span>
                          <input type="text" class="form-control form-line @error('jenis_order') is-invalid @enderror" name='jenis_order' value="{{$penjualan->jenis_order}}" readonly>
                      </div>
                    </div>
                  </div>
                  <h6 class="heading-small text-muted my-4">Detail Penjualan</h6>
                  <table class="table align-items-center table-flush table-striped table-hover">
                    <thead class="thead-light">
                      <tr>
                        <th>#</th>
                        <th>Menu</th>
                        <th>Kode Menu</th>
                        <th>Nama</th>
                        <th>Quantity</th>
                        <th>Harga</th>
                        <th>Diskon</th>
                        <th>Subtotal</th>
                      </tr>
                    </thead>
                    <tbody class="list">
                    @php 
                      $qty = 0;
                      $harga = 0;
                      $diskon = 0;
                      $subtotal = 0;
                    @endphp
                    @foreach ($detail as $value)
                      @php
                          $menu = \App\Menu::select('nama','foto')->where('kode_menu', $value->kode_menu)->get()[0];
                          $qty = $qty + $value->qty;
                          $diskon = $diskon + $value->diskon;
                          $subtotal = $subtotal + $value->sub_total;
                      @endphp
                      <tr>
                        <td>{{$loop->iteration}}</td>
                        <td><img src="{{asset('assets/img/menu/'.$menu->foto)}}" width='150px' class='img-thumbnail' alt=""></td>
                        <td>{{$value->kode_menu}}</td>
                        <td>{{$menu->nama}}</td>
                        <td>{{$value->qty}}</td>
                        <td>{{number_format($value->harga_jual,0,',','.')}}</td>
                        <td>{{number_format($value->diskon,0,',','.')}}</td>
                        <td>{{number_format($value->sub_total,0,',','.')}}</td>
                      </tr>
                    @endforeach
                      <tr>
                        <td colspan='6' class='text-center'></td>
                        <td>{{number_format($penjualan->total_diskon,0,',','.')}}</td>
                        <td>{{number_format($penjualan->total_harga,0,',','.')}}</td>
                      </tr>
                      <tr>
                        <td colspan='7' class='text-center'></td>
                        <td>{{number_format($penjualan->total_harga - $penjualan->total_diskon,0,',','.')}}</td>
                      </tr>
                      <tr>
                        <td colspan='4' class='text-center'>PPN(10%)</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>{{number_format($penjualan->total_ppn,0,',','.')}}</td>
                      </tr>
                      @if ($penjualan->jenis_order == 'Room Order')
                        <tr>
                          <td colspan='4' class='text-center'>Room Charge(10%)</td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td>{{number_format($penjualan->room_charge,0,',','.')}}</td>
                        </tr>
                      @endif
                    </tbody>
                    <tfoot class='bg-dark text-white'>
                      <tr>
                        <td colspan='4' class='text-center'>TOTAL</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>{{number_format(($penjualan->total_harga - $penjualan->total_diskon) + $penjualan->total_ppn + $penjualan->room_charge,0,',','.')}}</td>
                      </tr>
                    </tfoot>
                  </table>
                  <hr>
                  <div class="row mb-2">
                    <div class="col-md-12">
                      <ul class="nav nav-tabs tabs-bill-option mb-3">
                        <li class="nav-item"><a class="nav-link <?php echo $_GET['tab']=='normal' ? 'active' : '' ?>" href="?tab=normal">Normal Payment</a></li>
                        <li class="nav-item"><a class="nav-link <?php echo $_GET['tab']=='split-bill' ? 'active' : '' ?>" href="?tab=split-bill">Split Bill</a></li>
                      </ul>
                    </div>
                  </div>
                  <input type="text" name="temp_ppn" id="temp_ppn" value="{{$penjualan->total_ppn}}">
                  <input type="text" name="new_ppn" id="new_ppn" value="{{$penjualan->total_ppn}}">
                  <?php 
                    if($_GET['tab']=='normal'){
                  ?>
                  <input type="hidden" name='tipeBill' value='normal'>
                    <div class="row" id='normal-payment'>
                      <input type="hidden" name="total" id="total" class="form-control" value="{{($penjualan->total_harga - $penjualan->total_diskon) + $penjualan->total_ppn + $penjualan->room_charge }}" readonly>
                      <div class="col-4 mb-2">
                        <label for=""><strong>Diskon(%)</strong></label>
                        <input type="number" name="diskon" class="form-control diskon_tambahan" data-tipe='persen' value="{{old('diskon', 0)}}">
                      </div>
                      <div class="col-4 mb-2">
                        <label for=""><strong>Potongan</strong></label>
                        <input type="number" name="diskon_tambahan" class="form-control diskon_tambahan" value="{{old('diskon_tambahan', 0)}}" data-tipe='rp'>
                      </div>
                      <div class="col-4 mb-2">
                        <label for=""><strong>Metode Pembayaran</strong></label>
                        <select name="jenis_bayar" class="form-control @error('bayar') is-invalid @enderror" id="jenis_bayar">
                          <option value="Tunai" {{old('jenis_bayar') == 'Tunai' ? 'selected' : ''}}>Tunai</option>
                          <option value="Debit BCA" {{old('jenis_bayar') == 'Debit BCA' ? 'selected' : ''}}>Debit BCA</option>
                          <option value="Debit BRI" {{old('jenis_bayar') == 'Debit BRI' ? 'selected' : ''}}>Debit BRI</option>
                          <option value="Debit Bank Lain" {{old('jenis_bayar') == 'Debit Bank Lain' ? 'selected' : ''}}>Debit Bank Lain</option>
                          <option value="Kredit BCA" {{old('jenis_bayar') == 'Kredit BCA' ? 'selected' : ''}}>Kredit BCA</option>
                          <option value="Kredit BRI" {{old('jenis_bayar') == 'Kredit BRI' ? 'selected' : ''}}>Kredit BRI</option>
                          <option value="Kredit Bank Lain" {{old('jenis_bayar') == 'Kredit Bank Lain' ? 'selected' : ''}}>Kredit Bank Lain</option>
                        </select>
                        @error('jenis_bayar')
                          <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                      </div>
                      <div class="col-4 mb-2">
                        <label for=""><strong>Terbayar</strong></label>
                        <input type="number" name="bayar" id="bayar" class="form-control @error('bayar') is-invalid @enderror" value="{{old('bayar')}}">
                        @error('bayar')
                          <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                      </div>
                      <div class="col-4 mb-2">
                        <label for=""><strong>Kembalian</strong></label>
                        <input type="number" name="kembalian" id="kembalian" class="form-control" value="" readonly>
                      </div>
                      
                      <div class="col-4 mb-2">
                        <div id="debit_only">
                          <label for=""><strong>Nomor Kartu</strong></label>
                          <input type="number" name="no_kartu" id="no_kartu" class="form-control" value="{{old('no_kartu')}}">
                          {{-- @error('jenis_bayar')
                            <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                            </span>
                          @enderror --}}
                        </div>
                      </div>
                      <div class="col-4 mb-2">
                        <div id="">
                          <label for=""><strong>Charge</strong></label>
                          <input type="number" name="charge" id="charge" class="form-control" value="{{old('charge', 0)}}">
                          {{-- @error('jenis_bayar')
                            <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                            </span>
                          @enderror --}}
                        </div>
                      </div>
                      <div class="col-md-4 mt-4">
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" id="isTravel" name="isTravel" value="True" {{old('isTravel') == 'True' ? 'checked' : ''}}>
                          <label class="custom-control-label" for="isTravel">Travel</label>
                          <br>
                          <h5 class="text-default">*Khusus Customer Travel</h5>
                        </div>
                      </div>
                      <div class="col-md-4 mt-4">
                        <h1 class='text-dark'>Grand Total : Rp. <span class="text-orange" id='idrGrandTotal'>{{number_format($penjualan->total_harga - $penjualan->total_diskon + $penjualan->total_ppn + $penjualan->room_charge,0,',','.')}}</span></h1>
                        <input type="hidden" name="grand_total" id="grand_total" class="form-control form-line text-lg text-orange font-weight-bold" value="{{($penjualan->total_harga - $penjualan->total_diskon) + $penjualan->total_ppn + $penjualan->room_charge }}">
                      </div>
                      <div class="col-md-3 mt-3">
                          <button type="submit" class="btn btn-primary"><span class="fa fa-save"></span> Simpan</button>
                          <button type="reset" class="btn btn-secondary"><span class="fa fa-times"></span> Reset</button>
                      </div>
                    </div>
                  <?php } else{?>
                  <input type="hidden" name='tipeBill' value='split-bill'>
                    <div class="row">
                            <div class="col-md-2 text-center list-user-bill" data-count='1'>
                              <div class="user-bill active py-4" data-key='1'>
                                <img src="{{asset('assets/img/default.png')}}" width="60%">
                                <h4 class='mt-3' contenteditable='true'>{{$penjualan->nama_customer}}</h4>
                              </div>
                              <div class="new-user-bill">

                              </div>
                              <div class="mt-4">
                                <a href="" id='add-new-user-bill'><span class="fa fa-2x fa-plus-circle"></span></a>
                              </div>
                            </div>
                            <div class="col-md-10">
                            <label for="">Pilih Menu</label>
                            <div class="form-control" id="user-bill-menu" data-url="{{route('menu-bill')}}" data-kodepenjualan="{{$penjualan->kode_penjualan}}">
                            @foreach ($detail as $key => $value)
                                @php
                                    $menu = \App\Menu::select('nama','foto')->where('kode_menu', $value->kode_menu)->get()[0];
                                @endphp
                                <div class="custom-control custom-checkbox">
                                  <input type="checkbox" class="custom-control-input menuCheck" id="customCheck{{$key}}" data-qty="{{$value->qty}}" value="{{$value->kode_menu}}">
                                  <label class="custom-control-label" for="customCheck{{$key}}">{{$menu->nama}} <span class="badge badge-primary badge-pill" for='{{$value->kode_menu}}'>{{$value->qty}}</span> </label>
                                </div>                              
                            @endforeach
                            </div>
                            <div class="table-responsive">
                              <table class="table table-striped table-hover" id="table-menu-bill">
                                <thead>
                                  <tr>
                                  <th>Kode Menu</th>
                                  <th>Nama</th>
                                  <th width="20%">Quantity</th>
                                  <th>Harga</th>
                                  <th>Diskon</th>
                                  <th>Subtotal</th>
                                  </tr>
                                </thead>
                                <tbody data-key='1'>
                                </tbody>
                                <tfoot>
                                  <tr>
                                    <td colspan='4'></td>
                                    <td id='tfootDiskon'></td>
                                    <td id='tfootSubtotal'></td>
                                  </tr>
                                  <tr>
                                    <td colspan='5'></td>
                                    <td id='tfootDiskonSubtotal'></td>
                                  </tr>
                                  <tr>
                                    <td colspan='5'>PPN(10%)</td>
                                    <td id='tfootPpn'></td>
                                  </tr>
                                  <tr>
                                    <td colspan='5'>TOTAL</td>
                                    <td id='tfootTotal'></td>
                                  </tr>
                                </tfoot>
                              </table>
                            </div>
                            <hr>
                            </div>
                        </div>
                        <div class="row split-bill">
                            <input type="hidden" name="" id="total" class="form-control" value="0" readonly>
                            <div class="col-4 mb-2">
                              <label for=""><strong>Diskon(%)</strong></label>
                              <input type="number" name="diskon" class="form-control diskon_tambahan" data-tipe='persen' value="{{old('diskon', 0)}}">
                            </div>
                            <div class="col-4 mb-2">
                              <label for=""><strong>Potongan</strong></label>
                              <input type="number" name="diskon_tambahan" class="form-control diskon_tambahan" value="{{old('diskon_tambahan', 0)}}" data-tipe='rp'>
                            </div>
                            <div class="col-4 mb-2">
                              <label for=""><strong>Metode Pembayaran</strong></label>
                              <select name="jenis_bayar" class="form-control @error('bayar') is-invalid @enderror" id="jenis_bayar">
                                <option value="Tunai" {{old('jenis_bayar') == 'Tunai' ? 'selected' : ''}}>Tunai</option>
                                <option value="Debit BCA" {{old('jenis_bayar') == 'Debit BCA' ? 'selected' : ''}}>Debit BCA</option>
                                <option value="Debit BRI" {{old('jenis_bayar') == 'Debit BRI' ? 'selected' : ''}}>Debit BRI</option>
                                <option value="Debit Bank Lain" {{old('jenis_bayar') == 'Debit Bank Lain' ? 'selected' : ''}}>Debit Bank Lain</option>
                                <option value="Kredit BCA" {{old('jenis_bayar') == 'Kredit BCA' ? 'selected' : ''}}>Kredit BCA</option>
                                <option value="Kredit BRI" {{old('jenis_bayar') == 'Kredit BRI' ? 'selected' : ''}}>Kredit BRI</option>
                                <option value="Kredit Bank Lain" {{old('jenis_bayar') == 'Kredit Bank Lain' ? 'selected' : ''}}>Kredit Bank Lain</option>
                              </select>
                              @error('jenis_bayar')
                                <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                                </span>
                              @enderror
                            </div>
                            <div class="col-4 mb-2">
                              <label for=""><strong>Terbayar</strong></label>
                              <input type="number" name="bayar" id="bayar" class="form-control @error('bayar') is-invalid @enderror" value="{{old('bayar')}}">
                              @error('bayar')
                                <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                                </span>
                              @enderror
                            </div>
                            <div class="col-4 mb-2">
                              <label for=""><strong>Kembalian</strong></label>
                              <input type="number" name="kembalian" id="kembalian" class="form-control" value="" readonly>
                            </div>
                            
                            <div class="col-4 mb-2">
                              <div id="debit_only">
                                <label for=""><strong>Nomor Kartu</strong></label>
                                <input type="number" name="no_kartu" id="no_kartu" class="form-control" value="{{old('no_kartu')}}">
                                {{-- @error('jenis_bayar')
                                  <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                  </span>
                                @enderror --}}
                              </div>
                            </div>
                            <div class="col-4 mb-2">
                              <div id="">
                                <label for=""><strong>Charge</strong></label>
                                <input type="number" name="charge" id="charge" class="form-control" value="{{old('charge', 0)}}">
                                {{-- @error('jenis_bayar')
                                  <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                  </span>
                                @enderror --}}
                              </div>
                            </div>
                            <div class="col-md-4 mt-4">
                              <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="isTravel" name="isTravel" value="True" {{old('isTravel') == 'True' ? 'checked' : ''}}>
                                <label class="custom-control-label" for="isTravel">Travel</label>
                                <br>
                                <h5 class="text-default">*Khusus Customer Travel</h5>
                              </div>
                            </div>
                            <div class="col-md-4 mt-4">
                              <h1 class='text-dark'>Grand Total : Rp. <span class="text-orange" id='idrGrandTotal'>0</span></h1>
                              <input type="hidden" name="grand_total" id="grand_total" class="form-control form-line text-lg text-orange font-weight-bold" value="0">
                            </div>
                          </div>
                          <hr>
                          <div class="row">
                            <div class="col-md-3 mt-3">
                                <button type="submit" class="btn btn-primary"><span class="fa fa-save"></span> Simpan</button>
                                <button type="reset" class="btn btn-secondary"><span class="fa fa-times"></span> Reset</button>
                            </div>
                          </div>
                      <div class="data-bill">
                                
                      </div>
                      <div class="data-pembayaran">
                        <div class='guest-pembayaran' data-key='1'>
                          <input type='hidden' class='pembDiskon' name='diskon[1]' value='0'>
                          <input type='hidden' class='pembDiskonTambahan' name='diskon_tambahan[1]' value='0'>
                          <input type='hidden' class='pembJenisBayar' name='jenis_bayar[1]' value='Tunai'>
                          <input type='hidden' class='pembBayar' name='bayar[1]' value='0'>
                          <input type='hidden' class='pembKembalian' name='kembalian[1]' value='0'>
                          <input type='hidden' class='pembNoKartu' name='no_kartu[1]' value=''>
                          <input type='hidden' class='pembCharge' name='charge[1]' value='0'>
                          <input type='hidden' class='pembTotal' name='total[1]' value='0'>
                      </div>
                          
                      </div>

                  <?php } ?>
                  <div class="mt-4">
                  </div>
              </div>
            </form>
        </div>
    </div>
</div>
@endsection