<div class="row row-detail mb-3" data-no='{{$no}}'>
    <div class="col-md-3 {{ isset($n)&&$errors->has('kode_barang.'.$n) ? ' is-invalid' : '' }}">
        <label for="" class="form-control-label">Barang</label>
        <select name="kode_barang[]" class="form-control select2" id="">
            <option value=''>---Select---</option>
            @foreach($barang as $value)
                <option value="{{$value->kode_barang}}" {{ isset($n)&&old('kode_barang.'.$n) == $value->kode_barang ? 'selected' : ''}}>{{$value->kode_barang.' ~ '.$value->nama}}</option>
            @endforeach
        </select>
        @if(isset($n)&&$errors->has('kode_barang.'.$n))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('kode_barang.'.$n) }}</strong>
        </span>
        @endif
    </div>
    <div class="col-md-2">
        <label for="" class="form-control-label">Qty</label>
        <input type="number" name="qty[]" value="{{isset($n) ? old('qty.'.$n) : ''}}" class="form-control getSubtotal {{ isset($n)&&$errors->has('qty.'.$n) ? ' is-invalid' : '' }}" data-other='#harga' id='qty'>
        @if(isset($n)&&$errors->has('qty.'.$n))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('qty.'.$n) }}</strong>
        </span>
        @endif
    </div>
    <div class="col-md-3">
        <label for="" class="form-control-label">Harga (Satuan)</label>
        <input type="number" name="harga[]" value="{{isset($n) ? old('harga.'.$n) : ''}}" class="form-control getSubtotal {{ isset($n)&&$errors->has('harga.'.$n) ? ' is-invalid' : '' }}" data-other='#qty' id='harga'>
        @if(isset($n)&&$errors->has('harga.'.$n))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('harga.'.$n) }}</strong>
        </span>
        @endif
    </div>
    <div class="col-md-3">
        <label for="" class="form-control-label">Subtotal</label>
        <input type="number" name="subtotal[]" value="{{isset($n) ? old('subtotal.'.$n) : ''}}" class="form-control subtotal" readonly>
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
