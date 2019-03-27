<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePengaduanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengaduan', function (Blueprint $table) {
            $table->increments('id_pengaduan');
            $table->integer('no_pengaduan')->nullable()->unsigned();
            $table->enum('jenis_pengaduan', ['PELAYANAN', 'NON PELAYANAN']);
            $table->text('isi_pengaduan');
            $table->enum('status', ['0', '1']);
            $table->string('nik')->nullable();
            $table->integer('id_user')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('nik')->references('nik')->on('warga')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('id_user')->references('id_user')->on('user')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pengaduan');
    }
}
