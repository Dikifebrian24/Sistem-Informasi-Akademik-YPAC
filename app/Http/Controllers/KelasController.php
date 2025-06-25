<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class KelasController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index()
    {
        $data = DB::table('kelas')
            ->leftjoin('gurus', 'kelas.id_guru', '=', 'gurus.id_guru')
            ->select('kelas.*', 'gurus.nm_guru')
            ->get();

        $params = [
            'title' => 'Kelas',
            'guru' => Guru::all(),
        ];
        return view('dashboard.master.kelas.index', compact('params', 'data'));
    }

    public function hapusSiswa($id_kelas, $id_siswa)
    {
        DB::table('kelas_siswa')
            ->where('id_kelas', $id_kelas)
            ->where('id_siswa', $id_siswa)
            ->delete();

        return redirect()->back()->with('success', 'Siswa berhasil dihapus dari kelas.');
    }
    public function getDatatables(Request $request)
    {
        if ($request->ajax()) {
            $kelas = Kelas::select(['id_kelas', 'kd_kelas', 'nm_kelas', 'nm_guru', 'stts_kelas'])
                ->leftjoin('gurus', 'kelas.id_guru', '=', 'gurus.id_guru')
                ->where('stts_kelas', 'Active')->get();

            return DataTables::of($kelas)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<button class="btn btn-sm btn-warning edit" data-id="'.$row->id_kelas.'">Edit</button>
                        <button class="btn btn-sm btn-danger delete" data-id="'.$row->id_kelas.'">Delete</button>';
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
