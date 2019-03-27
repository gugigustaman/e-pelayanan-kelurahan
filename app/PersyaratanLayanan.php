<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PersyaratanLayanan extends Model
{
    protected $table = 'persyaratan_layanan';
    protected $primaryKey = 'id';
    protected $fillable = ['id_layanan', 'id_persyaratan'];
    protected $dates = ['created_at', 'updated_at'];

    public function persyaratan() {
    	return $this->belongsTo('App\Persyaratan', 'id_persyaratan', 'id_persyaratan');
    }

    public function layanan() {
    	return $this->belongsTo('App\Layanan', 'id_layanan', 'id_layanan');
    }

}
