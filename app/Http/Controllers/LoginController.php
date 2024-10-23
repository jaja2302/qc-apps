<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required'
        ]);

        // Retrieve the user based on the email
        $pengguna = Pengguna::where('email', $request->email)->first();

        // dd($pengguna);
        // Check if the user exists and the password is correct
        if (!$pengguna || $request->password != $pengguna->password) {
            return back()->with('error', 'Email atau Password Salah');
        }

        // Store user details in session
        session([
            'user_id' => $pengguna->user_id,
            'user_name' => $pengguna->nama_lengkap,
            'departemen' => $pengguna->departemen,
            'lok' => $pengguna->lokasi_kerja,
            'jabatan' => $pengguna->jabatan,
        ]);

        // Login the user using their email
        auth()->login($pengguna);

        // Check if the user is authorized to access the 'rekap' route
        if (!auth()->check()) {
            // Redirect the user back with an error message if not authorized
            return back()->with('error', 'Unauthorized access');
        }
        if (type_of_user() === 'QC_Mill') {
            return redirect()->intended(route('gradingdahsboard'));
        }
        // Redirect the user to the intended route after successful login
        return redirect()->intended(route('dashboard_inspeksi'));
    }


    public function logout(Request $request)
    {
        // Logout the authenticated user
        auth()->logout();

        // Flush all session data
        $request->session()->flush();

        // Redirect to the home page or any other desired page
        return redirect('/');
    }
}
