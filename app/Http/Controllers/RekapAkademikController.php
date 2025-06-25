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

    public function progressRekap()
    {

        $params = [
            'title' => 'Rekap Progress Akademik',
        ];
        return view('dashboard.akademik.rekap_akademik.progress', compact('params'));
    }

    public function getDatatablesNilai(Request $request)
    {
        if ($request->ajax()) {
            $id_user = Auth::user()->id;
//            $kelas = DB::table('mapels')
//                ->select('mapels.nm_mapel', 'kelas.nm_kelas', 'mapels.id as id_mapel', 'kelas.id_kelas as id_kelas')
//                ->join('kelas', 'kelas.id_kelas', '=', 'mapels.id_kelas')
//                ->join('kelas_siswa', 'kelas_siswa.id_kelas', '=', 'kelas.id_kelas')
//                ->join('siswas','kelas_siswa.id_siswa','=','siswas.id_siswa')
//                ->join('users','users.id','=','siswas.id_user')
//                ->join('jadwals', 'jadwals.id_mapel', '=', 'mapels.id')
//                ->where('users.id', $id_user)
//                ->get();

//            dd($kelas);



            $kelas = DB::table('jadwals')
                ->join('gurus', 'gurus.id_guru', '=', 'jadwals.id_guru')
                ->join('mapels', 'mapels.id', '=', 'jadwals.id_mapel')
                ->join('kelas', 'kelas.id_kelas', '=', 'jadwals.id_kelas')
//                ->where('gurus.id_user', '=', Auth::user()->id)
                ->select(
                    'mapels.id as id_mapel',
                    'mapels.nm_mapel'
                )
                ->groupBy('mapels.id', 'mapels.nm_mapel', 'jadwals.id_kelas')
                ->get();

//            dd($kelas);
            return DataTables::of($kelas)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($id_user) {
                    return '<button class="btn btn-sm btn-primary" data-id_mapel="'. $row->id_mapel.'" data-id_user="'.$id_user.'" id="get_detail_nilai">Lihat</button>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function getDatatablesNilaiProgress(Request $request)
    {
        if ($request->ajax()) {
            $id_user = Auth::user()->id;
//            $kelas = DB::table('mapels')
//                ->select('mapels.nm_mapel', 'kelas.nm_kelas', 'mapels.id as id_mapel', 'kelas.id_kelas as id_kelas')
//                ->join('kelas', 'kelas.id_kelas', '=', 'mapels.id_kelas')
//                ->join('kelas_siswa', 'kelas_siswa.id_kelas', '=', 'kelas.id_kelas')
//                ->join('siswas','kelas_siswa.id_siswa','=','siswas.id_siswa')
//                ->join('users','users.id','=','siswas.id_user')
//                ->join('jadwals', 'jadwals.id_mapel', '=', 'mapels.id')
//                ->where('users.id', $id_user)
//                ->get();

//            dd($kelas);



            $kelas = DB::table('jadwals')
                ->join('gurus', 'gurus.id_guru', '=', 'jadwals.id_guru')
                ->join('mapels', 'mapels.id', '=', 'jadwals.id_mapel')
                ->join('kelas', 'kelas.id_kelas', '=', 'jadwals.id_kelas')
//                ->where('gurus.id_user', '=', Auth::user()->id)
                ->select(
                    'mapels.id as id_mapel',
                    'mapels.nm_mapel'
                )
                ->groupBy('mapels.id', 'mapels.nm_mapel', 'jadwals.id_kelas')
                ->get();

//            dd($kelas);
            return DataTables::of($kelas)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($id_user) {
                    return '<button class="btn btn-sm btn-primary" data-id_mapel="'. $row->id_mapel.'" data-id_user="'.$id_user.'" id="get_detail_nilai_progress">Lihat</button>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }


    public function get_detail_nilai(Request $request)
    {
        $id_mapel = $request->id_mapel;
        $id_user = Auth::user()->id;
        $id_kelas = $request->id_kelas;

        $id_siswa = DB::table('siswas')
            ->join('users', 'users.id', '=', 'siswas.id_user')
            ->where('users.id', $id_user)
            ->get()->first();

        $nilai = DB::table('nilais')
            ->join('mapels', 'mapels.id', '=', 'nilais.id_mapel')
            ->join('siswas', 'siswas.id_siswa', '=', 'nilais.id_siswa')
            ->join('users', 'users.id', '=', 'siswas.id_user')
            ->where('nilais.id_mapel', $id_mapel)
            ->where('nilais.id_siswa', '=', "$id_siswa->id_siswa")
//            ->where('nilais.id_kelas', '=', "$id_kelas")
            ->select('nilais.*', 'kategori_nilai', 'siswas.nm_siswa as nama_siswa')
            ->get();

//        dd($nilai);

        // Kirim ke view baru
        return view('dashboard.akademik.rekap_akademik.detail_nilai', compact('nilai'))->render();
    }


    public function get_detail_progress(Request $request)
    {
        $id_mapel = $request->id_mapel;
        $id_user = Auth::user()->id;
        $id_kelas = $request->id_kelas;

        $id_siswa = DB::table('siswas')
            ->join('users', 'users.id', '=', 'siswas.id_user')
            ->where('users.id', $id_user)
            ->get()->first();

        $nilai = DB::table('progress_nilais')
            ->join('mapels', 'mapels.id', '=', 'progress_nilais.id_mapel')
            ->join('siswas', 'siswas.id_siswa', '=', 'progress_nilais.id_siswa')
            ->join('users', 'users.id', '=', 'siswas.id_user')
            ->where('progress_nilais.id_mapel', $id_mapel)
            ->where('progress_nilais.id_siswa', '=', "$id_siswa->id_siswa")
//            ->where('progress_nilais.id_kelas', '=', "$id_kelas")
            ->select('progress_nilais.*', 'siswas.nm_siswa as nama_siswa')
            ->get();

        $data = DB::table('progress_nilais')
            ->where('id_mapel', $id_mapel)
            ->where('id_siswa', '=', $id_siswa->id_siswa)
            ->orderBy('tgl_progress', 'asc')
            ->get();

        // Kirim ke view baru
        return view('dashboard.akademik.rekap_akademik.detail_progress', compact('nilai', 'data'));
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
