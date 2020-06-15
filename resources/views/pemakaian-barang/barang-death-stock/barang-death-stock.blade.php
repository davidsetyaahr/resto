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
              <div class="table-responsive mt-4">
                <table class="table align-items-center table-flush table-hover table-striped">
                  <thead class="thead-light">
                    <tr>
                      <th>#</th>
                      <th>Kode Barang</th>
                      <th>Nama Barang</th>
                      <th>Sisa Stock</th>
                      <th>Satuan</th>
                      <th>Saldo</th>
                    </tr>
                  </thead>
                  <tbody class="list">
                  @php $qty = 0; $total = 0; @endphp
                  @foreach ($deathStock as $value)
                  @php 
                      $qty += $value->stock;
                      $total += $value->saldo;
                  @endphp
                    <tr>
                      <td>{{$loop->iteration}}</td>
                      <td>{{$value->kode_barang}}</td>
                      <td>{{$value->nama}}</td>
                      <td>{{$value->stock}}</td>
                      <td>{{$value->satuan}}</td>
                      <td>{{number_format($value->saldo,0,',','.')}}</td>
                    </tr>
                  @endforeach
                  </tbody>
                  <tfoot class="bg-dark text-white">
                      <tr>
                          <td colspan='3' class='text-center'><b>TOTAL</b></td>
                          <td>{{$qty}}</td>
                          <td></td>
                          <td>{{number_format($total,0,',','.')}}</td>
                      </tr>
                  </tfoot>
                </table>
              </div>
            </div>
        </div>
    </div>
</div>
@endsection