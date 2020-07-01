<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Cetak Struk</title>
  {{-- <style>
    *{
        /* font-family : "Dot Matrix"; */
        /* font-size : 17px; */
        color: black;
    }
  </style>
  <style type="text/css" media="print">
    @page {
        size:58mm 80mm;
    }
    body {
      width:100%;
      height:100%;
    } 
  </style> --}}
  <style type="text/css">
  *{
        /* font-family : "Dot Matrix"; */
        font-size : 15px;
        color: black;
    }
  /* @media print and (width: 58mm) and (height: 80mm) { */
        /* @page {
          margin: 3cm;
        } */
  /* } */
  /* @page {
      size: 58mm 80mm;
      margin: 10%;
  } */
  body{
    width: 58mm;
    height: 80mm;
  }
  /* @media print { 
    body{ 
      width: 58mm;
      height: 80mm
    }
  } */
  </style>
  <link rel="icon" href="{{ asset('assets/img/coffee.png') }}" type="image/png">
</head>
<body>
    {{-- <div class="row"> --}}
      {{--  style="margin-left:1px" --}}
      {{-- <div class="col-12"> --}}
        <center>
          <p>{{$resto->nama}}</p>
          <p>{{$resto->alamat}}</p>
          <p>{{$resto->kota}}</p>
        </center>
        <table>
          <tr>
            <td>Kode Struk</td>
            <td>:</td>
            <td>{{$penjualan->kode_penjualan}}</td>
          </tr>
          <tr>
            <td>Waktu</td>
            <td>:</td>
            <td>{{date('d-m-Y H:i', strtotime($penjualan->waktu))}}</td>
          </tr>
          @php
            // $kasir = \DB::table('users')
            //               ->select('name')
            //               ->where('id', $penjualan->user_id)
            //               ->get();
          @endphp
          <tr>
            <td>Kasir</td>
            <td>:</td>
            <td>David</td>
          </tr>
        </table>
        <hr>
        <table>
          @foreach ($detail as $value)
            @php
              $menu = \DB::table('menu')
                            ->select('nama')
                            ->where('kode_menu', $value->kode_menu)
                            ->get();
            @endphp
            <tr>
              <td width="100px">{{$menu[0]->nama}}</td>
              <td width="40px" class="text-center">{{$value->qty}}</td>
              <td width="40px">{{number_format($value->sub_total, 0, ',', '.')}}</td>
            </tr>
          @endforeach
        </table>
        <hr>
        <table>
          @php
              $grandtotal = $penjualan->total_harga - $penjualan->total_diskon - $penjualan->total_diskon_tambahan + $penjualan->total_ppn;
          @endphp
          <tr>
            <td>Total</td>
            <td width="40px"></td>
            <td>{{number_format($penjualan->total_harga, 0, ',', '.')}}</td>
          </tr>
          <tr>
            <td>PPN</td>
            <td width="40px"></td>
            <td>{{number_format($penjualan->total_ppn, 0, ',', '.')}}</td>
          </tr>
          <tr>
            <td>Diskon</td>
            <td width="40px"></td>
            <td>{{number_format($penjualan->total_diskon + $penjualan->total_diskon_tambahan, 0, ',', '.')}}</td>
          </tr>
          <tr>
            <td>Grandtotal</td>
            <td width="40px"></td>
            <td>{{number_format($grandtotal, 0, ',', '.')}}</td>
          </tr>
          {{-- <tr>
            <td>Bayar</td>
            <td width="40px"></td>
            <td>{{number_format($penjualan->bayar, 0, ',', '.')}}</td>
          </tr>
          <tr>
            <td>Kembalian</td>
            <td width="40px"></td>
            <td>{{number_format($penjualan->bayar - $grandtotal, 0, ',', '.')}}</td>
          </tr> --}}
        </table>
        <center>
          <p>Kontak : {{$resto->telepon}}</p>
          <p>Email : {{$resto->email}}</p>
        </center> 
      {{-- </div>
    </div> --}}
</body>
<script>
  window.print();
  window.onafterprint = function(event) {
      window.location.href = 'http://localhost/resto/penjualan/penjualan/create'
  };
</script>
</html>

