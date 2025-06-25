<?php

namespace App\Http\Controllers;

use App\Exports\AdminExport;
use App\Exports\KepsekExport;
use App\Models\Admin;
use App\Models\Kelas;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index()
    {
        $params = [
            'title' => 'Data Admin',
        ];
        return view('dashboard.pengguna.admin.index', compact('params'));
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
            $users = User::select(['id', 'first_name', 'last_name', 'email', 'level'])->where('level', '1');

            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('nama', function ($row) {
                    return $row->first_name . ' ' . $row->last_name;
                })

                ->addColumn('action', function ($row) {
                    return '<a class="btn btn-primary btn-xs m-r-5 edit" data-id="'.$row->id.'"><i class="fa fa-edit"></i></a>
                        <a class="btn btn-danger btn-xs delete" data-id="'.$row->id.'"><i class="fa fa-trash"></i></a>';
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
            'email_verified_at' => date("Y-m-d H:i:s"),
            'level' => 1,
            'is_admin' => 1,
            'is_active' => 1
        ]);

        return response()->json(['success' => true, 'message' => 'User berhasil ditambahkan']);
    }

    public function export()
    {
        $timestamp = Carbon::now()->format('Ymd_His');
        $filename = "data_admin_{$timestamp}.xlsx";

        return Excel::download(new AdminExport(), $filename);
    }

//hkjhkhk

}
