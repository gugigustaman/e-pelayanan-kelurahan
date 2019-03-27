<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Storage;

class UploadPengaduan extends Model
{
    protected $table = 'upload_pengaduan';
    protected $primaryKey = 'id_upload';
    protected $fillable = ['id_pengaduan', 'path', 'status'];
    protected $dates = ['created_at', 'updated_at'];
    protected $appends = ['url'];

    public function getUrlAttribute() {
        return Storage::url('public/'.$this->path);
    }

    public function pengaduan() {
    	return $this->belongsTo('App\Pengaduan', 'id_pengaduan', 'id_pengaduan');
    }

    public static function saveToFile($filename, $data) {
    	if (preg_match('/^data:video\/(\w+);base64,/', $data, $type) || preg_match('/^data:image\/(\w+);base64,/', $data, $type) || preg_match('/^data:application/pdf;base64,/', $data, $type)) {
    	    $data = substr($data, strpos($data, ',') + 1);
    	    $type = strtolower($type[1]); // jpg, png, gif

    	    if ($type == '3gpp') {
    	    	$type = 'mp4';
    	    }

    	    if (!in_array($type, [ 'mp4', 'avi', 'jpg', 'jpeg', 'gif', 'png', 'pdf' ])) {
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
