<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWargaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warga', function (Blueprint $table) {
            $table->string('nik');
            $table->string('nama');
            $table->string('tmpt_lahir');
            $table->date('tgl_lahir');
            $table->enum('jk', ['L', 'P']);
            $table->string('alamat');
            $table->integer('rt');
            $table->integer('rw');
            $table->string('agama');
            $table->enum('status', ['KAWIN', 'BELUM KAWIN', 'CERAI']);
            $table->string('pekerjaan');
            $table->string('no_kk');
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamps();

            $table->primary('nik');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warga');
    }
}
