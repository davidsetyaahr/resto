<div class="row row-detail mb-3" data-no='{{$no}}'>
    <div class="col-md-3 {{ $errors->has($fields['kode_barang']) ? ' is-invalid' : '' }}">
        <label for="" class="form-control-label">Barang</label>
        <select name="kode_barang[]" class="form-control select2" id="">
            <option value=''>---Select---</option>
            @foreach($barang as $value)
                <option value="{{$value->kode_barang}}" {{ old($fields['kode_barang'], isset($edit) ?  $edit->kode_barang : '') == $value->kode_barang ? 'selected' : ''}}>{{$value->kode_barang.' ~ '.$value->nama}}</option>
            @endforeach
        </select>
        @if($errors->has($fields['kode_barang']))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first($fields['kode_barang']) }}</strong>
        </span>
        @endif
    </div>
    <div class="col-md-2">
        <label for="" class="form-control-label">Qty</label>
        <input type="number" name="qty[]" value="{{old($fields['qty'],isset($edit) ? $edit->qty : '')}}" class="form-control getSubtotal {{ $errors->has($fields['qty']) ? ' is-invalid' : '' }}" data-other='#harga' id='qty'>
        @if($errors->has($fields['qty']))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first($fields['qty']) }}</strong>
        </span>
        @endif
    </div>
    <div class="col-md-3">
        <label for="" class="form-control-label">Harga (Satuan)</label>
        <input type="number" name="harga[]" value="{{old($fields['harga'], isset($harga) ? $harga : '')}}" class="form-control getSubtotal {{ $errors->has($fields['harga']) ? ' is-invalid' : '' }}" data-other='#qty' id='harga'>
        @if($errors->has($fields['harga']))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first($fields['harga']) }}</strong>
        </span>
        @endif
    </div>
    <div class="col-md-3">
        <label for="" class="form-control-label">Subtotal</label>
        <input type="number" name="subtotal[]" value="{{old($fields['subtotal'], isset($edit) ? $edit->subtotal : '')}}" class="form-control subtotal" readonly>
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
