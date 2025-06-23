<?php

namespace App\Http\Controllers;

use App\Models\Mapel;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class MapelController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index()
    {
        $data = DB::table('kelas')
            ->join('gurus', 'kelas.id_guru', '=', 'gurus.id_guru')
            ->select('kelas.*', 'gurus.nm_guru')
            ->get();

        $kelas = Kelas::all();

        $params = [
            'title' => 'Mata Pelajaran',
            'kelas' => $kelas,
        ];
        return view('dashboard.master.mapel.index', compact('data', 'params'));
    }

    public function getDatatables(Request $request) {
        if ($request->ajax()) {
            $mapel = Mapel::select(['id', 'kelas.nm_kelas', 'nm_mapel'])->join('kelas', 'mapels.id_kelas', '=', 'kelas.id_kelas');

            return DataTables::of($mapel)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<button class="btn btn-sm btn-warning edit" data-id="'.$row->id.'">Edit</button>
                        <button class="btn btn-sm btn-danger delete" data-id="'.$row->id.'">Delete</button>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function detail($id)
    {
        $data = DB::table('kelas')
            ->join('gurus', 'kelas.id_guru', '=', 'gurus.id_guru')
            ->where('id_kelas', '=', $id)
            ->select('kelas.*', 'gurus.nm_guru', 'gurus.foto', )
            ->get();
        return response()->json($data);
    }

    public function add()
    {
        $data['kelas'] =  DB::table('kelas')
            ->select([
                'id_kelas',
                'nm_kelas'
            ])->get();
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nm_mapel' => 'required',
            'kelas' => 'required',

        ], [
            'nm_mapel.required' => 'Silahkan isi Nama Mapel terlebih dahulu!',
            'kelas.required'   => 'Kode kelas Wajib Di isi!',
        ]);

        //create post
        Mapel::create([
            'id_kelas'     => $request->kelas,
            'nm_mapel'     => $request->nm_mapel,
        ]);

        //redirect to index
        return response()->json(['success' => 'Kelas successfully added!']);
    }

    public function edit($id)
    {
        $mapel = Mapel::findOrFail($id);
        return response()->json($mapel);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nm_mapel' => 'required|string',
            'id_kelas' => 'required|exists:kelas,id_kelas',
        ]);

        $mapel = Mapel::findOrFail($id);
        $mapel->nm_mapel = $request->nm_mapel;
        $mapel->id_kelas = $request->id_kelas;
        $mapel->save();

        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function getKelas()
    {
        return response()->json(Kelas::all());
    }

    public function destroy($id)
    {
        try {
            $mapel = Mapel::findOrFail($id);
            $mapel->delete();

            return response()->json(['message' => 'Data berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menghapus data.'], 500);
        }
    }
}
