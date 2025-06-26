<?php

namespace App\Http\Controllers;

use App\Models\DataKelainan;
use App\Models\kelainan;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;

class KelainanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index()
    {
        $params = [
            'title' => 'Data kelainan',
        ];
        return view('dashboard.master.kelainan.index', compact('params'));
    }

    public function add()
    {
        $data['role_level'] = [
            ['id' => 1, 'name' => 'kelainan'],
            ['id' => 2, 'name' => 'Guru'],
            ['id' => 3, 'name' => 'Siswa']
        ];

        return response()->json($data);
    }

    public function edit($id)
    {
        $kelainan = DataKelainan::findOrFail($id);
        return response()->json($kelainan);
    }

    public function destroy($id)
    {
        $data = DataKelainan::findOrFail($id);
        $data->delete();

        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus']);
    }

    public function getDatatables(Request $request) {
        if ($request->ajax()) {
            $users = DataKelainan::select(['id', 'nm_kelainan', 'desc_kelainan']);

            return DataTables::of($users)
                ->addIndexColumn()

                ->addColumn('action', function ($row) {
                    return '<a class="btn btn-primary btn-xs m-r-5 edit" data-id="'.$row->id.'"><i class="fa fa-edit"></i></a>
                        <a class="btn btn-danger btn-xs m-r-5 delete" data-id="'.$row->id.'"><i class="fa fa-trash"></i></a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'nm_kelainan' => 'required|string|max:50',
        ]);

        DataKelainan::create([
            'nm_kelainan' => $request->nm_kelainan,
            'desc_kelainan' => $request->desc_kelainan,
            'created_at' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Data berhasil ditambahkan']);
    }

//hkjhkhk

}
