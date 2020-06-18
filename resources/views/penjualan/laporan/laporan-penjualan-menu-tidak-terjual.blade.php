<div class="table-responsive mt-3">
    <table class="table align-items-center table-flush table-striped  table-hover">
    <thead class="thead-light">
        <tr>
        <th>#</th>
        <th>Kode Menu</th>
        <th>Nama Menu</th>
        <th>HPP</th>
        <th>Harga Jual</th>
        <th>Status</th>
        </tr>
    </thead>
    <tbody class="list">
    @foreach ($menu as $value)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$value->kode_menu}}</td>
            <td>{{$value->nama}}</td>
            <td>{{$value->hpp}}</td>
            <td>{{number_format($value->harga_jual,0,',','.')}}</td>
            <td>{{$value->status}}</td>
        </tr>
        @endforeach
    </tbody>
    </table>
</div>
