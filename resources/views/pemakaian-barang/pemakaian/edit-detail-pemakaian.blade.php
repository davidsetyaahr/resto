<div class="row row-detail mb-3" data-no='{{$no}}'>
  <input type="hidden" name='id_detail[]' class='idDetail' value='{{$idDetail}}'>
  <div class="col-md-3 {{ $errors->has($fields['kode_barang']) ? ' is-invalid' : '' }}">
      <label for="" class="form-control-label">Barang</label>
      <select name="kode_barang[]" class="form-control select2 barang" data-url="{{ route('pemakaian.get-detail-barang') }}">
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
      <label for="" class="form-control-label">Sisa Stock</label>
      <input type="text" name="sisa_stock[]" value="{{old($fields['sisa_stock'], isset($edit) ? $edit->sisa_stock : '')}}" class="form-control" id='sisa_stock' readonly>
  </div>
  <div class="col-md-2">
      <label for="" class="form-control-label">Qty</label>
      <input type="number" name="qty[]" value="{{old($fields['qty'], isset($edit) ? $edit->qty : '')}}" class="form-control totalQty {{ isset($n)&&$errors->has('qty.'.$n) ? ' is-invalid' : '' }}" id='qty'>
      @if(isset($n)&&$errors->has('qty.'.$n))
      <span class="invalid-feedback" role="alert">
          <strong>{{ $errors->first('qty.'.$n) }}</strong>
      </span>
      @endif
  </div>
  <div class="col-md-2">
      <label for="" class="form-control-label">Satuan</label>
      <input type="text" name="satuan[]" value="{{old($fields['satuan'], isset($edit) ? $edit->satuan : '')}}" class="form-control" id='satuan' readonly>
  </div>
  <div class="col-md-2">
      <label for="" class="form-control-label">Keterangan</label>
      <input type="text" name="keterangan[]" value="{{old($fields['keterangan'], isset($edit) ? $edit->keterangan : '')}}" class="form-control" id='keterangan'>
  </div>
  
  <div class="col-md-1">
      <div class="dropdown mt-4">
          <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-ellipsis-v"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
              <a class="dropdown-item addDetail" data-no='{{$no}}' href="">Tambah</a>
              @if($hapus)
              <a class="dropdown-item deleteDetail addDeleteId" data-no='{{$no}}' href="">Hapus</a>
              @endif
          </div>
      </div>
  </div>
</div>
