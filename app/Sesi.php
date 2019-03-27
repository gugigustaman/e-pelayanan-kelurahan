<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sesi extends Model
{
    protected $table = 'sesi';
    protected $primaryKey = 'id';
    protected $fillable = [
    	'nik',
    	'token',
    	'ip_address',
    	'waktu_masuk',
    	'waktu_keluar',
    ];
    protected $dates = ['created_at', 'updated_at'];

    public function warga() {
    	return $this->belongsTo('App\Warga', 'nik', 'nik');
    }
}
