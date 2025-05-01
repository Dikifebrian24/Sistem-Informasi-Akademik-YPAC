<?php

namespace App\Http\Controllers;

use App\Models\DataKelainan;
use App\Models\Jadwal;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;

class JadwalController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index()
    {
        $data_guru = DB::table('users')->where('level', 2)->get();
        if (request()->ajax()) {
            return datatables()->of($data_guru)
                ->addIndexColumn()
                ->make(true);
        }

        $params = [
            'title' => 'Jadwal',
            'kelas' => Kelas::all(),
            'guru' => $data_guru,
        ];
        return view('dashboard.master.jadwal.index', compact('params'));
    }

    public function getDatatables(Request $request)
    {
        if ($request->ajax()) {
            $data_kelas = Kelas::select(['id_kelas', 'gurus.id_guru as guru_id','nm_kelas'])
                ->join('gurus', 'gurus.id_guru', '=', 'kelas.id_guru')
                ->where('stts_kelas', 'Active')->get();

            return DataTables::of($data_kelas)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<button class="btn btn-sm btn-danger show" data-id="'.$row->id_kelas.'">Lihat</button>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function getJadwal(Request $request)
    {
        $idKelas = $request->input('id_kelas');

        if ($request->ajax()) {
            $query = Jadwal::where('id_kelas', $request->id_kelas);

            return DataTables::of($query)
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function add()
    {
        $data['role_level'] = [
            ['id' => 1, 'name' => 'Admin'],
            ['id' => 2, 'name' => 'Guru'],
            ['id' => 3, 'name' => 'Siswa']
        ];

        return response()->json($data);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['success' => true, 'message' => 'User berhasil dihapus']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role_level' => 'required|in:1,2,3' // sesuai role id
        ]);

        if ($request->role_level == 1){
            $is_admin = 1;
        } else {
            $is_admin = 0;
        }

        User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'level' => $request->role_level,
            'is_admin' => $is_admin
        ]);

        return response()->json(['success' => true, 'message' => 'User berhasil ditambahkan']);
    }

//hkjhkhk

}
