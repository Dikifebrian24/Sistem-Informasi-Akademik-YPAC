<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class KelasSiswaController extends Controller
{
    // (Optional) List siswa in a kelas
    public function index()
    {
//        $kelas = Kelas::with('siswas')->findOrFail($kelas_id);

        $params = [
//            'kelas' => $kelas,
            'title' => 'Data Pembagian Kelas',
        ];

        return view('dashboard.master.kelas_siswa.index', compact('params'));
    }

    public function getDatatables(Request $request)
    {
        if ($request->ajax()) {
            $data_kelas = Kelas::select([
                'kelas.id_kelas',
                'kelas.kd_kelas',
                'kelas.nm_kelas',
                'gurus.id_guru as guru_id',
                DB::raw('(SELECT COUNT(*) FROM kelas_siswa WHERE kelas_siswa.id_kelas = kelas.id_kelas) as jumlah_siswa')
            ])
                ->join('gurus', 'gurus.id_guru', '=', 'kelas.id_guru')
                ->where('kelas.stts_kelas', 'Active')
                ->get();

            return DataTables::of($data_kelas)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<button class="btn btn-sm btn-danger edit-btn" data-id="' . $row->id_kelas . '">Edit</button>';
                })
                ->addColumn('jumlah_siswa', function ($row) {
                    return $row->jumlah_siswa;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }


    public function getDatatablesListSiswa(Request $request)
    {
        if ($request->ajax()) {
            $kelas = DB::table('kelas')
                ->get();

//            dd($kelas);

            return DataTables::of($kelas)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<button class="btn btn-sm btn-danger edit-btn" data-id="' . $row->id_kelas . '">Edit</button>';
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
//        $siswa = Siswa::all();


        $assignedSiswaIds = DB::table('siswas')
            ->whereIn('id_siswa', function ($query) {
                $query->select('id_siswa')
                    ->from('kelas_siswa');
            })
            ->pluck('id_siswa')
            ->toArray();

        $siswa = Siswa::whereNotIn('id_siswa', $assignedSiswaIds)->get();

        $params = [
            'title' => 'Tambah Pembagian Kelas',
            'kelas' => $kelas_info,
            'siswa' => $siswa,
            'assigned' => $assignedSiswaIds, // List of already assigned siswa IDs
        ];

//        dd($params['assigned']);

        return view('dashboard.master.kelas_siswa.detail', compact('params'));
    }

    // Assign siswa to a kelas
    public function store(Request $request)
    {
        $request->validate([
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'siswa' => 'required|array',
            'siswa.*' => 'exists:siswas,id_siswa',
        ]);

        $id_kelas = $request->input('id_kelas');
        $siswa_ids = $request->input('siswa');

        // Remove existing assignments if needed
        DB::table('kelas_siswa')->where('id_kelas', $id_kelas)->delete();

        // Insert new records
        foreach ($siswa_ids as $id_siswa) {
            DB::table('kelas_siswa')->insert([
                'id_kelas' => $id_kelas,
                'id_siswa' => $id_siswa,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Siswa berhasil ditambahkan ke kelas.');
    }

    // Remove siswa from kelas
    public function destroy(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'siswa_id' => 'required|exists:siswas,id',
        ]);

        $kelas = Kelas::findOrFail($request->kelas_id);
        $kelas->siswas()->detach($request->siswa_id);

        return response()->json(['message' => 'Siswa removed from kelas']);
    }

}
