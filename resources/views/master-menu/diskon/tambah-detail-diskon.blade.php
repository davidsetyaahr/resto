<div class="row row-detail mb-3" data-no='{{$no}}'>
    <div class="col-md-5 {{isset($n)&&$errors->has('kode_menu.'.$n) ? ' is-invalid' : ''}}">
        <label for="" class="form-control-label">Menu</label>
        <select name="kode_menu[]" class="form-control select2 menu">
            <option value="">---Pilih Menu---</option>
            @foreach ($menu as $item)
        <option value="{{$item->kode_menu}}" {{ isset($n)&&old('kode_menu.'.$n) == $item->kode_menu ? 'selected' : ''}}>{{$item->kode_menu.'~'.$item->nama}}</option>
            @endforeach
        </select>
        @if (isset($n)&&$errors->has('kode_menu.'.$n))
            <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('kode_menu.'.$n) }}</strong>
            </span>
        @endif
    </div>
    <div class="col-md-3">
        <label for="" class="form-control-label">Harga Jual</label>
    <input type="text" value="{{isset($n) ? old('harga_jual.'.$n) : ''}}" name="harga_jual[]" id="harga_jual" class="form-control" readonly>
    </div>
    <div class="col-md-3">
        <label for="" class="form-control-label">Harga Setelah Diskon</label>
    <input type="text" value="{{isset($n) ? old('harga_jual.'.$n) : ''}}" name="harga_setelah_diskon[]" class="form-control" readonly id="harga_setelah_diskon">
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
