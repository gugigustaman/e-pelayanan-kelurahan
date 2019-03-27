<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KomentarPengaduan extends Model
{
    protected $table = 'komentar_pengaduan';
    protected $primaryKey = 'id';
    protected $fillable = ['id_pengaduan', 'id_user', 'nik', 'status', 'isi_komentar'];
    protected $dates = ['created_at', 'updated_at'];

    public function pengaduan() {
    	return $this->belongsTo('App\Pengaduan', 'id_pengaduan', 'id_pengaduan');
    }

    public function petugas() {
    	return $this->belongsTo('App\User', 'id_user', 'id_user');
    }

    public function warga() {
    	return $this->belongsTo('App\Warga', 'nik', 'nik');
    }
}
