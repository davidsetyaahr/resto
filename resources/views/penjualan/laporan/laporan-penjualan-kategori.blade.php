`<div class="table-responsive mt-3">
    <table class="table align-items-center table-flush">
        <thead class="thead-light">
            <tr>
                <th>#</th>
                <th>Grup</th>
                <th>Qty</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody class="list">
            <?php
                $qty=0;
                $total=0;
            ?>
            @foreach($penjualan as $value)
                <?php
                    $qty = $qty + $value->total_qty;
                    $total = $total + $value->grand_total;
                ?>
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$value->nama_grup_kategori}}</td>
                    <td>{{$value->total_qty}}</td>
                    <td>{{number_format($value->grand_total,0,',','.')}}</td>
                </tr>
            @endforeach

        </tbody>
        <tfoot class="bg-dark text-white">
            <tr>
                <td colspan="2" class="text-center"><b>TOTAL</b></td>
                <td>{{$qty}}</td>
                <td>{{number_format($total,0,',','.')}}</td>
            </tr>
        </tfoot>
</div>
