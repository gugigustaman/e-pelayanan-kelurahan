<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Persyaratan extends Model
{
    protected $table = 'persyaratan';
    protected $primaryKey = 'id_persyaratan';
    protected $fillable = ['jenis_persyaratan'];
    protected $hidden = ['pivot'];
    protected $dates = ['created_at', 'updated_at'];

    public function layanan() {
    	return $this->belongsToMany('App\Layanan', 'persyaratan_layanan', 'id_persyaratan', 'id_layanan');
    }

    public function persyaratan_layanan() {
    	return $this->hasMany('App\PersyaratanLayanan', 'id_persyaratan', 'id_persyaratan');
    }

    public function permohonan() {
        return $this->belongsToMany('App\Permohonan', 'upload_persyaratan', 'id_persyaratan', 'id_permohonan')->withPivot('path');
    }
}
