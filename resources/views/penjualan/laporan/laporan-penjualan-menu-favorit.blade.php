<div class="table-responsive mt-3">
    <table class="table align-items-center table-flush table-striped  table-hover">
    <thead class="thead-light">
        <tr>
        <th>#</th>
        <th>Kode Menu</th>
        <th>Nama Menu</th>
        <th>Jumlah Terjual</th>
        <th>Total</th>
        </tr>
    </thead>
    <tbody class="list">
    <?php
        $qty=0;
        $total = 0;
    ?>
    @foreach ($menu as $value)
    <?php 
        $qty = $qty + $value->qty;
        $total = $total + $value->total;
    ?>
            <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$value->kode_menu}}</td>
            <td>{{$value->nama}}</td>
            <td>{{$value->qty}}</td>
            <td>{{number_format($value->total,0,',','.')}}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot class="bg-dark text-white">
        <td colspan='3' class='text-center'><b>TOTAL</b></td>
        <td>{{$qty}}</td>
        <td>{{number_format($total,0,',','.')}}</td>
    </tfoot>
    </table>
</div>
