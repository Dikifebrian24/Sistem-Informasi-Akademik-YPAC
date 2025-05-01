<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\GedungController;
use App\Http\Controllers\GolonganController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\KelainanController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\KepegawaianController;
use App\Http\Controllers\KurikulumController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\PtkController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\ThnAkademikController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes(['verify' => true]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::prefix('master')->group(function () {
    Route::controller(KurikulumController::class)->prefix('kurikulum')->group(function () {
        Route::get('', 'index')->name('kurikulum');
        Route::get('add', 'add')->name('kurikulum/add');
        Route::post('save', 'store')->name('kurikulum/save');
        Route::get('edit/{id}', 'edit')->name('kurikulum/edit');
        Route::put('update/{id}', 'update')->name('kurikulum/update');
        Route::delete('delete/{id}', 'destroy')->name('kurikulum/delete');
    });

    Route::controller(JadwalController::class)->prefix('jadwal')->group(function () {
        Route::get('', 'index')->name('jadwal');
        Route::get('add', 'add')->name('jadwal/add');
        Route::get('data', 'getDatatables')->name('jadwal/data');
        Route::get('get-jadwal', 'getJadwal')->name('jadwal/get-jadwal');
        Route::get('{id}/edit', 'edit')->name('jadwal/edit');
        Route::post('store', 'store')->name('jadwal/store');
        Route::post('save', 'store')->name('jadwal/save');
        Route::put('update/{id}', 'update')->name('jadwal/update');
        Route::delete('delete/{id}', 'destroy')->name('jadwal/delete');
    });

    Route::get('/disabilitas', [\App\Http\Controllers\HomeController::class, '']);

    Route::controller(ThnAkademikController::class)->prefix('thnakademik')->group(function () {
        Route::get('', 'index')->name('thnakademik');
        Route::get('add', 'add')->name('thnakademik/add');
        Route::post('save', 'store')->name('thnakademik/save');
        Route::get('edit/{id}', 'edit')->name('thnakademik/edit');
        Route::put('update/{id}', 'update')->name('thnakademik/update');
        Route::delete('delete/{id}', 'destroy')->name('thnakademik/delete');
    });

    Route::controller(GedungController::class)->prefix('gedung')->group(function () {
        Route::get('', 'index')->name('gedung');
        Route::get('add', 'add')->name('gedung/add');
        Route::post('save', 'store')->name('gedung/save');
        Route::get('edit/{id}', 'edit')->name('gedung/edit');
        Route::put('update/{id}', 'update')->name('gedung/update');
        Route::delete('delete/{id}', 'destroy')->name('gedung/delete');
    });

    Route::controller(RuanganController::class)->prefix('ruangan')->group(function () {
        Route::get('', 'index')->name('ruangan');
        Route::get('add', 'add')->name('ruangan/add');
        Route::post('save', 'store')->name('ruangan/save');
        Route::get('edit/{id}', 'edit')->name('ruangan/edit');
        Route::put('update/{id}', 'update')->name('ruangan/update');
        Route::delete('delete/{id}', 'destroy')->name('ruangan/delete');
    });

    Route::controller(GolonganController::class)->prefix('golongan')->group(function () {
        Route::get('', 'index')->name('golongan');
        Route::get('add', 'add')->name('golongan/add');
        Route::post('save', 'store')->name('golongan/save');
        Route::get('edit/{id}', 'edit')->name('golongan/edit');
        Route::put('update/{id}', 'update')->name('golongan/update');
        Route::delete('delete/{id}', 'destroy')->name('golongan/delete');
    });

    Route::controller(PtkController::class)->prefix('ptk')->group(function () {
        Route::get('', 'index')->name('ptk');
        Route::post('save', 'store')->name('ptk/save');
        Route::get('edit/{id}', 'edit')->name('ptk/edit');
        Route::put('update/{id}', 'update')->name('ptk/update');
        Route::delete('delete/{id}', 'destroy')->name('ptk/delete');
    });

    Route::controller(JurusanController::class)->prefix('jurusan')->group(function () {
        Route::get('', 'index')->name('jurusan');
        Route::get('add', 'add')->name('jurusan/add');
        Route::post('save', 'store')->name('jurusan/save');
        Route::get('detail/{id}', 'detail')->name('jurusan/detail');
        Route::get('edit/{id}', 'edit')->name('jurusan/edit');
        Route::put('update/{id}', 'update')->name('jurusan/update');
        Route::delete('delete/{id}', 'destroy')->name('jurusan/delete');
    });

    Route::controller(KelasController::class)->prefix('kelas')->group(function () {
        Route::get('', 'index')->name('kelas');
        Route::get('add', 'add')->name('kelas/add');
        Route::post('save', 'store')->name('kelas/save');
        Route::get('detail/{id}', 'detail')->name('kelas/detail');
        Route::get('edit/{id}', 'edit')->name('kelas/edit');
        Route::put('update/{id}', 'update')->name('kelas/update');
        Route::delete('delete/{id}', 'destroy')->name('kelas/delete');
    });

    Route::controller(KepegawaianController::class)->prefix('kepegawaian')->group(function () {
        Route::get('', 'index')->name('kepegawaian');
        Route::post('save', 'store')->name('kepegawaian/save');
        Route::get('edit/{id}', 'edit')->name('kepegawaian/edit');
        Route::put('update/{id}', 'update')->name('kepegawaian/update');
        Route::delete('delete/{id}', 'destroy')->name('kepegawaian/delete');
    });

    Route::controller(SiswaController::class)->prefix('siswa')->group(function () {
        Route::get('', 'index')->name('siswa');
        Route::get('add', 'add')->name('siswa/add');
        Route::get('data', 'getDatatables')->name('siswa/data');
        Route::get('{id}/edit', 'edit')->name('siswa/edit');
        Route::post('store', 'store')->name('siswa/store');
        Route::post('save', 'store')->name('siswa/save');
        Route::put('update/{id}', 'update')->name('siswa/update');
        Route::delete('delete/{id}', 'destroy')->name('siswa/delete');
    });

    Route::controller(GuruController::class)->prefix('guru')->group(function () {
        Route::get('', 'index')->name('guru');
        Route::get('add', 'add')->name('guru/add');
        Route::get('data', 'getDatatables')->name('guru/data');
        Route::post('save', 'store')->name('guru/save');
        Route::get('edit/{id}', 'edit')->name('guru/edit');
        Route::put('update/{id}', 'update')->name('guru/update');
        Route::delete('delete/{id}', 'destroy')->name('guru/delete');
    });


    Route::controller(AdminController::class)->prefix('admin')->group(function () {
        Route::get('', 'index')->name('admin');
        Route::get('add', 'add')->name('admin/add');
        Route::get('data', 'getDatatables')->name('admin/data');
        Route::get('{id}/edit', 'edit')->name('admin/edit');
        Route::post('store', 'store')->name('admin/store');
        Route::post('save', 'store')->name('admin/save');
        Route::put('update/{id}', 'update')->name('admin/update');
        Route::delete('delete/{id}', 'destroy')->name('admin/delete');
    });

    Route::controller(KelainanController::class)->prefix('kelainan')->group(function () {
        Route::get('', 'index')->name('kelainan');
        Route::get('add', 'add')->name('kelainan/add');
        Route::get('data', 'getDatatables')->name('kelainan/data');
        Route::get('{id}/edit', 'edit')->name('kelainan/edit');
        Route::post('store', 'store')->name('kelainan/store');
        Route::post('save', 'store')->name('kelainan/save');
        Route::put('update/{id}', 'update')->name('kelainan/update');
        Route::delete('delete/{id}', 'destroy')->name('kelainan/delete');
    });

    Route::controller(MapelController::class)->prefix('mapel')->group(function () {
        Route::get('', 'index')->name('mapel');
        Route::get('data', 'getDatatables')->name('mapel/data');
        Route::get('add', 'add')->name('mapel/add');
        Route::post('save', 'store')->name('mapel/save');
        Route::post('store', 'store')->name('mapel/store');
        Route::get('detail/{id}', 'detail')->name('mapel/detail');
        Route::get('edit/{id}', 'edit')->name('mapel/edit');
        Route::put('update/{id}', 'update')->name('mapel/update');
        Route::delete('delete/{id}', 'destroy')->name('mapel/delete');
    });
});
