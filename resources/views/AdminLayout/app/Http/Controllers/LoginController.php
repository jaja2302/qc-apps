<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class LoginController extends Controller
{
    //
    public function index(Request $request)
    {
        return view('Admin.index');
    }
  public function authenticate(Request $request)
    {
        $this->validate($request, [
            'email_or_nama_lengkap' => 'required',
            'password' => 'required'
        ]);

        $user = DB::table('pengguna')
            ->where('email', $request->email_or_nama_lengkap)
            ->orWhere('nama_lengkap', $request->email_or_nama_lengkap)
            ->first();

        if (!$user) {
            return back()->with('error', 'Email atau Password Salah');
        }

        if ($request->password != $user->password) {
            return back()->with('error', 'Email atau Password Salah');
        }

        session([
            'user_id' => $user->user_id,
            'user_name' => $user->nama_lengkap,
            'departemen' => $user->departemen,
            'lok' => $user->lokasi_kerja,
            'jabatan' => $user->jabatan,
        ]);

        return redirect()->intended('dashboard_inspeksi');
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect('/');
    }
}


