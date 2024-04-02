<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Pengguna;

class LoginController extends Controller
{
    //
    public function index(Request $request)
    {
        return view('Auth.index');
    }

    public function authenticate(Request $request)
    {
        // Validate the request data
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required'
        ]);

        // Retrieve the user based on email or nama_lengkap
        $user = Pengguna::where('email', $request->email)
            ->orWhere('nama_lengkap', $request->email)
            ->first();

        if (!$user) {
            // User not found logic
            return back()->with('error', 'Email atau Password Salah');
        }

        // Attempt to log in the user
        if ($request->password === $user->password) {
            // Password matches
            Auth::login($user);
            session([
                'user_id' => $user->user_id,
                'user_name' => $user->nama_lengkap,
                'departemen' => $user->departemen,
                'lok' => $user->lokasi_kerja,
                'jabatan' => $user->jabatan,
            ]);
            return redirect()->route('rekap');

            // Redirect or perform other actions
        } else {

            // Password does not match
            return back()->with('error', 'Email atau Password Salah');
        }
    }




    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect('/');
    }
}
