<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMajorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('majors', function (Blueprint $table) {
            $table->id('id_kelas');
            $table->unsignedBigInteger('id_jurusan');
            $table->unsignedBigInteger('id_tingkat');
            $table->timestamps();

            $table->foreign('id_jurusan')->references('id_jurusan')->on('jurusans');
            $table->foreign('id_tingkat')->references('id_tingkat')->on('tingkats');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('majors');
    }
}
