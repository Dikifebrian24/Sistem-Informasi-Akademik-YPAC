<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class RekapAkademikController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index()
    {

        $params = [
            'title' => 'Rekap Nilai Akademik',
        ];
        return view('dashboard.akademik.rekap_akademik.nilai', compact('params'));
    }

    public function getDatatablesNilai(Request $request)
    {
        if ($request->ajax()) {
            $kelas = DB::table('mapels')
                ->select('mapels.nm_mapel', 'kelas.nm_kelas')
                ->join('kelas', 'kelas.id_kelas', '=', 'mapels.id_kelas')
                ->join('kelas_siswa', 'kelas_siswa.id_kelas', '=', 'kelas.id_kelas')
                ->join('siswas','kelas_siswa.id_siswa','=','siswas.id_siswa')
                ->join('users','users.id','=','siswas.id_user')
                ->where('users.id', Auth::user()->id)
                ->get();

//            dd($kelas);

            return DataTables::of($kelas)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<button class="btn btn-sm btn-warning edit">Edit</button>
                        <button class="btn btn-sm btn-danger delete">Delete</button>';
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
            ->select('kelas.*', 'gurus.nm_guru', 'gurus.foto')
            ->get();
        return response()->json($data);
    }

    public function add()
    {
        $data['wali_kelas'] = DB::table('gurus')
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
        ], [
            'kd_kelas.required' => 'Silahkan isi kode kelas terlebih dahulu!',
            'kd_kelas.unique' => 'Kode kelas telah digunakan!',
            'wali_kelas.required' => 'Guru wajib diisi!',
            'nm_kelas.required' => 'Silahkan isi nama kelas terlebih dahulu!',
        ]);

        //create post
        Kelas::create([
            'id_guru' => $request->wali_kelas,
            'kd_kelas' => $request->kd_kelas,
            'nm_kelas' => $request->nm_kelas,
            'stts_kelas' => 'Active'
        ]);

        //redirect to index
        return response()->json(['success' => 'Kelas successfully added!']);
    }

    public function edit($id)
    {
        $kelas = Kelas::findOrFail($id);
        return response()->json($kelas);
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
            'kd_kelas.unique' => 'Kode kelas telah digunakan!',
            'nm_kelas.required' => 'Silahkan isi nama kelas terlebih dahulu!',
            'stts_kelas.required' => 'Silahkan pilih status terlebih dahulu!',
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
