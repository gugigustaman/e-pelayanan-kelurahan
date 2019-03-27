<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersyaratanLayananTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('persyaratan_layanan', function (Blueprint $table) {
            $table->increments('id');
            $table->string('id_layanan');
            $table->integer('id_persyaratan')->unsigned();
            $table->timestamps();

            $table->foreign('id_layanan')->references('id_layanan')->on('layanan')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_persyaratan')->references('id_persyaratan')->on('persyaratan')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('persyaratan_layanan');
    }
}
