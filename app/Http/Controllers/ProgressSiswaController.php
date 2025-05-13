<?php

namespace App\Http\Controllers;

use App\Models\DataKelainan;
use App\Models\Jadwal;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Nilai;
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
            'title' => 'Nilai Siswa',
            'kelas' => Kelas::all(),
            'guru' => $data_guru,
            'mapel' => $mapel,
            'siswa' => $siswa,
        ];

        return view('dashboard.akademik.nilai.index', compact('params'));
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
            $mapel = DB::table('jadwals')
                ->join('gurus', 'gurus.id_guru', '=', 'jadwals.id_guru')
                ->join('mapels', 'mapels.id', '=', 'jadwals.id_mapel')
                ->join('kelas', 'kelas.id_kelas', '=', 'jadwals.id_kelas')
                ->where('gurus.id_user', '=', Auth::user()->id)
                ->select('gurus.nm_guru', 'mapels.nm_mapel', 'jadwals.id as id_jadwal', 'nm_kelas', 'jadwals.*')
                ->get();

            return DataTables::of($mapel)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<button class="btn btn-sm btn-danger" id="input_nilai" data-id_kelas="'.$row->id_kelas.'" data-id_mapel="'.$row->id_mapel.'" data-id="'.$row->id_jadwal.'">Input</button>';
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

        return view('dashboard.akademik.nilai.nilai', $data);
    }

    public function nilaiSave(Request $request)
    {
        $request->validate([
            'id_siswa' => 'required|exists:siswas,id_siswa',
            'id_mapel' => 'required|exists:mapels,id',
            'kategori_nilai' => 'required|in:Harian,UTS,UAS',
            'nilai_value' => 'required|integer|min:1|max:100',
            'lampiran' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        ]);

        $lampiran = null;
        if ($request->hasFile('lampiran')) {
            $lampiran = $request->file('lampiran')->store('lampiran_nilai', 'public');
        }

        Nilai::create([
            'id_siswa' => $request->id_siswa,
            'id_mapel' => $request->id_mapel,
            'kategori_nilai' => $request->kategori_nilai,
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
