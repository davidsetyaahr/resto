<div class="row row-detail mb-4" data-no='{{$no}}'>
    <div class="col-md-5 {{isset($n)&&$errors->has('id_kategori_menu.'.$n) ? ' is-invalid' : ''}}">
        <label for="" class="form-control-label">Kategori Menu</label>
        <select name="id_kategori_menu[]" class="form-control select2">
            <option value="">---Pilih Kategori Menu---</option>
            @foreach ($kategori_menu as $item)
                <option value="{{$item->id_kategori_menu}}" {{ isset($n)&&old('id_kategori_menu.'.$n) == $item->id_kategori_menu ? 'selected' : ''}}>{{$item->kategori_menu}}</option>
            @endforeach
        </select>
        @if (isset($n)&&$errors->has('id_kategori_menu.'.$n))
            <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('id_kategori_menu.'.$n) }}</strong>
            </span>
        @endif
    </div>
    
    <div class="col-md-1">
        <div class="dropdown mt-4">
            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-ellipsis-v"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                <a class="dropdown-item addDetail" data-no='{{$no}}' href="">Tambah</a>
                @if($hapus)
                <a class="dropdown-item deleteDetail" data-no='{{$no}}' href="">Hapus</a>
                @endif
            </div>
        </div>
    </div>
</div>
