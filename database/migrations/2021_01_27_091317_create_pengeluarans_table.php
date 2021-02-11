<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengeluaransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengeluarans', function (Blueprint $table) {
            $table->id('id_pengeluaran');
            $table->unsignedBigInteger('id_kategori');
            $table->unsignedBigInteger('id_subkategori');
            $table->string('tgl_pengeluaran');
            $table->string('jumlah');
            $table->timestamps();

            $table->foreign('id_kategori')->references('id_kategori')->on('kategoris');
            $table->foreign('id_subkategori')->references('id_subkategori')->on('subkategoris');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pengeluarans');
    }
}
