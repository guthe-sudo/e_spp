<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiswasgygTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('siswas', function (Blueprint $table) {
            $table->id('nis');
            $table->string('nama');
            $table->string('tempat_lahir');
            $table->string('tgl_lahir');
            $table->string('jk');
            $table->unsignedBigInteger('id_tahun_akademik');
            $table->unsignedBigInteger('id_tingkat');
            $table->unsignedBigInteger('id_jurusan');
            $table->string('alamat');
            $table->string('foto');
            $table->timestamps();
        
            $table->foreign('id_tahun_akademik')->references('id_tahun_akademik')->on('tahun_akademiks');
            $table->foreign('id_tingkat')->references('id_tingkat')->on('tingkats');
            $table->foreign('id_jurusan')->references('id_jurusan')->on('jurusans');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('siswas');
    }
}
