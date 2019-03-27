<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pengaduan extends Model
{
    protected $table = 'pengaduan';
    protected $primaryKey = 'id_pengaduan';
    protected $fillable = ['no_pengaduan', 'jenis_pengaduan', 'isi_pengaduan', 'status', 'nik', 'id_user'];
    protected $dates = ['created_at', 'updated_at'];
    protected $appends = ['no'];

    public function getNoAttribute() {
        $no = [str_pad($this->no_pengaduan, 3, "0", STR_PAD_LEFT), "ADUAN", "ATG"];
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

    public function komentar() {
        return $this->hasMany('App\KomentarPengaduan', 'id_pengaduan', 'id_pengaduan');
    }

    public function upload() {
        return $this->hasOne('App\UploadPengaduan', 'id_pengaduan', 'id_pengaduan');
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
