<?php

namespace App\Http\Controllers;

use App\Exports\TemplateNilaiExport;
use App\Imports\NilaiImport;
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
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;

class NilaiSiswaController extends Controller
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

    public function laporan_nilai_index()
    {

        $id_guru = DB::table('gurus')->where('id_user', Auth::user()->id)->first()->id_guru;

        $kelas = DB::table('kelas')->where('id_guru', $id_guru)->get();

        $params = [
            'kelas' => $kelas,
            'title' => 'Nilai Siswa',
        ];
        return view('dashboard.akademik.nilai.laporan_nilai', compact('params'));
    }
    public function getNilaiData(Request $request)
    {
        if ($request->ajax()) {
            $data_nilai = DB::table('nilais')
                ->select('nilais.id as nilai_id', 'nilais.*', 'nm_siswa', 'jadwals.materi')
                ->join('mapels', 'mapels.id', '=', 'nilais.id_mapel')
                ->join('siswas', 'siswas.id_siswa', '=', 'nilais.id_siswa')
                ->join('jadwals', 'jadwals.id', '=', 'nilais.id_jadwal')
                ->get();

            return DataTables::of($data_nilai)
                ->addIndexColumn()
                ->editColumn('lampiran', function ($row) {
                    if (is_null($row->lampiran)) {
                        return 'Tidak ada lampiran';
                    } else {
                        return '<a href="' . asset('storage/' . $row->lampiran) . '" target="_blank">Lampiran</a>';
                    }
                })
                ->addColumn('action', function ($row) {
                    return '<button class="btn btn-sm btn-warning edit-btn"
                  data-id="' . $row->nilai_id . '"
                  data-nilai="' . $row->nilai . '"
                  data-siswa="' . $row->nm_siswa . '"
                  data-toggle="modal" data-target="#editModal">
                  Edit
            </button>
             <button class="btn btn-sm btn-danger delete-btn"
                data-id="' . $row->nilai_id . '">
                Hapus
             </button>';
                })
                ->rawColumns(['action', 'lampiran'])
                ->make(true);
        }
    }

    public function exportTemplate(Request $request, $idKelas)
    {
        $id_mapel = $request->query('id_mapel');
        $id_kelas = $idKelas;

        return Excel::download(new TemplateNilaiExport($id_kelas, $id_mapel), 'template_nilai_kelas_' . $id_kelas . '.xlsx');
    }

    public function importNilai(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file_import' => 'required|file|mimes:xlsx,xls',
            'id_kelas' => 'required|integer',
            'id_mapel' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $id_kelas = $request->input('id_kelas');
            $id_mapel = $request->input('id_mapel');

            Excel::import(new NilaiImport($id_kelas, $id_mapel), $request->file('file_import'));

            return response()->json([
                'status' => 'success',
                'message' => 'Import nilai berhasil.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Import gagal: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request)
    {

//        dd($request);
        $updated = DB::table('nilais')
            ->where('id', $request->nilai_id)
            ->update([
                'nilai' => $request->nilai,
                'updated_at' => now(),
            ]);

//        dd($updated);

        return response()->json(['success' => true, 'message' => 'Nilai berhasil diperbarui']);
    }
    public function getDatatables(Request $request)
    {
        if ($request->ajax()) {
            $data_kelas = Kelas::select(['id_kelas', 'gurus.id_guru as guru_id', 'nm_kelas'])
                ->join('gurus', 'gurus.id_guru', '=', 'kelas.id_guru')
                ->where('stts_kelas', 'Active')->get();

            return DataTables::of($data_kelas)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<button class="btn btn-sm btn-danger show" data-id="' . $row->id_kelas . '">Lihat</button>';
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

//            $mapel = DB::table('jadwals')
//                ->join('gurus', 'gurus.id_guru', '=', 'jadwals.id_guru')
//                ->join('mapels', 'mapels.id', '=', 'jadwals.id_mapel')
//                ->join('kelas', 'kelas.id_kelas', '=', 'jadwals.id_kelas')
//                ->where('gurus.id_user', '=', Auth::user()->id)
//                ->select(
//                    'mapels.id as id_mapel',
//                    'mapels.nm_mapel',
//                    DB::raw('MIN(jadwals.id) as id_jadwal'),
//                    DB::raw('MIN(jadwals.tanggal) as tanggal'),
//                    DB::raw('MIN(kelas.nm_kelas) as nm_kelas'),
//                    DB::raw('MIN(gurus.nm_guru) as nm_guru'),
//                    'jadwals.id_kelas'
//                )
//                ->groupBy('mapels.nm_mapel', 'mapels.id_kelas', 'jadwals.id')
//                ->get();

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
                    return '<button class="btn btn-sm btn-danger" id="input_nilai" data-id_kelas="' . $row->id_kelas . '" data-id_mapel="' . $row->id_mapel . '" data-id="' . $row->id_jadwal . '">Input</button>
                            <button class="btn btn-sm btn-primary" id="show_nilai" data-id_kelas="' . $row->id_kelas . '" data-id_mapel="' . $row->id_mapel . '" data-id="' . $row->id_jadwal . '">Lihat Nilai</button>';
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


        $jadwal = DB::table('jadwals')->where('id_mapel', $id_mapel)->get();

        $data = [
            'mapel' => $mapel,
            'id_mapel' => $id_mapel,
            'id_kelas' => $id_kelas,
            'id_jadwal' => $id_jadwal,
            'siswa' => $siswa,
            'jadwal' => $jadwal,
        ];

        return view('dashboard.akademik.nilai.nilai', $data);
    }

    public function nilaiDetailShow(Request $request)
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

        return view('dashboard.akademik.nilai.show', $data);
    }

    public function filter(Request $request)
    {
        $id_mapel = $request->id_mapel;
        $id_siswa = $request->id_siswa;

        $data = DB::table('nilais')
            ->where('id_mapel', $id_mapel)
            ->where('id_siswa', $id_siswa)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.akademik.nilai._filter_nilai', compact('data'));
    }

    public function filterLaporan(Request $request)
    {
        $id_kelas = $request->id_kelas;

        $data = DB::table('kelas_siswa')
            ->join('kelas', 'kelas_siswa.id_kelas', '=', 'kelas.id_kelas')
            ->join('siswas', 'siswas.id_siswa', '=', 'kelas_siswa.id_siswa')
            ->where('kelas_siswa.id_kelas', $id_kelas)
            ->get();

//        dd($data);

        return view('dashboard.akademik.nilai._filter_laporan', compact('data'));
    }

    public function cetak($id)
    {
//        $siswa = Siswa::with('kelas', 'nilai', 'ekskul')->findOrFail($id);

//        $nilai_harian = DB::table('nilais')
//            ->join('jadwals', 'jadwals.id', '=', 'nilais.id_jadwal')
//            ->join('mapels', 'mapels.id', '=', 'jadwals.id_mapel')
//            ->select('mapels.nm_mapel', DB::raw('AVG(nilais.nilai) as avg'))
//            ->where('nilais.id_siswa', $id)
//            ->whereNotIn('jadwals.materi', ['UAS', 'UTS'])
//            ->groupBy('mapels.nm_mapel')
//            ->get();


//        dd($nilai_harian);
        $nilai = DB::table('nilais')
            ->join('jadwals', 'jadwals.id', '=', 'nilais.id_jadwal')
            ->join('mapels', 'mapels.id', '=', 'jadwals.id_mapel')
            ->join('siswas', 'siswas.id_siswa', '=', 'nilais.id_siswa')
            ->select('siswas.nm_siswa','jadwals.materi','mapels.nm_mapel', 'nilais.nilai')
            ->where('nilais.id_siswa', $id)
            ->orderBy('jadwals.created_at', 'asc')
            ->get();

        $siswa = DB::table('siswas')
            ->where('id_siswa', $id)
            ->get()->first();

        // Untuk export ke Word
        $html = view('dashboard.akademik.nilai.template', compact('siswa', 'nilai'))->render();

        $filename = 'Laporan_Nilai_' . $siswa->nm_siswa . '.doc';

        return response($html)
            ->header('Content-Type', 'application/msword')
            ->header('Content-Disposition', "attachment; filename=$filename");
    }

    public function nilaiSave(Request $request)
    {
        $request->validate([
            'id_siswa' => 'required|exists:siswas,id_siswa',
            'id_mapel' => 'required|exists:mapels,id',
            'kategori_nilai' => 'required|in:Harian,UTS,UAS',
            'nilai_value' => 'required|integer|min:1|max:100',
            'lampiran' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
            'jadwal' => 'required|exists:jadwals,id',
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
            'id_jadwal' => $request->jadwal
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
        DB::table('nilais')->where('id', $id)->delete();

        return response()->json(['success' => true, 'message' => 'Data nilai berhasil dihapus']);
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
            'jadwal' => 'required|exists:jadwals,id',
        ]);


//        dd($validated);

        // Store the new jadwal in the database
        Jadwal::create([
            'id_mapel' => $validated['mapel'],
            'materi' => $validated['materi'],
            'tanggal' => $validated['tanggal'],
            'waktu_mulai' => $validated['waktu_mulai'],
            'waktu_selesai' => $validated['waktu_selesai'],
            'id_kelas' => $validated['id_kelas'],
            'id_jadwal' => $request->jadwal,
        ]);

        // Return response or redirect as needed
        return response()->json(['message' => 'Jadwal added successfully!']);
    }
}
