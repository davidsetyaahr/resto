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
            <form action="{{ route('kas.update', $kas->kode_kas) }}" method="post">
              @csrf
              @method('put')
              <div class="card-body">
                  <label for="" class="form-control-label">Kode Kas Keluar</label>
                  <input type="text" name="kode_kas" value="{{old('kode_kas', $kas->kode_kas)}}" class="form-control @error('kode_kas') is-invalid @enderror" id="kode" readonly>
                  @error('kode_kas')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                  <br>
                  
                  <label for="" class="form-control-label">Tipe</label>
                  <select name="tipe" id="tipe" class="form-control @error('tipe') is-invalid @enderror">
                    <option value="">--Pilih Tipe--</option>
                    <option value="Masuk" {{old('tipe', $kas->tipe) == 'Masuk' ? 'selected' : ''}} >Masuk</option>
                    <option value="Keluar" {{old('tipe', $kas->tipe) == 'Keluar' ? 'selected' : ''}}>Keluar</option>
                  </select>
                  @error('tipe')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                  <br>

                  <label for="" class="form-control-label">Tanggal</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><span class="fa fa-calendar"></span></span>
                    </div>
                    <input type="text" name="tanggal" value="{{old('tanggal', $kas->tanggal)}}" class="form-control datepicker @error('tanggal') is-invalid @enderror" placeholder="ex : 2020-06-20" }}">
                  </div> 
                  @error('tanggal')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                  <br>

                  <label for="" class="form-control-label">Nominal</label>
                  <input type="number" name="nominal" value="{{old('nominal', $kas->nominal)}}" class="form-control @error('nominal') is-invalid @enderror" placeholder="ex : 250000">
                  @error('nominal')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                  <br>

                  <label for="" class="form-control-label">Jenis</label>
                  <select name="jenis" id="jenis" class="form-control @error('jenis') is-invalid @enderror">
                    <option value="">-- Pilih Jenis --</option>
                    <option value="Cash" {{old('jenis', $kas->jenis) == 'Cash' ? 'selected' : ''}} >Cash</option>
                    <option value="BCA" {{old('jenis', $kas->jenis) == 'BCA' ? 'selected' : ''}} >BCA</option>
                    <option value="BRI" {{old('jenis', $kas->jenis) == 'BRI' ? 'selected' : ''}} >BRI</option>
                    <option value="Bank Lain" {{old('jenis', $kas->jenis) == 'Bank Lain' ? 'selected' : ''}} >Bank Lain</option>
                  </select>
                  @error('jenis')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                  <br>
                  <br>
                  
                  <label for="" class="form-control-label ">Keterangan</label>
                  <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror">{{old('keterangan', $kas->keterangan)}}</textarea>
                  @error('keterangan')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                  <br>

                  <button type="submit" class="btn btn-primary"><span class="fa fa-save"></span> Simpan</button>
                  <button type="reset" class="btn btn-secondary"><span class="fa fa-times"></span> Reset</button>
              </div>
            </form>
        </div>
    </div>
</div>
@endsection