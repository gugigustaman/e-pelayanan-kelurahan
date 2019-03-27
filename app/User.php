<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    protected $table = 'user';
    protected $primaryKey = 'id_user';
    protected $fillable = ['nama_user', 'password', 'nama_petugas', 'jabatan', 'alamat', 'lvl_user'];

    protected $hidden = [
        'password', 'remember_token',
    ];
    
    protected $dates = ['created_at', 'updated_at'];

    public function permohonan() {
        return $this->hasMany('App\Permohonan', 'id_user', 'id_user');
    }

    public function pengaduan() {
        return $this->hasMany('App\Pengaduan', 'id_user', 'id_user');
    }
}
