<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Warga extends Model
{
    protected $table = 'warga';
    protected $primaryKey = 'nik';
    public $incrementing = false;
    protected $fillable = ['nik', 'nama', 'tmpt_lahir', 'tgl_lahir', 'jk', 'alamat', 'rt', 'rw', 'agama', 'status', 'pekerjaan', 'no_kk', 'email', 'password'];
    protected $hidden = ['password'];
    protected $dates = ['created_at', 'updated_at'];

    public function permohonan() {
    	return $this->hasMany('App\Permohonan', 'nik', 'nik');
    }

    public function pengaduan() {
    	return $this->hasMany('App\Pengaduan', 'nik', 'nik');
    }
}
