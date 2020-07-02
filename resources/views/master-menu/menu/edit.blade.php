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
            <form action="{{ route('menu.update', $menu->kode_menu) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('put');
                <div class="card-body">
                    <label for="" class="form-control-label">Kode Menu</label>
                    <input type="text" readonly name="kode_menu" value="{{old('kode_menu', $menu->kode_menu)}}" class="form-control @error('kode_menu') is-invalid @enderror" placeholder="ex : MN0001">
                    @error('kode_menu')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <br>

                    <label for="" class="form-control-label">Nama Menu</label>
                    <input type="text" name="nama" value="{{old('nama', $menu->nama)}}" class="form-control @error('nama') is-invalid @enderror" placeholder="ex : Gurami Pedas">
                    @error('nama')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <br>

                    <label for="" class="form-control-label">Kategori Menu</label>
                    <select name="id_kategori_menu" class="form-control select2 @error('id_kategori_menu') is-invalid @enderror" id="">
                        <option value="">---Pilih Kategori---</option>
                        @foreach ($kategoris as $item)
                    <option value="{{$item->id_kategori_menu}}" {{old('id_kategori_menu', $menu->id_kategori_menu) == $item->id_kategori_menu ? 'selected' : ''}}> {{$item->kategori_menu}} </option>
                        @endforeach
                    </select>
                    @error('id_kategori_menu')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <br>
                    <br>

                    <label for="" class="form-control-label">HPP</label>
                    <input type="number" name="hpp" value="{{old('hpp', $menu->hpp)}}" class="form-control @error('hpp') is-invalid @enderror" placeholder="ex : 12000">
                    @error('hpp')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <br>

                    <label for="" class="form-control-label">Harga Jual</label>
                    <input type="number" name="harga_jual" value="{{old('harga_jual', $menu->harga_jual)}}" class="form-control @error('harga_jual') is-invalid @enderror" placeholder="ex : 15000">
                    @error('harga_jual')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <br>

                    <label for="" class="form-control-label">Foto Menu</label>
                    <br>
                    <img src="{{ asset('assets/img/menu/'.$menu->foto) }}" alt="" width="150px" height="150px">
                    <br>
                    <span>
                        <i>*Kosongi jika tidak ingin mengubah foto</i>
                    </span>
                    <input type="file" name="foto" class="form-control" id="customFile">
                    @error('foto')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <br>
                    
                    <label for="" class="form-control-label">Jenis Menu</label>
                    <select name="jenis_menu" class="form-control select2 @error ('jenis_menu') is-invalid @enderror" id="">
                    <option value="">---Pilih Jenis---</option>
                    <option value="Bar" {{old('jenis_menu', $menu->jenis_menu) == "Bar" ? 'selected' : ''}}>Bar</option>
                    <option value="Dapur" {{old('jenis_menu', $menu->jenis_menu) == "Dapur" ? 'selected' : ''}}>Dapur</option>
                    </select>
                    @error('jenis_menu')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <br>
                    <br>

                    <label for="" class="form-control-label">Status Menu</label>
                    <select name="status" class="form-control select2 @error ('status') is-invalid @enderror" id="">
                    <option value="">---Pilih Status---</option>
                    <option value="Habis" {{old('status', $menu->status) == "Habis" ? 'selected' : ''}}>Habis</option>
                    <option value="Ready" {{old('status', $menu->status) == "Ready" ? 'selected' : ''}}>Ready</option>
                    </select>
                    @error('status')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <br>
                    <br>
                    <button class="btn btn-primary"><span class="fa fa-save"></span> Simpan</button>
                    <button class="btn btn-secondary"><span class="fa fa-times"></span> Reset</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection