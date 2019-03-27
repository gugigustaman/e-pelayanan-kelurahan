<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Arsip extends Model
{
    protected $table = 'arsip';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'id_permohonan', 'file_path'];
    protected $dates = ['created_at', 'updated_at'];

    public function permohonan() {
    	return $this->belongsTo('App\Permohonan', 'id_permohonan', 'id_permohonan');
    }
}
