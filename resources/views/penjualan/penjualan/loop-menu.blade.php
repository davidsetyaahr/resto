@foreach($menu as $data)
<?php
    $pick = 'false';
    if(!is_null(old('kode_menu')) || isset($penjualan)){
        if((!is_null(old('kode_menu')) && in_array($data->kode_menu, old('kode_menu'))) || (isset($penjualan) && in_array($data->kode_menu, $menuOnDetail))){
            $pick = 'true';
        }
    }
?>

<div class="col-md-3 col-6">
    <div class="menu" data-pick='{{$pick}}' data-menu='{{$data->kode_menu}}'>
        <div class="img">
            <img src="{{asset('assets/img/menu/'.$data->foto)}}" class="img-fluid" alt="">
            <div>
                <span class='info'>Klik Untuk Memesan</span>
                <span class='picked'><i class='fa fa-check'></i> Dipesan</span>
            </div>
        </div>
        <div class="info">
        <h4>{{$data->nama}}</h4>
        <h5 class='text-primary' data-harga='{{$data->harga_jual}}'>{{number_format($data->harga_jual,0,',','.')}}</h5>
        </div>
    </div>
    </div>
@endforeach
<div class="paging-menu">
    {{ $menu->render() }}
</div>
