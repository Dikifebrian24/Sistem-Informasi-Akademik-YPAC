<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Response;

class RaportController extends Controller
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

        $id_guru = DB::table('gurus')->where('id_user', Auth::user()->id)->first()->id_guru;

        $kelas = DB::table('kelas')->where('id_guru', $id_guru)->get();
        $params = [
            'title' => 'Kelas',
            'kelas' => $kelas,
        ];
        return view('dashboard.master.raport.raport', compact('params', 'data'));
    }

    public function filter(Request $request)
    {
        $id_kelas = $request->id_kelas;

        $data = DB::table('kelas_siswa')
            ->join('kelas', 'kelas_siswa.id_kelas', '=', 'kelas.id_kelas')
            ->join('siswas', 'siswas.id_siswa', '=', 'kelas_siswa.id_siswa')
            ->where('kelas_siswa.id_kelas', $id_kelas)
            ->get();

//        dd($data);

        return view('dashboard.master.raport._filter_kelas', compact('data'));
    }

    public function cetak($id)
    {
//        $siswa = Siswa::with('kelas', 'nilai', 'ekskul')->findOrFail($id);

        $nilai_harian = DB::table('nilais')
            ->join('jadwals', 'jadwals.id', '=', 'nilais.id_jadwal')
            ->join('mapels', 'mapels.id', '=', 'jadwals.id_mapel')
            ->select('mapels.nm_mapel', DB::raw('AVG(nilais.nilai) as avg'))
            ->where('nilais.id_siswa', $id)
            ->whereNotIn('jadwals.materi', ['UAS', 'UTS'])
            ->groupBy('mapels.nm_mapel')
            ->get();


//        dd($nilai_harian);

        $siswa = DB::table('siswas')
            ->where('id_siswa', $id)
            ->get()->first();

        // Jika ingin return view di browser:
        // return view('raport.template', compact('siswa'));

        // Untuk export ke Word
        $html = view('dashboard.master.raport.template', compact('siswa', 'nilai_harian'))->render();

        $filename = 'Raport_' . $siswa->nm_siswa . '.doc';

        return response($html)
            ->header('Content-Type', 'application/msword')
            ->header('Content-Disposition', "attachment; filename=$filename");
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
