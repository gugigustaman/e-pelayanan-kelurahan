<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUploadPersyaratanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('upload_persyaratan', function (Blueprint $table) {
            $table->increments('id_upload');
            $table->integer('id_permohonan')->unsigned();
            $table->integer('id_persyaratan')->unsigned();
            $table->string('path');
            $table->enum('status', ['0', '1', '2'])->default('0');
            $table->timestamps();

            $table->foreign('id_permohonan')->references('id_permohonan')->on('permohonan')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('upload_persyaratan');
    }
}
