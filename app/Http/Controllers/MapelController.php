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
        $data = DB::table('kelas_backup')
            ->join('gurus', 'kelas_backup.id_guru', '=', 'gurus.id_guru')
            ->select('kelas_backup.*', 'gurus.nm_guru')
            ->get();

        $params = [
            'title' => 'Mata Pelajaran',
        ];
        return view('dashboard.master.mapel.index', compact('data'));
    }

    public function getDatatables(Request $request) {
        if ($request->ajax()) {
            $mapel = Mapel::select(['id', 'kelas_backup.nm_kelas', 'nm_mapel'])->join('kelas_backup', 'mapels.id_kelas', '=', 'kelas_backup.id_kelas');

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
        $data = DB::table('kelas_backup')
            ->join('gurus', 'kelas_backup.id_guru', '=', 'gurus.id_guru')
            ->where('id_kelas', '=', $id)
            ->select('kelas_backup.*', 'gurus.nm_guru', 'gurus.foto', )
            ->get();
        return response()->json($data);
    }

    public function add()
    {
        $data['kelas_backup'] =  DB::table('kelas_backup')
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
            'kelas_backup' => 'required',

        ], [
            'nm_mapel.required' => 'Silahkan isi Nama Mapel terlebih dahulu!',
            'kelas_backup.required'   => 'Kode kelas_backup Wajib Di isi!',
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
        $data['item'] = DB::table('kelas_backup')
            ->join('gurus', 'kelas_backup.id_guru', '=', 'gurus.id_guru')
            ->join('ruangans', 'kelas_backup.kd_ruangan', '=', 'ruangans.kd_ruangan')
            ->join('gedungs', 'ruangans.kd_gedung', '=', 'gedungs.kd_gedung')
            ->where('id_kelas', '=', $id)
            ->select('kelas_backup.*', 'gurus.nm_guru', 'gurus.nip', 'gurus.foto', 'jurusans.nm_jurusan', 'ruangans.nm_ruangan', 'gedungs.nm_gedung',)
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
                'required|unique:kelas_backup,kd_kelas,' . $id . ',id_kelas',
            'nm_kelas' => 'required',
            'nip' => 'required',
            'kd_jurusan' => 'required',
            'kd_ruangan' => 'required',
            'stts_kelas' => 'required'
        ], [
            'kd_kelas.required' => 'Silahkan isi kode kelas_backup terlebih dahulu!',
            'kd_kelas.unique'   => 'Kode kelas_backup telah digunakan!',
            'nm_kelas.required'   => 'Silahkan isi nama kelas_backup terlebih dahulu!',
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
        Mapel::find($id)->delete();
        return redirect()->route('mapel')->with(['success' => 'Kelas successfully deleted!']);
    }
}
