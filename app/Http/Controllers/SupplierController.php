<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Supplier;

class SupplierController extends Controller
{
    private $param;
    public function __construct()
    {
        $this->param['icon'] = 'ni-bag-17 text-yellow';
    }
    public function index()
    {
        $this->param['pageInfo'] = 'Daftar Supplier';
        $this->param['btnRight']['text'] = 'Tambah Supplier';
        $this->param['btnRight']['link'] = route('supplier.create');
        $this->param['supplier'] = Supplier::paginate(10);

        return view('pembelian-barang.supplier.list-supplier', $this->param);
    }
    public function create()
    {
        $this->param['pageInfo'] = 'Tambah Supplier';
        $this->param['btnRight']['text'] = 'Lihat Supplier';
        $this->param['btnRight']['link'] = route('supplier.index');
    
        return view('pembelian-barang.supplier.tambah-supplier', $this->param);
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'kode_supplier' => 'required|unique:supplier|max:8',
            'nama_supplier' => 'required|max:50',
        ]);

        $newSupplier = new Supplier;

        $newSupplier->kode_supplier = $request->get('kode_supplier');
        $newSupplier->nama_supplier = $request->get('nama_supplier');
        $newSupplier->no_hp = $request->get('no_hp');
        $newSupplier->alamat = $request->get('alamat');
        $newSupplier->keterangan = $request->get('keterangan');
        
        $newSupplier->save();

        return redirect()->route('supplier.index')->withStatus('Data berhasil ditambahkan.');
    }
    public function edit($kode)
    {
        $this->param['pageInfo'] = 'Tambah Supplier';
        $this->param['btnRight']['text'] = 'Lihat Supplier';
        $this->param['btnRight']['link'] = route('supplier.index');
        
        $this->param['supplier'] = Supplier::findOrFail($kode);
        
        return view('pembelian-barang.supplier.edit-supplier', $this->param);
    }
    public function update(Request $request, $kode)
    {
        $validatedData = $request->validate([
            'kode_supplier' => 'required|max:8',
            'nama_supplier' => 'required|max:50',
        ]);
            
        $supplier = Supplier::findOrFail($kode);
        $supplier->kode_supplier = $request->get('kode_supplier');
        $supplier->nama_supplier = $request->get('nama_supplier');
        $supplier->no_hp = $request->get('no_hp');
        $supplier->alamat = $request->get('alamat');
        $supplier->keterangan = $request->get('keterangan');
        
        $supplier->save();
        
        return redirect()->route('supplier.index')->withStatus('Data berhasil diperbarui.');
    }
    public function destroy($kode)
    {
        $supplier = Supplier::findOrFail($kode);
        $supplier->delete();

        return redirect()->route('supplier.index')->withStatus('Data berhasil dihapus.');
    }
}
