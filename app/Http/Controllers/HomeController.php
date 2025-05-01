<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        $disabilitasData = DB::table('siswas as s')
            ->join('data_kelainans as dk', 's.id_kelainan', '=', 'dk.id')
            ->select('dk.nm_kelainan', DB::raw('COUNT(s.id_siswa) as total_disabilitas'))
            ->groupBy('dk.nm_kelainan')
            ->get();

        return view('dashboard.home', compact('disabilitasData'));
    }

}
