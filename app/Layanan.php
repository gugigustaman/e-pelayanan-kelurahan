<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    protected $table = 'layanan';
    protected $primaryKey = 'id_layanan';
    public $incrementing = false;
    protected $fillable = ['id_layanan', 'jenis_layanan', 'template_path'];
    protected $dates = ['created_at', 'updated_at'];

    public function permohonan() {
        return $this->hasMany('App\Permohonan', 'id_layanan', 'id_layanan');
    }

    public function persyaratan() {
    	return $this->belongsToMany('App\Persyaratan', 'persyaratan_layanan', 'id_layanan', 'id_persyaratan')->withTimestamps();
    }

    public function persyaratan_layanan() {
        return $this->hasMany('App\PersyaratanLayanan', 'id_layanan', 'id_layanan');
    }

    public function permohonan_menunggu() {
        return $this->permohonan()->where('status', '0');
    }
    public function permohonan_proses() {
        return $this->permohonan()->where('status', '1');
    }
    public function permohonan_selesai() {
        return $this->permohonan()->where('status', '2');
    }
    public function permohonan_ditolak() {
        return $this->permohonan()->where('status', '3');
    }
}
