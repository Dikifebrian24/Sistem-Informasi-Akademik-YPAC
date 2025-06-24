<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Mapel;
use App\Models\Kelas;
use Dflydev\DotAccessData\Data;
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

    public function detail_mapel_kelas($id)
    {
        $id_kelas = $id;

        $kelas = DB::table('kelas')->where('id_kelas', $id)->first();


        $params = [
            'title' => 'Mapel',
            'id_kelas' => $id_kelas,
            'kelas' => $kelas->nm_kelas,
        ];

        return view('dashboard.master.mapel.detail', compact('params'));
    }

    public function getDatatables(Request $request) {
        if ($request->ajax()) {
            $mapel = DB::table("mapels")
                ->join('kelas', 'mapels.id_kelas', '=', 'kelas.id_kelas')
                ->where('mapels.id_kelas', $request->kelas)->get();

//            dd($mapel);


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

    public function getDatatablesKelas(Request $request)
    {
        if ($request->ajax()) {
            $data_kelas = Kelas::select([
                'kelas.id_kelas',
                'kelas.kd_kelas',
                'kelas.nm_kelas',
                'gurus.id_guru as guru_id'
            ])
                ->join('gurus', 'gurus.id_guru', '=', 'kelas.id_guru')
                ->where('kelas.stts_kelas', 'Active')
                ->get();

//            dd($data_kelas);

            return DataTables::of($data_kelas)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<button class="btn btn-sm btn-danger show" id="show" data-id="' . $row->id_kelas . '">Lihat</button>';
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

        ], [
            'nm_mapel.required' => 'Silahkan isi Nama Mapel terlebih dahulu!',
        ]);

        //create post
        Mapel::create([
            'id_kelas'     => $request->id_kelas,
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
