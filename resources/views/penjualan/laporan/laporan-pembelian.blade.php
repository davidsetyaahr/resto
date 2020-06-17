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
            <div class="card-body">
            <form action="" method="get">
            <div class="row align-items-center">
                <div class="col-4">
                    <label for="" class="form-control-label">Dari Tanggal</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><span class="fa fa-calendar"></span></span>
                        </div>
                        <input type="text" class="datepicker form-control" name="dari" value="{{isset($_GET['dari']) ? $_GET['dari'] : ''}}">
                    </div>                
                </div>
                <div class="col-4">
                    <label for="" class="form-control-label">Sampai Tanggal</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><span class="fa fa-calendar"></span></span>
                        </div>
                        <input type="text" class="datepicker form-control" name="sampai" value="{{isset($_GET['sampai']) ? $_GET['sampai'] : ''}}">
                    </div>                
                </div>
                <div class="col-4">
                    <label for="" class="form-control-label">Kode Supplier</label>
                    <select name="kode_supplier" class="form-control select2" id="">
                        <option value=''>---Select---</option>
                        @foreach($supplier as $value)
                        <option value="{{$value->kode_supplier}}" {{isset($_GET['kode_supplier'])&&$_GET['kode_supplier']==$value->kode_supplier ? 'selected' : ''}}>{{$value->kode_supplier.' ~ '.$value->nama_supplier}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col mt-4">
                <button type="submit" class="btn btn-primary"><span class="fa fa-save"></span> Simpan</button>
                  <button type="reset" class="btn btn-secondary"><span class="fa fa-times"></span> Reset</button>
                </div>
              </div>
            </form>
            <?php 
                if(isset($laporan)){
            ?>
            <a href="<?php echo "laporan-pembelian?dari=$_GET[dari]&sampai=$_GET[sampai]&kode_supplier=$_GET[kode_supplier]&print=ok" ?>" class="btn btn-sm btn-success float-right py-2 px-3"><span class="fa fa-print"></span> Print</a>
            <br>
            <div class="table-responsive mt-4">
              <table class="table align-items-center table-flush table-hover table-striped">
                <thead class="thead-light">
                  <tr>
                    <th>#</th>
                    <th>Kode Pembelian</th>
                    <th>Tanggal Pembelian</th>
                    <th>Kode Supplier</th>
                    <th>Jumlah Item</th>
                    <th>Jumlah Qty</th>
                    <th>Total</th>
                  </tr>
                </thead>
                <tbody class="list">
                @php $qty = 0; $total = 0; @endphp
                @foreach ($laporan as $value)
                @php 
                    $qty = $qty + $value->jumlah_qty; 
                    $total = $qty + $value->total; 
                @endphp
                      <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$value->kode_pembelian}}</td>
                        <td>{{$value->tanggal}}</td>
                        <td>{{$value->kode_supplier}}</td>
                        <td>{{$value->jumlah_item}}</td>
                        <td>{{$value->jumlah_qty}}</td>
                        <td>{{number_format($value->total,0,',','.')}}</td>
                      </tr>
                  @endforeach
                </tbody>
                <tfoot class="bg-dark text-white">
                    <tr>
                        <td colspan='5' class='text-center'><b>TOTAL</b></td>
                        <td>{{$qty}}</td>
                        <td>{{number_format($total,0,',','.')}}</td>
                    </tr>
                </tfoot>
              </table>
            </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
@endsection