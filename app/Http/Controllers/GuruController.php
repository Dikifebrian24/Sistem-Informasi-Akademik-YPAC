<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use Illuminate\Http\Request;
use Datatables;
use Illuminate\Support\Facades\DB;

class GuruController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index()
    {
        $params = [
            'title' => 'Guru',
        ];
        return view('dashboard.pengguna.guru.index', compact('params'));
    }

    public function getDatatables(){
        $data = DB::table('gurus')->get();
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

    public function add() {
        $data['role_level'] = [
            ['id' => 1, 'name' => 'Admin'],
            ['id' => 2, 'name' => 'Guru'],
            ['id' => 3, 'name' => 'Siswa']
        ];

        return response()->json($data);
    }
}
