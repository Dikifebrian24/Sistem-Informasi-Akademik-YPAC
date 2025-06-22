<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
//        $id_user_guru = DB::table('users')->where('id', Auth::id())->first();

        if (Auth::user()->level == 2) {
            $id_user_guru = DB::table('gurus')->where('id_user', Auth::id())->first()->id_guru;

            $id_kelas_guru = DB::table('kelas')->where('id_guru', '=', $id_user_guru)->first()->id_kelas;

        }

        $level_login = DB::table('users')->where('id', Auth::id())->first()->level;



        if ($level_login ==  2) {
            $disabilitasData = DB::table('siswas as s')
                ->join('data_kelainans as dk', 's.id_kelainan', '=', 'dk.id')
                ->join('kelas_siswa as ks', 's.id_siswa', '=', 'ks.id_siswa')
                ->select('dk.nm_kelainan', DB::raw('COUNT(s.id_siswa) as total_disabilitas'))
                ->where('ks.id_kelas', $id_kelas_guru)
                ->groupBy('dk.nm_kelainan')
                ->get();
        } else {
            $disabilitasData = DB::table('siswas as s')
                ->join('data_kelainans as dk', 's.id_kelainan', '=', 'dk.id')
                ->select('dk.nm_kelainan', DB::raw('COUNT(s.id_siswa) as total_disabilitas'))
                ->groupBy('dk.nm_kelainan')
                ->get();
        }

        $siswa = DB::table('siswas')
            ->join('users', 'siswas.id_user', '=', 'users.id')
            ->where('siswas.id_user', Auth::user()->id)
            ->get()->first();

        return view('dashboard.home', compact('disabilitasData', 'siswa'));
    }


}
