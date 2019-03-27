<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Storage;

class UploadPersyaratan extends Model
{
    protected $table = 'upload_persyaratan';
    protected $primaryKey = 'id_upload';
    protected $fillable = ['id_permohonan', 'id_persyaratan', 'path', 'status'];
    protected $dates = ['created_at', 'updated_at'];

    public function permohonan() {
    	return $this->belongsTo('App\Permohonan', 'id_permohonan', 'id_permohonan');
    }

    public function persyaratan() {
    	return $this->belongsTo('App\Persyaratan', 'id_persyaratan', 'id_persyaratan');
    }

    public static function saveToFile($filename, $data) {
    	if (preg_match('/^data:image\/(\w+);base64,/', $data, $type) || preg_match('/^data:application/pdf;base64,/', $data, $type)) {
    	    $data = substr($data, strpos($data, ',') + 1);
    	    $type = strtolower($type[1]); // jpg, png, gif

    	    if (!in_array($type, [ 'jpg', 'jpeg', 'gif', 'png', 'pdf' ])) {
    	        // throw new \Exception('invalid document type');
    	        return null;
    	    }

    	    $data = base64_decode($data);

    	    if ($data === false) {
    	        // throw new \Exception('base64_decode failed');
    	        return null;
    	    }
    	} else {
    	    // throw new \Exception('did not match data URI with image data');
    	    return null;
    	}

    	// file_put_contents($filename.".{$type}", $data);
    	Storage::put('public/'.$filename.".{$type}", $data);
    	return $filename.".{$type}";
    }
}
