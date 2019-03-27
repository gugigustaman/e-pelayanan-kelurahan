<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKomentarPengaduan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('komentar_pengaduan', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_pengaduan')->unsigned()->nullable();
            $table->integer('id_user')->unsigned()->nullable();
            $table->string('nik')->nullable();
            $table->enum('status', ['0', '1']);
            $table->text('isi_komentar');
            $table->timestamps();

            $table->foreign('id_pengaduan')->references('id_pengaduan')->on('pengaduan')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_user')->references('id_user')->on('user')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('nik')->references('nik')->on('warga')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('komentar_pengaduan');
    }
}
