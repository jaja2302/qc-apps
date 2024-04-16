<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class UserQCController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($lokasi_kerja)
    {
        //

        $query = DB::connection('mysql')->table("pengguna")->where('departemen', 'QC')->where('lokasi_kerja', $lokasi_kerja)->get();

        // dd($query);
        return view('Gudang.edit', ['data' => $query]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('qc.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $lokasi_kerja)
    {
        //



        // dd($request->all());
        $queryNewUser = DB::connection('mysql')->table("pengguna")->insert([
            'nama_lengkap' => $request->input('nama_lengkap'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'departemen' => 'QC',
            'afdeling' => '',
            'no_hp' => '',
            // 'jabatan' => '',
            'status_akun' => '1',
            'lokasi_kerja' => $lokasi_kerja,
            'nama_perusahaan' => '',
            'akses_level' => 1,
        ]);

        if ($queryNewUser) {
            $version = DB::connection('mysql2')->table("user_qc_version")->where('id', 1)->first()->version;
            $user = DB::connection('mysql2')->table("user_qc_version")->where('id', 1)->update(['version' => $version + 1]);
            return redirect()->route('user_qc', ['lokasi_kerja' => $lokasi_kerja]);
            // return Redirect::back()->with(['message' => 'Berhasil Insert data user']);
        } else {
            return redirect()->route('user_qc', ['lokasi_kerja' => $lokasi_kerja]);
            // return Redirect::back()->with(['message' => 'Gagal Insert data user']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $query = DB::connection('mysql')->table("pengguna")->where('user_id', $id)->first();

        return view('qc.edit', ['data' => $query]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, $lokasi_kerja)
    {

        $req = $request->all();

        $request->validate([
            'nama_lengkap' => 'required',
            'email' => 'required',
            'password' => 'required|min:6',
        ]);

        $user = DB::connection('mysql')->table("pengguna")
            ->where('user_id', $id)
            ->update([
                'nama_lengkap' => $request->input('nama_lengkap'),
                'email' => $request->input('email'),
                'password' => $request->input('password')
            ]);


        if ($user > 0) {
            $version = DB::connection('mysql2')->table("user_qc_version")->where('id', 1)->first()->version;
            $user = DB::connection('mysql2')->table("user_qc_version")->where('id', 1)->update(['version' => $version + 1]);
            // return Redirect::back()->with(['message' => 'Berhasil meng-update data user']);
            return redirect()->route('user_qc', ['lokasi_kerja' => $lokasi_kerja])->with(['message' => 'Berhasil Update data user']);
        } else {
            return redirect()->route('user_qc', ['lokasi_kerja' => $lokasi_kerja])->with(['message' => 'Gagal Update data user']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $user =  DB::connection('mysql')->table("pengguna")->where('user_id', '=', $id)->delete();

        if ($user > 0) {
            // Delete was successful
            $version = DB::connection('mysql2')->table("user_qc_version")->where('id', 1)->first()->version;
            $user = DB::connection('mysql2')->table("user_qc_version")->where('id', 1)->update(['version' => $version + 1]);
            return Redirect::back()->with(['message' => 'Berhasil meng-update data user']);
        } else {
            // Delete failed
            return Redirect::back()->with(['failed' => 'Gagal menghapus data user']);
        }
    }
}
