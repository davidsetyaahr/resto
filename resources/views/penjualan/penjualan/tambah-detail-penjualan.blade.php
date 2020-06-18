<div class="row row-detail mb-3" data-no='{{$no}}'>
    <div class="col-md-3 {{ isset($n)&&$errors->has('kode_menu.'.$n) ? ' is-invalid' : '' }}">
        <label for="" class="form-control-label">Menu</label>
        <select name="kode_menu[]" class="form-control select2 menu2" id="">
            <option value=''>---Select---</option>
            @foreach($menu as $value)
                <option value="{{$value->kode_menu}}" {{ isset($n)&&old('kode_menu.'.$n) == $value->kode_menu ? 'selected' : ''}}>{{$value->kode_menu.' ~ '.$value->nama}}</option>
            @endforeach
        </select>
        @if(isset($n)&&$errors->has('kode_menu.'.$n))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('kode_menu.'.$n) }}</strong>
        </span>
        @endif
    </div>
    <div class="col-md-2">
        <label for="" class="form-control-label">Qty</label>
        <input type="number" name="qty[]" value="{{isset($n) ? old('qty.'.$n) : ''}}" class="form-control qtyPj {{ isset($n)&&$errors->has('qty.'.$n) ? ' is-invalid' : '' }}" data-other='#harga'>
        @if(isset($n)&&$errors->has('qty.'.$n))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('qty.'.$n) }}</strong>
        </span>
        @endif
    </div>
    <div class="col-md-2">
        <label for="" class="form-control-label">Harga Jual</label>
        <input type="number" name="harga[]" value="{{isset($n) ? old('harga.'.$n) : ''}}" class="form-control getSubtotal {{ isset($n)&&$errors->has('harga.'.$n) ? ' is-invalid' : '' }}" data-other='#qty' id='harga' readonly>
        @if(isset($n)&&$errors->has('harga.'.$n))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('harga.'.$n) }}</strong>
        </span>
        @endif
    </div>
    <div class="col-md-2">
        <label for="" class="form-control-label">Diskon</label>
        <input type="number" name="diskon[]" value="{{isset($n) ? old('diskon.'.$n) : ''}}" class="form-control diskon" readonly id="diskon">
    </div>
    <input type="hidden" name="diskon_satuan" id="diskon_satuan">
    <div class="col-md-2">
        <label for="" class="form-control-label">Subtotal</label>
        <input type="number" name="subtotal[]" value="{{isset($n) ? old('subtotal.'.$n) : ''}}" class="form-control getTotalPj" readonly id="subtotal">
    </div>
    <div class="col-md-2">
        <label for="" class="form-control-label">Keterangan</label>
        <input type="text" name="keterangan[]" value="{{isset($n) ? old('keterangan.'.$n) : ''}}" class="form-control">
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
