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
            <form action="{{ route('diskon.store') }}" method="post">
              @csrf
              <div class="card-body">
              <h6 class="heading-small text-muted mb-4">Informasi Umum</h6>
                  <div class="row pl-lg-4">
                    <div class="col-md-4">
                        <label for="" class="form-control-label">Nama Diskon</label>
                        <input type="text" class="form-control" id="nama" name='nama_diskon' value="{{old('nama_diskon')}}" placeholder="ex : Bharata Merdeka" @error('nama_diskon') is-invalid @enderror required>
                        @error('nama_diskon')
                          <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                    </div>
                    <div class="col-md-4 @error('jenis_diskon') is-invalid @enderror">
                        <label for="" class="form-control-label">Jenis Diskon</label>
                        <select name="jenis_diskon" class="form-control select2" id="jenis_diskon">
                          <option value=''>---Select---</option>
                          <option value="Persen" {{old('jenis_diskon') == 'Persen' ? 'selected' : ''}}>Persen</option>
                          <option value="Rupiah" {{old('jenis_diskon') == 'Rupiah' ? 'selected' : ''}}>Rupiah</option>
                        </select>
                        @error('jenis_diskon')
                          <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="" class="form-control-label">Nominal Diskon</label>
                        <input type="number" class="form-control" name='diskon' value="{{old('diskon')}}" placeholder="ex : 2000" @error('diskon') is-invalid @enderror required id="diskon">
                        @error('diskon')
                          <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                    </div>
                  </div>
                  <br>
                  <div class="row pl-lg-4">
                    <div class="col-md-4">
                        <label for="" class="form-control-label">Tanggal Mulai</label>
                        <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><span class="fa fa-calendar"></span></span>
                        </div>
                        <input type="text" name="start_date" value="{{old('start_date')}}" class="form-control datepicker @error('start_date') is-invalid @enderror">
                        @error('start_date')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="" class="form-control-label">Tanggal Akhir</label>
                        <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><span class="fa fa-calendar"></span></span>
                        </div>
                        <input type="text" name="end_date" value="{{old('end_date')}}" class="form-control datepicker @error('end_date') is-invalid @enderror">
                        @error('end_date')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                        </div>
                    </div>
                  </div>
                  <hr class="my-4">
                  <h6 class="heading-small text-muted mb-4">Detail Diskon</h6>
                  <div class="pl-lg-4" id="urlAddDetail" data-url="{{url('master-menu/diskon/addDetailDiskon')}}">
                    @if (!is_null(old('id_kategori_menu')))
                      @foreach (old('id_kategori_menu') as $n => $value)
                        @php $no++ @endphp
                        @include('master-menu.diskon.tambah-detail-diskon', ['hapus' => false, 'no' => $no, 'kategori_menu' => $kategori_menu])
                      @endforeach
                    @endif
                      @include('master-menu.diskon.tambah-detail-diskon', ['hapus' => false, 'no' => 1, 'kategori_menu' => $kategori_menu])
                  </div>
                  <br>
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