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
            $data_kelas = Kelas::select(['id_kelas', 'gurus.id_guru as guru_id','nm_kelas'])
                ->join('gurus', 'gurus.id_guru', '=', 'kelas.id_guru')
                ->where('stts_kelas', 'Active')->get();

            return DataTables::of($data_kelas)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<button class="btn btn-sm btn-danger edit-btn" data-id="'.$row->id_kelas.'">Edit</button>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }



    public function add(Request $request)
    {
        $id_kelas = $request->input('id_kelas');

        $kelas_info = Kelas::where('id_kelas', $id_kelas)->first();

        $siswa = Siswa::select('id_siswa', 'nm_siswa')->get();

        $assignedSiswaIds = DB::table('kelas_siswa')
            ->where('id_kelas', $id_kelas)
            ->pluck('id_siswa')
            ->toArray();

        $params = [
            'title' => 'Tambah Pembagian Kelas',
            'kelas' => $kelas_info,
            'siswa' => $siswa,
            'assigned' => $assignedSiswaIds,
        ];

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
