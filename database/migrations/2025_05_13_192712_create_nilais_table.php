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
        Schema::create('nilais', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_siswa');
            $table->unsignedBigInteger('id_mapel');
            $table->string('kategori_nilai'); // Harian, UTS, UAS
            $table->integer('nilai');
            $table->text('desc_nilai')->nullable();
            $table->string('lampiran')->nullable(); // Nama file upload
            $table->timestamps();

            // Menambahkan Foreign Key untuk id_siswa dan id_mapel
            $table->foreign('id_siswa')->references('id_siswa')->on('siswas')->onDelete('cascade');
            $table->foreign('id_mapel')->references('id')->on('mapels')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nilais');
    }
};
