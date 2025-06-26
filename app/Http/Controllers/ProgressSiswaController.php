<?php

namespace App\Http\Controllers;

use App\Models\DataKelainan;
use App\Models\Jadwal;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Nilai;
use App\Models\ProgressNilai;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;

class ProgressSiswaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index()
    {
        $data_guru = DB::table('users')->where('level', 2)->get();
        if (request()->ajax()) {
            return datatables()->of($data_guru)
                ->addIndexColumn()
                ->make(true);
        }

        $mapel = Mapel::all();

        $siswa = Siswa::all();

        $params = [
            'title' => 'Data Progress Siswa',
            'kelas' => Kelas::all(),
            'guru' => $data_guru,
            'mapel' => $mapel,
            'siswa' => $siswa,
        ];

        return view('dashboard.akademik.progress.index', compact('params'));
    }

    public function getDatatables(Request $request)
    {
        if ($request->ajax()) {
            $data_kelas = Kelas::select(['id_kelas', 'gurus.id_guru as guru_id','nm_kelas'])
                ->join('gurus', 'gurus.id_guru', '=', 'kelas.id_guru')
                ->where('stts_kelas', 'Active')->get();

            return DataTables::of($data_kelas)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<button class="btn btn-sm btn-danger show" data-id="'.$row->id_kelas.'">Lihat</button>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function getMapelDatatables(Request $request)
    {
        if ($request->ajax()) {
//            $mapel = DB::table('jadwals')
//                ->join('gurus', 'gurus.id_guru', '=', 'jadwals.id_guru')
//                ->join('mapels', 'mapels.id', '=', 'jadwals.id_mapel')
//                ->join('kelas', 'kelas.id_kelas', '=', 'jadwals.id_kelas')
//                ->where('gurus.id_user', '=', Auth::user()->id)
//                ->select('gurus.nm_guru', 'mapels.nm_mapel', 'jadwals.id as id_jadwal', 'nm_kelas', 'jadwals.*')
//                ->get();
//
//            return DataTables::of($mapel)
//                ->addIndexColumn()
//                ->addColumn('action', function ($row) {
//                    return '<button class="btn btn-sm btn-danger" id="input_progress" data-id_kelas="'.$row->id_kelas.'" data-id_mapel="'.$row->id_mapel.'" data-id="'.$row->id_jadwal.'">Input</button>
//                            <button class="btn btn-sm btn-primary" id="show_progress" data-id_kelas="'.$row->id_kelas.'" data-id_mapel="'.$row->id_mapel.'" data-id="'.$row->id_jadwal.'">Lihat Nilai</button>';
//                })
//                ->rawColumns(['action'])
//                ->make(true);

            $mapel = DB::table('jadwals')
                ->join('gurus', 'gurus.id_guru', '=', 'jadwals.id_guru')
                ->join('mapels', 'mapels.id', '=', 'jadwals.id_mapel')
                ->join('kelas', 'kelas.id_kelas', '=', 'jadwals.id_kelas')
                ->where('gurus.id_user', '=', Auth::user()->id)
                ->select(
                    'mapels.id as id_mapel',
                    'mapels.nm_mapel',
                    DB::raw('MIN(jadwals.id) as id_jadwal'),
                    DB::raw('MIN(jadwals.tanggal) as tanggal'),
                    DB::raw('MIN(kelas.nm_kelas) as nm_kelas'),
                    DB::raw('MIN(gurus.nm_guru) as nm_guru'),
                    'jadwals.id_kelas'
                )
                ->groupBy('mapels.id', 'mapels.nm_mapel', 'jadwals.id_kelas')
                ->get();

//                dd($mapel);

            return DataTables::of($mapel)
                ->addIndexColumn()
                ->editColumn('jumlah_siswa', function ($row) {
                    $id_kelas = $row->id_kelas;

                    $jml_siswa = DB::table('kelas_siswa')->where('id_kelas', $id_kelas)->count();

                    return $jml_siswa;
                })
                ->addColumn('action', function ($row) {
                    return '<button class="btn btn-sm btn-danger" id="input_progress" data-id_kelas="'.$row->id_kelas.'" data-id_mapel="'.$row->id_mapel.'" data-id="'.$row->id_jadwal.'">Input</button>
                            <button class="btn btn-sm btn-primary" id="show_progress" data-id_kelas="'.$row->id_kelas.'" data-id_mapel="'.$row->id_mapel.'" data-id="'.$row->id_jadwal.'">Lihat Nilai</button>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function getJadwal(Request $request)
    {
        $idKelas = $request->input('id_kelas');

        if ($request->ajax()) {
            $query = Jadwal::where('id_kelas', $request->id_kelas);

            return DataTables::of($query)
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function show(Request $request)
    {
        $id_mapel = $request->id_mapel;
        $id_kelas = $request->id_kelas;
        $id_jadwal = $request->id_jadwal;

        $siswa = DB::table('kelas_siswa')
            ->join('siswas', 'siswas.id_siswa', '=', 'kelas_siswa.id_siswa')
            ->join('kelas', 'kelas.id_kelas', '=', 'kelas_siswa.id_kelas')
            ->where('kelas_siswa.id_kelas', $id_kelas)
            ->get();

        $mapel = Mapel::where('id', $id_mapel)->get()->first()->nm_mapel;
        $mapel_id = Mapel::where('id', $id_mapel)->get()->first()->id;

        $data = [
            'mapel' => $mapel,
            'id_mapel' => $id_mapel,
            'id_kelas' => $id_kelas,
            'id_jadwal' => $id_jadwal,
             'siswa' => $siswa,
        ];

        return view('dashboard.akademik.progress.progress', $data);
    }

    public function progressDetailShow(Request $request)
    {
        $id_mapel = $request->id_mapel;
        $id_kelas = $request->id_kelas;
        $id_jadwal = $request->id_jadwal;

        $siswa = DB::table('kelas_siswa')
            ->join('siswas', 'siswas.id_siswa', '=', 'kelas_siswa.id_siswa')
            ->join('kelas', 'kelas.id_kelas', '=', 'kelas_siswa.id_kelas')
            ->where('kelas_siswa.id_kelas', $id_kelas)
            ->get();

        $mapel = Mapel::where('id', $id_mapel)->get()->first()->nm_mapel;

        $data = [
            'mapel' => $mapel,
            'id_mapel' => $id_mapel,
            'id_kelas' => $id_kelas,
            'id_jadwal' => $id_jadwal,
            'siswa' => $siswa,
        ];

        return view('dashboard.akademik.progress.show', $data);
    }

    public function rekapProgress()
    {
        // Ambil semua data progress lengkap dengan relasi siswa & mapel
        $data = DB::table('progress_nilais')
            ->join('siswas', 'progress_nilais.id_siswa', '=', 'siswas.id_siswa')
            ->join('mapels', 'progress_nilais.id_mapel', '=', 'mapels.id')
            ->select('progress_nilais.*', 'siswas.nm_siswa as nama_siswa', 'mapels.nm_mapel as nama_mapel')
            ->orderBy('id', 'desc')
            ->get();

//        dd($data);

        return view('dashboard.akademik.progress.recap', compact('data'));
    }

    public function filter(Request $request)
    {
        $id_mapel = $request->id_mapel;
        $id_siswa = $request->id_siswa;

        $data = DB::table('progress_nilais')
            ->where('id_mapel', $id_mapel)
            ->where('id_siswa', $id_siswa)
            ->orderBy('tgl_progress', 'asc')
            ->get();

        return view('dashboard.akademik.progress._filter_progress', compact('data'));
    }

    public function progressSave(Request $request)
    {
        $request->validate([
            'id_siswa' => 'required|exists:siswas,id_siswa',
            'id_mapel' => 'required|exists:mapels,id',
            'tgl_progress' => 'required|date',
            'nilai_value' => 'required|integer|min:1|max:10', // 1–10 saja
            'desc_nilai' => 'nullable|string',
            'lampiran' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ]);

        $lampiran = null;
        if ($request->hasFile('lampiran')) {
            $lampiran = $request->file('lampiran')->store('lampiran_progress', 'public');
        }

        \App\Models\ProgressNilai::create([
            'id_siswa' => $request->id_siswa,
            'id_mapel' => $request->id_mapel,
            'tgl_progress' => $request->tgl_progress,
            'nilai' => $request->nilai_value,
            'desc_nilai' => $request->desc_nilai,
            'lampiran' => $lampiran,
        ]);

        return response()->json(['message' => 'Data nilai berhasil disimpan.']);
    }

    public function add()
    {
        $data['role_level'] = [
            ['id' => 1, 'name' => 'Admin'],
            ['id' => 2, 'name' => 'Guru'],
            ['id' => 3, 'name' => 'Siswa']
        ];

        return response()->json($data);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['success' => true, 'message' => 'User berhasil dihapus']);
    }

    public function store(Request $request)
    {
        // Validate the data
        $validated = $request->validate([
            'materi' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'mapel' => 'required|exists:mapels,id',
        ]);

        // Store the new jadwal in the database
        Jadwal::create([
            'id_mapel'  => $validated['mapel'],
            'materi' => $validated['materi'],
            'tanggal' => $validated['tanggal'],
            'waktu_mulai' => $validated['waktu_mulai'],
            'waktu_selesai' => $validated['waktu_selesai'],
            'id_kelas' => $validated['id_kelas'],
        ]);

        // Return response or redirect as needed
        return response()->json(['message' => 'Jadwal added successfully!']);
    }

//hkjhkhk

}
