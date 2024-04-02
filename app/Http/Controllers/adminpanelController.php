<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class adminpanelController extends Controller
{
    //
    public function dashboard()
    {


        return view('Admin.userqcpanel', []);
    }

    public function listqc(Request $request)
    {
        $nama = $request->input('user_name');
        $lokasi = $request->input('lok');

        // $lokasi = 'Regional IV';


        $queryEste = DB::connection('mysql2')->table('estate')
            ->select('estate.*')
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->join('reg', 'reg.id', '=', 'wil.regional')
            ->where('reg.nama', $lokasi)
            ->get();


        $list_qc = DB::table('pengguna')
            ->select('pengguna.*')
            ->where('pengguna.lokasi_kerja', '=', $lokasi)
            ->where('departemen', '=', 'QC')
            ->get();

        $arrView = array();
        $arrView['list_qc'] =  $list_qc;

        // dd($sidak_tph);
        echo json_encode($arrView);
        exit();
    }

    public function updateUserqc(Request $request)
    {
        $actionType = $request->input('actionType');

        // dd($actionType);
        switch ($actionType) {
            case 'update':
                $id = $request->input('id');
                $email = $request->input('email');
                $password = $request->input('password');
                $nama_lengkap = $request->input('nama_lengkap');
                $no_hp = $request->input('no_hp');

                // dd($no_hp);
                if ($no_hp == null) {
                    $new_hp = '';
                } else {
                    $new_hp = $no_hp;
                }
                $jabatan = $request->input('jabatan');
                $newjbtan = '';
                if ($jabatan == 'Kosong') {
                    $newjbtan = '';
                } else {
                    $newjbtan = $jabatan;
                }
                DB::table('pengguna')->where('user_id', $id)->update([
                    'email' => $email,
                    'password' => $password,
                    'nama_lengkap' => $nama_lengkap,
                    'no_hp' => $new_hp,
                    'jabatan' => $newjbtan,
                ]);
                break;
            case 'delete':
                $id_delete = $request->input('id_delete');
                DB::table('pengguna')->where('user_id', $id_delete)->delete();
                break;
            case 'add':
                $emailValue = $request->input('emailValue');
                $passwordValue = $request->input('passwordValue');
                $namaLengkapValue = $request->input('namaLengkapValue');
                $nomorHP_input = '';
                $jabatan_input = $request->input('jabatan_input');
                $lokasi = $request->input('lokasi');
                $statusAkun = $request->input('statusAkun');

                // dd($statusAkun);
                $newjbtanInput = '';
                if ($jabatan_input == 'Kosong') {
                    $newjbtanInput = '';
                } else {
                    $newjbtanInput = $jabatan_input;
                }

                // dd($newjbtanInput);
                DB::table('pengguna')->insert([
                    'email' => $emailValue,
                    'password' => $passwordValue,
                    'nama_lengkap' => $namaLengkapValue,
                    'no_hp' => $nomorHP_input,
                    'jabatan' => $newjbtanInput,
                    'departemen' => 'QC',
                    'afdeling' => '',
                    'status_akun' => $statusAkun,
                    'nama_perusahaan' => '',
                    'akses_level' => 1,
                    'lokasi_kerja' => $lokasi
                ]);
                break;

            default:

                break;
        }
    }
}
