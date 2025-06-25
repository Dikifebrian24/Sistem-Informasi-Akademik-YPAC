<?php

namespace App\Http\Controllers;

use App\Exports\SiswaExport;
use App\Imports\GuruImport;
use App\Imports\SiswaImport;
use App\Models\DataKelainan;
use App\Models\Siswa;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Datatables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class SiswaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index()
    {
        $kelainan = DataKelainan::all();

        $params = [
            'title' => 'Data Siswa',
            'kelainan' => $kelainan,
        ];
        return view('dashboard.pengguna.siswa.index', compact('params'));
    }

    public function getDatatables(Request $request)
    {
        if ($request->ajax()) {
            $users = User::select(['users.id', 'first_name', 'last_name', 'nm_kelainan', 'no_hp', 'nisn', 'angkatan'])
                ->join('siswas', 'siswas.id_user', '=', 'users.id')
                ->join('data_kelainans', 'data_kelainans.id', '=', 'siswas.id_kelainan');

//            dd($users);

            return \Yajra\DataTables\DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('nama', function ($row) {
                    return $row->first_name . ' ' . $row->last_name;
                })
                ->editColumn('level', function ($row) {
                    switch ($row->level) {
                        case 1:
                            return 'Kepala Sekolah';
                        case 2:
                            return 'Guru';
                        case 3:
                            return 'Siswa';
                        default:
                            return '-';
                    }
                })
                ->addColumn('action', function ($row) {
//                    return '<button class="btn btn-primary btn-xs m-r-5 edit" data-id="' . $row->id . '"><i class="fa fa-edit"></button>
//                        <button class="btn btn-danger btn-xs delete" data-id="' . $row->id . '"><i class="fa fa-trash"></i></button>';

                    return '<a class="btn btn-primary btn-xs m-r-5 edit" data-id="' . $row->id . '"><i class="fa fa-edit"></i></a>
                        <a class="btn btn-danger btn-xs delete" data-id="' . $row->id . '"><i class="fa fa-trash"></i></a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'import_data' => 'required|mimes:xlsx,xls',
        ]);

        Excel::import(new SiswaImport(), $request->file('import_data'));

        return response()->json(['message' => 'Import berhasil!']);
    }


    public function export()
    {
        $timestamp = Carbon::now()->format('Ymd_His');
        $filename = "data_siswa_{$timestamp}.xlsx";

        return Excel::download(new SiswaExport, $filename);
    }

    public function store(Request $request)
    {
        $request->validate([
            // Identitas dasar
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'nik' => 'required|numeric|digits_between:10,20|unique:siswas,nik',
            'nisn' => 'required|numeric|digits_between:10,20|unique:siswas,nisn',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',

            // Biodata
            'kelainan' => 'required|exists:data_kelainans,id',
            'jenkel' => 'required|in:Laki - Laki,Perempuan',
            'tmpt_lahir' => 'required|string|max:100',
            'tgl_lahir' => 'required|date',
            'agama' => 'required|string|max:30',

            // Kontak dan sekolah
            'almt_rumah' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15',
            'angkatan' => 'required|numeric',

            // Data wali
            'nm_wali' => 'nullable|string|max:100',
            'tgl_lahir_wali' => 'nullable|date',
            'no_telp_wali' => 'nullable|string|max:15',
        ]);

        $nm_siswa = $request->first_name . ' ' . $request->last_name;

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'email_verified_at' => date("Y-m-d H:i:s"),
            'password' => Hash::make($request->password),
            'level' => 3,
            'is_admin' => 0,
            'is_active' => 1,
        ]);

        // Simpan ke tabel siswas jika user berhasil dibuat
        if ($user) {
            Siswa::create([
                'id_user' => $user->id,
                'id_kelainan' => $request->kelainan,
                'nm_siswa' => $nm_siswa,
                'nik' => $request->nik,
                'nisn' => $request->nisn,
                'email' => $request->email,
                'jenkel' => $request->jenkel,
                'tmpt_lahir' => $request->tmpt_lahir,
                'tgl_lahir' => $request->tgl_lahir,
                'agama' => $request->agama,
                'almt_rumah' => $request->almt_rumah,
                'no_hp' => $request->no_hp,
                'angkatan' => $request->angkatan,
                'nm_wali' => $request->nm_wali,
                'tgl_lahir_wali' => $request->tgl_lahir_wali,
                'no_telp_wali' => $request->no_telp_wali,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'User berhasil ditambahkan']);
    }

    public function kelas_siswa()
    {
        $params = [
            'title' => 'Data Bimbingan Siswa'
        ];
        return view('dashboard.akademik.kelas_siswa.index', compact('params'));
    }

    public function getDataSiswaAjar(Request $request)
    {
        if ($request->ajax()) {
            $kelas_siswa = DB::table('kelas_siswa')
                ->join('kelas', 'kelas.id_kelas', '=', 'kelas_siswa.id_kelas')
                ->join('siswas', 'siswas.id_siswa', '=', 'kelas_siswa.id_siswa')
                ->join('gurus', 'gurus.id_guru', '=', 'kelas.id_guru')
                ->select('siswas.id_siswa as id', 'siswas.nm_siswa as nama', 'siswas.jenkel', 'nm_kelas')
                ->where('gurus.id_user', '=', Auth::user()->id)->get();

            return \Yajra\DataTables\DataTables::of($kelas_siswa)
                ->addIndexColumn()
//                ->addColumn('nama', function ($row) {
//                    return $row->nm_siswa;
//                })
//                ->addColumn('jenkel', function ($row) {
//                    return $row->jenkel;
//                })
                ->make(true);
        }
    }

    public function edit($id)
    {

        $user = DB::table('siswas')
            ->join('users', 'siswas.id_user', '=', 'users.id')
            ->where('siswas.id_user', $id)->first();

//        dd($user);

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    public function update(Request $request, $id)
    {
        // Update data user

//        dd($request);
        $user = User::findOrFail($id);
        $user->first_name = $request->edit_first_name;
        $user->last_name = $request->edit_last_name;
        $user->email = $request->edit_email;
        $user->save();

        // Update data siswa
        $siswa = Siswa::where('id_user', $id)->first();
        if ($siswa) {
            $siswa->nik = $request->edit_nik;
            $siswa->nisn = $request->edit_nisn;
            $siswa->jenkel = $request->edit_jenkel;
            $siswa->tmpt_lahir = $request->edit_tmpt_lahir;
            $siswa->tgl_lahir = $request->edit_tgl_lahir;
            $siswa->agama = $request->edit_agama;
            $siswa->almt_rumah = $request->edit_almt_rumah;
            $siswa->angkatan = $request->edit_angkatan;
            $siswa->nm_wali = $request->edit_nm_wali;
            $siswa->tgl_lahir_wali = $request->edit_tgl_lahir_wali;
            $siswa->no_telp_wali = $request->edit_no_telp_wali;
            $siswa->id_kelainan = $request->edit_kelainan;
            $siswa->no_hp = $request->edit_no_hp;
            $siswa->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diupdate'
        ]);
    }

    public function destroy($id)
    {
        // Hapus data siswa terlebih dahulu
        $siswa = Siswa::where('id_user', $id)->first();

        $id_user = $siswa->id_user;
        if ($siswa) {
            $siswa->delete();
        }

        // Hapus data user
        $user = User::findOrFail($id_user);
        $user->delete();

        return response()->json(['success' => true, 'message' => 'User dan data siswa berhasil dihapus']);
    }


}
