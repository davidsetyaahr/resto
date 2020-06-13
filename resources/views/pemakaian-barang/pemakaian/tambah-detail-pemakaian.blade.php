<div class="row row-detail mb-3" data-no='{{$no}}'>
    <div class="col-md-3">
        <label for="" class="form-control-label">Barang</label>
        <select name="kode_barang[]" class="form-control select2 barang">
            <option value=''>---Pilih Barang---</option>
            @foreach ($barang as $item)
                <option value="{{$item->kode_barang}}">{{$item->kode_barang . ' - ' .$item->nama}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <label for="" class="form-control-label">Sisa Stock</label>
        <input type="text" name="sisa_stock[]" value="" class="form-control" id='sisa_stock' readonly>
    </div>
    <div class="col-md-2">
        <label for="" class="form-control-label">Qty</label>
        <input type="number" name="qty[]" value="" class="form-control getSubtotal" id='qty' required>
    </div>
    <div class="col-md-2">
        <label for="" class="form-control-label">Satuan</label>
        <input type="text" name="satuan[]" value="" class="form-control" id='satuan' readonly>
    </div>
    <div class="col-md-2">
        <label for="" class="form-control-label">Keterangan</label>
        <input type="text" name="keterangan[]" value="" class="form-control" id='keterangan'>
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
