<?php

namespace App\Http\Controllers;

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

    public function dataKelas(Request $request)
    {
        if ($request->ajax()) {
            $users = User::select(['id', 'first_name', 'last_name', 'email', 'level']);

            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('nama', function ($row) {
                    return $row->first_name . ' ' . $row->last_name;
                })
                ->editColumn('level', function ($row) {
                    switch ($row->level) {
                        case 1: return 'Kepala Sekolah';
                        case 2: return 'Guru';
                        case 3: return 'Siswa';
                        default: return '-';
                    }
                })
                ->addColumn('action', function ($row) {
                    return '<button class="btn btn-sm btn-warning edit" data-id="'.$row->id.'">Edit</button>
                        <button class="btn btn-sm btn-danger delete" data-id="'.$row->id.'">Delete</button>';
                })
                ->rawColumns(['action'])
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

    public function getDatatables(Request $request) {
        if ($request->ajax()) {
            $users = User::select(['id', 'first_name', 'last_name', 'email', 'level']);

            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('nama', function ($row) {
                    return $row->first_name . ' ' . $row->last_name;
                })
                ->editColumn('level', function ($row) {
                    switch ($row->level) {
                        case 1: return 'Kepala Sekolah';
                        case 2: return 'Guru';
                        case 3: return 'Siswa';
                        default: return '-';
                    }
                })
                ->addColumn('action', function ($row) {
                    return '<button class="btn btn-sm btn-warning edit" data-id="'.$row->id.'">Edit</button>
                        <button class="btn btn-sm btn-danger delete" data-id="'.$row->id.'">Delete</button>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
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
