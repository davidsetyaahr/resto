<div class="row row-detail mb-3" data-no='{{$no}}'>
    <div class="col-md-3">
        <label for="" class="form-control-label">Barang</label>
        <select name="kode_barang[]" class="form-control select2" id="">
            <option value=''>---Select---</option>
        </select>
    </div>
    <div class="col-md-2">
        <label for="" class="form-control-label">Qty</label>
        <input type="number" name="qty[]" value="" class="form-control getSubtotal" data-other='#harga' id='qty'>
    </div>
    <div class="col-md-3">
        <label for="" class="form-control-label">Harga (Satuan)</label>
        <input type="number" name="harga[]" value="" class="form-control getSubtotal" data-other='#qty' id='harga'>
    </div>
    <div class="col-md-3">
        <label for="" class="form-control-label">Subtotal</label>
        <input type="number" name="subtotal[]" value="" class="form-control subtotal" readonly>
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
