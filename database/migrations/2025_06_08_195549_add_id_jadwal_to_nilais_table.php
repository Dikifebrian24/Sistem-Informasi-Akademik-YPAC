<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nilais', function (Blueprint $table) {
            // Tambah kolom id_jadwal jika belum ada
            if (!Schema::hasColumn('nilais', 'id_jadwal')) {
                $table->unsignedBigInteger('id_jadwal')->nullable()->after('id_mapel');
            }

            $table->foreign('id_jadwal', 'fk_nilais_jadwal')->references('id_jadwal')->on('jadwals')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nilais', function (Blueprint $table) {
            $table->dropForeign(['id_jadwal']);
            $table->dropColumn('id_jadwal');
        });
    }
};
