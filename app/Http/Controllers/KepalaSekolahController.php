<?php

namespace App\Http\Controllers;

use App\Exports\GuruExport;
use App\Exports\KepsekExport;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Datatables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class KepalaSekolahController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index()
    {
        $params = [
            'title' => 'Data Kepala Sekolah',
        ];
        return view('dashboard.pengguna.kepala_sekolah.index', compact('params'));
    }

    public function getDatatables(){
        $data = DB::table('gurus')->where('level_guru', 1)->get();
        if (request()->ajax()) {
            return datatables()->of($data)
                ->addColumn('action', function ($row) {
                    return '<a class="btn btn-info btn-xs m-r-5" data-id="'.$row->id_guru.'"><i class="fa fa-eye"></i></a>
                        <a class="btn btn-primary btn-xs m-r-5" data-id="'.$row->id_guru.'"><i class="fa fa-edit"></i></a>
                        <a class="btn btn-danger btn-xs" data-id="'.$row->id_guru.'"><i class="fa fa-trash"></i></a>';
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function store(Request $request) {
        $request->validate([
            // Identitas dasar
            'first_name'     => 'required|string|max:50',
            'last_name'      => 'required|string|max:50',
            'nip'            => 'required|numeric|digits_between:10,20|unique:gurus,nik',
            'nik'           => 'required|numeric|digits_between:10,20|unique:gurus,nik',
            'npwp'           => 'required|numeric|digits_between:10,20|unique:gurus,npwp',
            'email'          => 'required|email|unique:users,email',
            'password'       => 'required|string|min:6',

            // Biodata
            'jenkel'         => 'required|in:Laki - Laki,Perempuan',
            'tmpt_lahir'     => 'required|string|max:100',
            'tgl_lahir'      => 'required|date',
            'agama'          => 'required|string|max:30',

            // Kontak dan sekolah
            'almt_jalan'     => 'required|string|max:255',
            'no_hp'          => 'required|string|max:15',

        ]);

        $nm_guru = $request->first_name . ' ' . $request->last_name;

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'level' => 1,
            'is_admin' => 0,
            'is_active' => 1,
        ]);

        // Simpan ke tabel gurus jika user berhasil dibuat
        if ($user) {
            Guru::create([
                'id_user'       => $user->id,
                'nm_guru'       => $nm_guru,
                'nip'            => $request->nip,
                'nik'           => $request->nik,
                'npwp'           => $request->npwp,
                'jenkel'         => $request->jenkel,
                'tmpt_lahir'     => $request->tmpt_lahir,
                'tgl_lahir'      => $request->tgl_lahir,
                'agama'          => $request->agama,
                'almt_jalan'     => $request->almt_jalan,
                'no_hp'          => $request->no_hp,
                'level_guru'     => 1,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => null,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'User berhasil ditambahkan']);
    }

    public function export()
    {
        $timestamp = Carbon::now()->format('Ymd_His');
        $filename = "data_guru_{$timestamp}.xlsx";

        return Excel::download(new KepsekExport(), $filename);
    }
}
