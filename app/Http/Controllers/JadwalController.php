<?php

namespace App\Http\Controllers;

use App\Imports\JadwalImport;
use App\Imports\SiswaImport;
use App\Models\DataKelainan;
use App\Models\Jadwal;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

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

        $params = [
            'title' => 'Jadwal',
            'kelas' => Kelas::all(),
            'guru' => $data_guru,
            'mapel' => $mapel,
        ];
        return view('dashboard.master.jadwal.index', compact('params'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'import_data' => 'required|mimes:xlsx,xls',
        ]);

        Excel::import(new JadwalImport(), $request->file('import_data'));

        return response()->json(['message' => 'Import berhasil!']);
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
