<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSppsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spps', function (Blueprint $table) {
            $table->id('id_spp');
            $table->unsignedBigInteger('id_tahun_akademik');
            $table->string('jumlah');
            $table->unsignedBigInteger('id_tingkat');
            $table->timestamps();
        
            $table->foreign('id_tahun_akademik')->references('id_tahun_akademik')->on('tahun_akademiks');
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
        Schema::dropIfExists('spps');
    }
}
