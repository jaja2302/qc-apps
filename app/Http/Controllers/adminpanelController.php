<?php

namespace App\Http\Controllers;

use App\Models\Asistenqc;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class adminpanelController extends Controller
{
    //
    public function dashboard()
    {
        $query = Asistenqc::with('User')->get();
        // dd($query);


        $queryEst = DB::connection('mysql2')->table('estate')->whereIn('wil', [1, 2, 3])->get();
        $queryAfd = DB::connection('mysql2')->table('afdeling')->select('nama')->groupBy('nama')->get();
        // $list_pengguna = Pengguna::query()->where('nama_lengkap', 'like', '%christian%')->get();

        // dd($list_pengguna);

        return view('Admin.userqcpanel', ['asisten' => $query, 'estate' => $queryEst, 'afdeling' => $queryAfd]);
    }
    public function searchUsers(Request $request)
    {
        $search = $request->input('term');
        // dd($search); // Debugging search input

        $users = Pengguna::query()
            ->where('nama_lengkap', 'LIKE', "%{$search}%")
            ->with('Jabatan', 'Departement')
            ->limit(20) // Limit the result to avoid loading too many users at once
            ->get();
        // dd($users);

        return response()->json($users);
    }

    public function listqc(Request $request)
    {
        $nama = $request->input('user_name');
        $lokasi = $request->input('lok');


        $list_qc = DB::table('pengguna')
            ->select('pengguna.*')
            // ->where('pengguna.lokasi_kerja', '=', $lokasi)
            ->where('email', 'like', '%QC%')
            ->orwhere('id_departement', 43)
            ->get();

        $lokexclaide = ['SULUNG RANCH', 'DEPT IT', 'QC', 'AGROSERVICES', 'MILL', 'Hortikultura', 'Perkebunan'];
        $list_gm = DB::table('pengguna')
            ->select('*')
            ->whereNotIn('pengguna.departemen', $lokexclaide)
            ->where('jabatan', 'manager')
            ->get();

        $list_asisten = DB::table('pengguna')
            ->select('*')
            ->whereNotIn('pengguna.departemen', $lokexclaide)
            ->whereIn('jabatan', ['Asisten', 'Asisten Afdeling'])
            ->get();
        // dd($list_asisten);

        $arrView = array();
        $arrView['list_qc'] =  $list_qc;
        $arrView['list_gm'] =  $list_gm;
        $arrView['list_asisten'] =  $list_asisten;

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
                } elseif ($jabatan == 'Asisafd') {
                    $newjbtan = 'Asisten Afdeling';
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
        $query = DB::table('asisten_qc')
            ->select('asisten_qc.*')
            ->get();

        $queryEst = DB::connection('mysql2')->table('estate')->whereIn('wil', [1, 2, 3])->get();
        $queryAfd = DB::connection('mysql2')->table('afdeling')->select('nama')->groupBy('nama')->get();

        // return view('User.user');
        return view('User.user', ['asisten' => $query, 'estate' => $queryEst, 'afdeling' => $queryAfd]);
    }

    public function updateAsisten(Request $request)
    {
        DB::connection('mysql2')->beginTransaction(); // Start the transaction

        try {
            // Get the asisten by ID
            $asisten = DB::table('asisten_qc')->where('id', $request->input('id'))->first();
            // dd($request->input('hidden_user_id_option'));
            // $user_id = $request->in
            if ($asisten) {
                // Update the asisten record
                DB::table('asisten_qc')->where('id', $request->input('id'))->update([
                    'user_id' => $request->input('hidden_user_id_option'),
                    'est' => $request->input('est'),
                    'afd' => $request->input('afd')
                ]);

                // Commit the transaction
                DB::connection('mysql2')->commit();

                return redirect()->back()->with('success', 'Profile updated successfully');
            } else {
                // Rollback the transaction if the asisten is not found
                DB::connection('mysql2')->rollBack();
                return redirect()->back()->with('error', 'Asisten not found!');
            }
        } catch (\Throwable $th) {
            // Rollback the transaction if an error occurs
            DB::connection('mysql2')->rollBack();

            // Log the exception

            // Return back with an error message
            return redirect()->back()->with('error', 'Failed to update profile. Please try again later.');
        }
    }

    public function deleteAsisten(Request $request)
    {
        DB::table('asisten_qc')->where('id', $request->input('id'))->delete();
        return redirect()->back()->with('success', 'Data asisten berhasil dihapus!');
    }

    public function storeAsisten(Request $request)
    {
        DB::table('asisten_qc')->insert([
            'user_id' => $request->input('nama'),
            'est' => $request->input('est'),
            'afd' => $request->input('afd')
        ]);

        return redirect()->back()->with('success', 'Asisten added successfully');
    }
}
