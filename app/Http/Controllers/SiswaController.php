<?php

namespace App\Http\Controllers;

use App\Models\DataKelainan;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Datatables;
use Illuminate\Support\Facades\DB;

class SiswaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index()
    {
        $data = DB::table('siswas')
            ->join('jurusans', 'siswas.kd_jurusan', '=', 'jurusans.kd_jurusan')
            ->select('siswas.*', 'jurusans.nm_jurusan')->get();
        if (request()->ajax()) {
            return datatables()->of($data)
                ->addIndexColumn()
                ->make(true);
        }

        $params = [
            'title' => 'Data Siswa',
        ];
        return view('dashboard.pengguna.siswa.index', compact('params'));
    }

    public function getDatatables(Request $request){
        if ($request->ajax()) {
            $users = User::select(['users.id', 'first_name', 'last_name', 'nm_kelainan', 'no_hp', 'nisn'])
                    ->join('siswas', 'siswas.id_user', '=', 'users.id')
                    ->join('data_kelainans', 'data_kelainans.id', '=', 'siswas.id_kelainan');

            return \Yajra\DataTables\DataTables::of($users)
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

    public function add() {
        $kelainan = DataKelainan::select('id', 'nm_kelainan')->get();

        $data = [
            'kelainan' => $kelainan,
        ];

        return response()->json($data);return view('dashboard.pengguna.siswa.add');
    }
}
