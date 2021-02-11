<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->string('tgl_bayar');
            $table->unsignedBigInteger('nis');
            $table->unsignedBigInteger('id_spp');
            $table->unsignedBigInteger('id_tahun_akademik');
            $table->unsignedBigInteger('id_tingkat');
            $table->unsignedBigInteger('id_jurusan');
            $table->timestamps();

            $table->foreign('nis')->references('nis')->on('siswas');
            $table->foreign('id_spp')->references('id_spp')->on('spps');
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
        Schema::dropIfExists('transactions');
    }
}
