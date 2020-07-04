<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\User;

class UserController extends Controller
{
    private $param;
    public function __construct()
    {
        $this->param['icon'] = 'ni-single-02 text-cyan';
    }

    public function index(Request $request)
    {
        $this->param['pageInfo'] = 'List Data';
        $this->param['btnRight']['text'] = 'Tambah Data';
        $this->param['btnRight']['link'] = route('user.create');

        $keyword = $request->get('keyword');
        
        if ($keyword) {
            $user = User::where('nama', 'LIKE', "%$keyword%")->paginate(10);
        }
        else{
            $user = User::paginate(10);
        }

        return \view('master-user.user.list-user', ['user' => $user], $this->param);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->param['pageInfo'] = 'Tambah Data';
        $this->param['btnRight']['text'] = 'Lihat Data';
        $this->param['btnRight']['link'] = route('user.index');

        return \view('master-user.user.tambah-user', $this->param);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required',
            'username' => 'required|unique:users',
            'gender' => 'required',
            'email' => 'email|unique:users',
            'no_hp' => 'numeric',            
            'level' => 'required',
            'alamat' => 'required',
            'password' => 'required',
            'konfirmasi_password' => 'required|same:password',
        ]);

        $newUser = new User;

        $newUser->nama = $request->get('nama');
        $newUser->username = $request->get('username');
        $newUser->gender = $request->get('gender');
        $newUser->email = $request->get('email');
        $newUser->no_hp = $request->get('no_hp');
        $newUser->alamat = $request->get('alamat');
        $newUser->level = $request->get('level');
        $newUser->password = \Hash::make($request->get('level'));

        $newUser->save();

        return redirect()->route('user.create')->withStatus('Data berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $this->param['pageInfo'] = 'Edit Data';
        $this->param['btnRight']['text'] = 'Lihat Data';
        $this->param['btnRight']['link'] = route('user.index');

        $user = User::findOrFail($id);
        return \view('master-user.user.edit-user', ['user' => $user], $this->param);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $isUniqueUsername = $user->username == $request->get('username') ? "" : "|unique:user";
        $isUniqueEmail = $user->email == $request->get('email') ? "" : "|unique:user";

        $validatedData = $request->validate([
            'nama' => 'required',
            'username' => 'required'.$isUniqueUsername,
            'gender' => 'required',
            'email' => 'email'.$isUniqueEmail,
            'no_hp' => 'numeric',            
            'level' => 'required',
            'alamat' => 'required',
        ]);

        $user->nama = $request->get('nama');
        $user->username = $request->get('username');
        $user->gender = $request->get('gender');
        $user->email = $request->get('email');
        $user->no_hp = $request->get('no_hp');
        $user->alamat = $request->get('alamat');
        $user->level = $request->get('level');

        $user->save();

        return redirect()->route('user.index')->withStatus('Data berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function gantiPassword()
    {
        $this->param['pageInfo'] = 'Ganti Password';
        $this->param['btnRight']['text'] = '';
        $this->param['btnRight']['link'] = '#';

        return \view('master-user.user.ganti-password', $this->param);
    }

    public function updatePassword(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'password' => 'required',
            'konfirmasi_password' => 'required|same:password',
            'old_password' => ['required', function ($attribute, $value, $fail) use ($user) {
                if (!\Hash::check($value, $user->password)) {
                    return $fail(__('Password lama tidak sesuai.'));
                }
            }],
        ]);

        $user->password = \Hash::make($request->get('password'));

        $user->save();

        return redirect()->route('user.ganti-password')->withStatus(__('Password berhasil diperbarui.'));
    }
}
