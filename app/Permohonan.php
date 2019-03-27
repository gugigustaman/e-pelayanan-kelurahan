<?php

namespace App;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class Permohonan extends Model
{
    protected $table = 'permohonan';
    protected $primaryKey = 'id_permohonan';
    protected $fillable = ['id_layanan', 'no_sp', 'tgl_sp', 'keterangan', 'status', 'nik', 'id_user'];
    protected $dates = ['tgl_sp', 'created_at', 'updated_at'];
    protected $appends = ['no'];

    public function getNoAttribute() {
        $no = [str_pad($this->no_sp, 4, "0", STR_PAD_LEFT), $this->id_layanan, "ATG"];
        $no[] = $this->romanic_number(Carbon::parse($this->created_at)->month);
        $no[] = Carbon::parse($this->created_at)->year;
        return implode('/', $no); 
    }

    public function warga() {
    	return $this->belongsTo('App\Warga', 'nik', 'nik');
    }

    public function user() {
    	return $this->belongsTo('App\User', 'id_user', 'id_user');
    }

    public function layanan() {
    	return $this->belongsTo('App\Layanan', 'id_layanan', 'id_layanan');
    }

    public function persyaratan() {
        return $this->belongsToMany('App\Persyaratan', 'upload_persyaratan', 'id_permohonan', 'id_persyaratan')->withPivot('path', 'status')->withTimestamps();
    }

    // Convert Integer to Roman Numerals Function
    public function romanic_number($integer, $upcase = true) 
    { 
        $table = array('M'=>1000, 'CM'=>900, 'D'=>500, 'CD'=>400, 'C'=>100, 'XC'=>90, 'L'=>50, 'XL'=>40, 'X'=>10, 'IX'=>9, 'V'=>5, 'IV'=>4, 'I'=>1); 
        $return = ''; 
        while($integer > 0) 
        { 
            foreach($table as $rom=>$arb) 
            { 
                if($integer >= $arb) 
                { 
                    $integer -= $arb; 
                    $return .= $rom; 
                    break; 
                } 
            } 
        } 

        return $return; 
    } 
}
