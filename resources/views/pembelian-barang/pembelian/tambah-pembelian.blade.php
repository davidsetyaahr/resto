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
            <form action="{{ route('pembelian.store') }}" method="post">
              @csrf
              <div class="card-body">
              <h6 class="heading-small text-muted mb-4">Informasi Umum</h6>
                  <div class="row pl-lg-4">
                    <div class="col-md-4">
                        <label for="" class="form-control-label">Kode Pembelian</label>
                        <input type="text" class="form-control" id="kode" name='kode_pembelian' value="{{old('kode_pembelian')}}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="" class="form-control-label">Tanggal Pembelian</label>
                        <div class="input-group mb-3">
                          <div class="input-group-prepend">
                              <span class="input-group-text"><span class="fa fa-calendar"></span></span>
                          </div>
                        <input type="text" name="tanggal" value="{{old('tanggal')}}" class="form-control datepicker getKode @error('tanggal') is-invalid @enderror" data-url="{{url('pembelian-barang/pembelian/getKode')}}">
                        @error('tanggal')
                          <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                        </div>
                    </div>
                    <div class="col-md-4 @error('kode_supplier') is-invalid @enderror">
                        <label for="" class="form-control-label">Kode Supplier</label>
                        <select name="kode_supplier" class="form-control select2" id="">
                          <option value=''>---Select---</option>
                          @foreach($supplier as $value)
                            <option value="{{$value->kode_supplier}}" {{old('kode_supplier') == $value->kode_supplier ? 'selected' : ''}}>{{$value->kode_supplier.' ~ '.$value->nama_supplier}}</option>
                          @endforeach
                        </select>
                        @error('kode_supplier')
                          <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                    </div>
                  </div>
                  <hr class="my-4">
                  <h6 class="heading-small text-muted mb-4">Detail Pembelian</h6>
                  <div class="pl-lg-4" id='urlAddDetail' data-url="{{url('pembelian-barang/pembelian/addDetailPembelian')}}">
                  @if(!is_null(old('kode_barang')))
                    @php $no = 0 @endphp
                    @foreach(old('kode_barang') as $n => $value)
                      @php $no++ @endphp
                      @include('pembelian-barang.pembelian.tambah-detail-pembelian',['hapus' => false, 'no' => $no, 'barang' => $barang])
                    @endforeach
                  @else
                    @include('pembelian-barang.pembelian.tambah-detail-pembelian',['hapus' => false, 'no' => 1, 'barang' => $barang])
                  @endif
                  </div>
                  <h2 class='text-right mt-5 pr-5'>Total : <span id='total' class="text-orange">0</span></h2>
                  <div class="mt-4">
                  <button type="submit" class="btn btn-primary"><span class="fa fa-save"></span> Simpan</button>
                  <button type="reset" class="btn btn-secondary"><span class="fa fa-times"></span> Reset</button>
                  </div>
              </div>
            </form>
        </div>
    </div>
</div>
@endsection