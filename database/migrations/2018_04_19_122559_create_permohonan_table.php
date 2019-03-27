<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermohonanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permohonan', function (Blueprint $table) {
            $table->increments('id_permohonan');
            $table->string('id_layanan');
            $table->integer('no_sp');
            $table->date('tgl_sp');
            $table->string('keterangan');
            $table->enum('status', ['0', '1', '2', '3']);
            $table->string('nik')->nullable();
            $table->integer('id_user')->unsigned()->nullable();
            $table->string('alasan_tolak')->nullable();
            $table->timestamps();

            $table->foreign('nik')->references('nik')->on('warga')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('id_user')->references('id_user')->on('user')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('id_layanan')->references('id_layanan')->on('layanan')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permohonan');
    }
}
