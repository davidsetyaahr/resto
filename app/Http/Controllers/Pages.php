<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Pages extends Controller
{
    private $param;
    public function __construct()
    {
        $this->param['icon'] = 'ni-tv-2 text-primary';
    }
    public function dashboard()
    {
        if(\Auth::user()->level=='Waiters'){
            return redirect()->route('penjualan.create');
        }   
        else{
            return view('pages.dashboard', $this->param);
        }     
    }
    public function form()
    {
        $this->param['pageInfo'] = 'Form input';
        $this->param['btnRight']['text'] = 'Lihat Data';
        $this->param['btnRight']['link'] = url('pages/list-data');
        return view('pages.form', $this->param);
    }
    public function list()
    {
        $this->param['pageInfo'] = 'List Data';
        $this->param['btnRight']['text'] = 'Tambah Data';
        $this->param['btnRight']['link'] = url('pages/form');
        return view('pages.list-data', $this->param);
    }
}
