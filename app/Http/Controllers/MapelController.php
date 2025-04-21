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

        $params = [
            'title' => 'Mata Pelajaran',
        ];
        return view('dashboard.master.mapel.index', compact('data'));
    }

    public function getDatatables(Request $request) {
        if ($request->ajax()) {
            $mapel = Mapel::select(['id', 'kelas.nm_kelas', 'nm_mapel'])->join('kelas', 'mapels.id_kelas', '=', 'kelas.id_kelas');

            return DataTables::of($mapel)
                ->addIndexColumn()
//                ->editColumn('level', function ($row) {
//                    switch ($row->level) {
//                        case 1: return 'Kepala Sekolah';
//                        case 2: return 'Guru';
//                        case 3: return 'Siswa';
//                        default: return '-';
//                    }
//                })
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
        $data['wali_kelas'] =  DB::table('gurus')
            ->select([
                'id_guru',
                'nm_guru'
            ])->get();
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'kd_kelas' => 'required|unique:kelas,kd_kelas',
            'wali_kelas' => 'required',
            'nm_kelas' => 'required',
            'stts_kelas' => 'required'
        ], [
            'kd_kelas.required' => 'Silahkan isi kode kelas terlebih dahulu!',
            'kd_kelas.unique'   => 'Kode kelas telah digunakan!',
            'wali_kelas.required'   => 'Guru wajib diisi!',
            'nm_kelas.required'   => 'Silahkan isi nama kelas terlebih dahulu!',
            'stts_kelas.required'   => 'Silahkan pilih status terlebih dahulu!',
        ]);

        //create post
        Kelas::create([
            'id_guru'     => $request->wali_kelas,
            'kd_kelas'     => $request->kd_kelas,
            'nm_kelas'     => $request->nm_kelas,
            'stts_kelas'      => $request->stts_kelas
        ]);

        //redirect to index
        return response()->json(['success' => 'Kelas successfully added!']);
    }

    public function edit($id)
    {
        $data['item'] = DB::table('kelas')
            ->join('gurus', 'kelas.id_guru', '=', 'gurus.id_guru')
            ->join('ruangans', 'kelas.kd_ruangan', '=', 'ruangans.kd_ruangan')
            ->join('gedungs', 'ruangans.kd_gedung', '=', 'gedungs.kd_gedung')
            ->where('id_kelas', '=', $id)
            ->select('kelas.*', 'gurus.nm_guru', 'gurus.nip', 'gurus.foto', 'jurusans.nm_jurusan', 'ruangans.nm_ruangan', 'gedungs.nm_gedung',)
            ->get();
        $data['wali_kelas'] =  DB::table('gurus')
            ->select([
                'id_guru',
                'nm_guru'
            ])->get();

        $data['nm_ruangan'] = DB::table('ruangans')
            ->select([
                'kd_ruangan',
                'nm_ruangan'
            ])->get();
        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'kd_kelas' =>
                'required|unique:kelas,kd_kelas,' . $id . ',id_kelas',
            'nm_kelas' => 'required',
            'nip' => 'required',
            'kd_jurusan' => 'required',
            'kd_ruangan' => 'required',
            'stts_kelas' => 'required'
        ], [
            'kd_kelas.required' => 'Silahkan isi kode kelas terlebih dahulu!',
            'kd_kelas.unique'   => 'Kode kelas telah digunakan!',
            'nm_kelas.required'   => 'Silahkan isi nama kelas terlebih dahulu!',
            'stts_kelas.required'   => 'Silahkan pilih status terlebih dahulu!',
        ]);

        $data = Kelas::find($id);
        $data->kd_kelas = $request->kd_kelas;
        $data->nm_kelas = $request->nm_kelas;
        $data->nip = $request->nip;
        $data->stts_kelas = $request->stts_kelas;
        $data->update();
        return response()->json(['success' => 'Kelas successfully updated!']);
    }

    public function destroy($id)
    {
        Kelas::find($id)->delete();
        return redirect()->route('kelas')->with(['success' => 'Kelas successfully deleted!']);
    }
}
