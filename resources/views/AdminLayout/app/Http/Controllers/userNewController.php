<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class userNewController extends Controller
{
    public function showUser(Request $request)
    {
        // dd($queryUser);
        $query = DB::connection('mysql2')->table('asisten_qc')
            ->select('asisten_qc.*')
            ->get();

        $queryEst = DB::connection('mysql2')->table('estate')->whereIn('wil', [1, 2, 3])->get();
        $queryAfd = DB::connection('mysql2')->table('afdeling')->select('nama')->groupBy('nama')->get();
        return view('User.user', ['asisten' => $query, 'estate' => $queryEst, 'afdeling' => $queryAfd]);
    }

    public function getuser(Request $request)
    {
        $user_id = $request->input('user_id');

        $queryUser = DB::table('pengguna')
            ->where('user_id', $user_id)
            ->first();
        $queryUser = (array) $queryUser; // Cast stdClass object to an array
        // dd($queryUser);



        $arrView = array();
        $arrView['user'] = $queryUser;

        return response()->json($arrView);
    }

    public function update_user(Request $request)
    {
        $userqc = $request->input('user_id');
        $email = $request->input('email');
        $name = $request->input('name');
        $dept = $request->input('departemen');
        $afd = $request->input('afdeling');
        $no_hp = $request->input('nohp') ?? null;
        $pass = $request->input('pass');
        // Update the user in the database
        DB::table('pengguna')->where('user_id', $userqc)
            ->update([
                'nama_lengkap' => $name,
                'email' => $email,
                'departemen' => $dept,

                'password' => $pass,
            ]);

        return redirect()->back()->with('success', 'Profile updated successfully');
    }

    public function listAsisten2(Request $request)
    {
        $query = DB::connection('mysql2')->table('asisten_qc')
            ->select('asisten_qc.*')
            ->get();

        $queryEst = DB::connection('mysql2')->table('estate')->whereIn('wil', [1, 2, 3])->get();
        $queryAfd = DB::connection('mysql2')->table('afdeling')->select('nama')->groupBy('nama')->get();

        // return view('User.user');
        return view('User.user', ['asisten' => $query, 'estate' => $queryEst, 'afdeling' => $queryAfd]);
    }

    public function updateAsisten(Request $request)
    {
        // Get the asisten by ID
        $asisten = DB::connection('mysql2')->table('asisten_qc')->where('id', $request->input('id'))->first();

        if ($asisten) {
            // Update the asisten record
            DB::connection('mysql2')->table('asisten_qc')->where('id', $request->input('id'))->update([
                'nama' => $request->input('nama'),
                'est' => $request->input('est'),
                'afd' => $request->input('afd')
            ]);

            return redirect()->back()->with('success', 'Profile updated successfully');
        } else {
            return redirect()->back()->with('error', 'Asisten not found!');
        }
    }

    public function deleteAsisten(Request $request)
    {
        DB::connection('mysql2')->table('asisten_qc')->where('id', $request->input('id'))->delete();
        return redirect()->back()->with('success', 'Data asisten berhasil dihapus!');
    }

    public function storeAsisten(Request $request)
    {
        DB::connection('mysql2')->table('asisten_qc')->insert([
            'nama' => $request->input('nama'),
            'est' => $request->input('est'),
            'afd' => $request->input('afd')
        ]);

        return redirect()->back()->with('success', 'Asisten added successfully');
    }
}
