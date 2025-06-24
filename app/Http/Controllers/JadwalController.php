<?php

namespace App\Http\Controllers;

use App\Exports\TemplateNilaiExport;
use App\Imports\JadwalImport;
use App\Imports\SiswaImport;
use App\Models\DataKelainan;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;

class JadwalController extends Controller
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
        $guru = Guru::all();

        $params = [
            'title' => 'Jadwal',
            'kelas' => Kelas::all(),
            'guru' => $guru,
            'mapel' => $mapel,
        ];
        return view('dashboard.master.jadwal.index', compact('params'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'import_data' => 'required|mimes:xlsx,xls',
        ]);

//        Excel::import(new JadwalImport(), $request->file('import_data'));
        Excel::import(new JadwalImport($request->id_kelas), $request->file('import_data'));

        return response()->json(['message' => 'Import berhasil!']);
    }

    public function jadwal_mengajar()
    {
        $mapel = Mapel::all();
        $guru = Guru::all();

        $params = [
            'title' => 'Jadwal Mengajar',
            'kelas' => Kelas::all(),
            'guru' => $guru,
            'mapel' => $mapel,
        ];
        return view('dashboard.master.jadwal.jadwal_mengajar', compact('params'));
    }

    public function jadwal_siswa()
    {
        $id_user = Auth::id();

        $id_siswa = DB::table('siswas')->where('id_user', $id_user)->value('id_siswa');

        $params = [
            'title' => 'Jadwal Mengajar',
            'kelas' => Kelas::all(),
        ];
        return view('dashboard.master.jadwal.jadwal_siswa', compact('params'));
    }


    public function getDatatables(Request $request)
    {
        if ($request->ajax()) {
            $data_kelas = Kelas::select(['id_kelas', 'gurus.id_guru as guru_id', 'nm_kelas'])
                ->leftjoin('gurus', 'gurus.id_guru', '=', 'kelas.id_guru')
                ->where('stts_kelas', 'Active')->get();

            return DataTables::of($data_kelas)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<button class="btn btn-sm btn-danger" id="show" data-id="' . $row->id_kelas . '">Lihat</button>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function getDatatablesMengajar(Request $request)
    {

        if ($request->ajax()) {
//            $data_kelas = Kelas::select(['id_kelas', 'gurus.id_guru as guru_id', 'nm_kelas'])
//                ->leftjoin('gurus', 'gurus.id_guru', '=', 'kelas.id_guru')
//                ->where('stts_kelas', 'Active')->get();

            $mengajar = DB::table('jadwals')
                ->join('mapels', 'mapels.id', '=', 'jadwals.id_mapel')
                ->join('kelas', 'kelas.id_kelas', '=', 'jadwals.id_kelas')
                ->join('gurus', 'gurus.id_guru', '=', 'jadwals.id_guru')
                ->where('gurus.id_user', Auth::user()->id)
                ->orderBy('jadwals.tanggal', 'asc')
                ->get();

            return DataTables::of($mengajar)
                ->addIndexColumn()
                ->editColumn('hari', function ($row) {
                    return $this->getHari($row->tanggal);
                })
                ->editColumn('tanggal', function ($row) {
                    $date = Carbon::parse($row->tanggal);
                    return $date->translatedFormat('d F Y'); // Contoh: 12 Juni 2025
                    // Kalau ingin format lengkap: return $date->translatedFormat('l, d F Y');
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function getDatatablesJadwalSiswa(Request $request)
    {
        $id_user = Auth::id();

        $id_siswa = DB::table('siswas')->where('id_user', $id_user)->value('id_siswa');

        $id_kelas = DB::table('kelas_siswa')->where('id_siswa', $id_siswa)->value('id_kelas');

//        dd($id_kelas);


        if ($request->ajax()) {
//            $data_kelas = Kelas::select(['id_kelas', 'gurus.id_guru as guru_id', 'nm_kelas'])
//                ->leftjoin('gurus', 'gurus.id_guru', '=', 'kelas.id_guru')
//                ->where('stts_kelas', 'Active')->get();

            $mengajar = DB::table('jadwals')
                ->join('mapels', 'mapels.id', '=', 'jadwals.id_mapel')
                ->join('kelas', 'kelas.id_kelas', '=', 'jadwals.id_kelas')
                ->join('gurus', 'gurus.id_guru', '=', 'jadwals.id_guru')
                ->where('jadwals.id_kelas', $id_kelas)
                ->orderBy('jadwals.tanggal', 'asc')
                ->get();

            return DataTables::of($mengajar)
                ->addIndexColumn()
                ->editColumn('hari', function ($row) {
                    return $this->getHari($row->tanggal);
                })
                ->editColumn('tanggal', function ($row) {
                    $date = Carbon::parse($row->tanggal);
                    return $date->translatedFormat('d F Y'); // Contoh: 12 Juni 2025
                    // Kalau ingin format lengkap: return $date->translatedFormat('l, d F Y');
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function detail($id)
    {
        $jadwal = Jadwal::where('id_kelas', $id);

        $data_guru = DB::table('users')->where('level', 2)->get();

        $mapel = DB::table('mapels')->where('id_kelas', $id)->get();

        $kelas = DB::table('kelas')->where('id_kelas', $id)->first()->nm_kelas;

        $guru = Guru::all();

        $params = [
            'title' => 'Jadwal',
            'kelas' => Kelas::all(),
            'guru' => $guru,
            'mapel' => $mapel,
            'jadwal' => $jadwal,
            'kelas' => $kelas,
        ];

        return view('dashboard.master.jadwal.detail', compact('params'));
    }

    public function exportTemplate(Request $request)
    {
        $id_mapel = $request->get('_id_mapel');
        $id_kelas = $request->get('_id_kelas');

//        dd($id_mapel, $id_kelas);

        // Validasi param
//        if (!$id_mapel || !$id_jadwal) {
//            abort(400, 'Missing parameter');
//        }

        return Excel::download(new TemplateNilaiExport($id_kelas, $id_mapel), 'template_nilai_kelas_' . $id_kelas . '.xlsx');
    }

    public function getJadwal(Request $request)
    {
        $idKelas = $request->input('id_kelas');

        if ($request->ajax()) {
//            $query = Jadwal::where('id_kelas', $request->id_kelas);

            $query = DB::table('jadwals')
                ->select('jadwals.id as jadwal_id', 'materi', 'nm_mapel', 'jadwals.tanggal', 'waktu_mulai', 'waktu_selesai', 'nm_guru')
                ->join('gurus', 'gurus.id_guru', '=', 'jadwals.id_guru')
                ->join('mapels', 'mapels.id', '=', 'jadwals.id_mapel')
                ->join('kelas', 'kelas.id_kelas', '=', 'jadwals.id_kelas')
                ->where('jadwals.id_kelas', $idKelas)
                ->get();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '
            <button class="btn btn-sm btn-warning edit" data-id="' . $row->jadwal_id . '">Edit</button>
            <button class="btn btn-sm btn-danger delete" data-id="' . $row->jadwal_id . '">Delete</button>
        ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function add(Request $request)
    {
        $id_kelas = $request->input('id_kelas');

        // Get the kelas information
        $kelas_info = Kelas::findOrFail($id_kelas);

        // Get the list of all siswa
        $siswa = Siswa::all();

        // Get the assigned siswa IDs for this kelas
        $assignedSiswaIds = DB::table('kelas_siswa')
            ->where('id_kelas', $id_kelas)
            ->pluck('id_siswa')
            ->toArray();


        $params = [
            'title' => 'Tambah Pembagian Kelas',
            'kelas' => $kelas_info,
            'siswa' => $siswa,
            'assigned' => $assignedSiswaIds, // List of already assigned siswa IDs
        ];

        return view('dashboard.master.jadwal.detail', compact('params'));
    }

//    public function edit($id)
//    {
////        $jadwal = Jadwal::findOrFail($id);
//
//
//        return response()->json($jadwal);
//    }

    public function edit($id)
    {
//        dd($id);
        $jadwal = DB::table('jadwals')->where('id', $id)->first();

//        dd($jadwal);

        if (!$jadwal) {
            return response()->json(['message' => 'Data jadwal tidak ditemukan'], 404);
        }

        $mapel = Mapel::all();
        $guru = Guru::all();

        return response()->json([
            'jadwal' => $jadwal,
            'mapel' => $mapel,
            'guru' => $guru,
        ]);
    }

    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::findOrFail($id);

        $jadwal->id_mapel = $request->input('mapel');
        $jadwal->id_guru = $request->input('guru');
        $jadwal->materi = $request->input('materi');
        $jadwal->tanggal = $request->input('tanggal');
        $jadwal->waktu_mulai = $request->input('waktu_mulai');
        $jadwal->waktu_selesai = $request->input('waktu_selesai');

        $jadwal->save();

        return response()->json(['message' => 'Jadwal berhasil diperbarui']);
    }

    public function destroy($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $jadwal->delete();

        return response()->json(['message' => 'Jadwal berhasil dihapus']);
    }

    public function store(Request $request)
    {
        // Validate the data
        $validated = $request->validate([
            'materi' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'kelas_id' => 'required|exists:kelas,id_kelas',
            'mapel' => 'required|exists:mapels,id',
            'guru' => 'required|exists:gurus,id_guru',
        ]);

        // Store the new jadwal in the database
        Jadwal::create([
            'id_mapel' => $validated['mapel'],
            'id_guru' => $validated['guru'],
            'materi' => $validated['materi'],
            'tanggal' => $validated['tanggal'],
            'waktu_mulai' => $validated['waktu_mulai'],
            'waktu_selesai' => $validated['waktu_selesai'],
            'id_kelas' => $validated['kelas_id'],
        ]);

        // Return response or redirect as needed
        return response()->json(['message' => 'Jadwal added successfully!']);
    }

    public function getHari($tanggal)
    {
        $carbon = Carbon::parse($tanggal);
        $englishDay = $carbon->format('l'); // contoh: 'Tuesday'

        $map = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];

        return $map[$englishDay] ?? $englishDay;
    }

//hkjhkhk

}
