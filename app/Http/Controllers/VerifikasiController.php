<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UploadPersyaratan;
use Auth;
use Input;
use Validator;

class VerifikasiController extends Controller
{
    public function index() {
    	return view('verifikasi.index');
    }

    public function dtIndex() {
    	$sp = UploadPersyaratan::with('permohonan.warga', 'permohonan.layanan')->whereHas('permohonan', function($query) {
    		$query->join('warga', 'permohonan.nik', '=', 'warga.nik')
    			->where('rw', Auth::user()->rw);
    	})->where('id_persyaratan', 3)
        ->where('upload_persyaratan.status', '0');
    	return datatables()->of($sp)->toJson();
    }

    public function verify(Request $req) {
        $inputs = Input::all();

        $validator = Validator::make($inputs, [
            'id' => 'required|exists:upload_persyaratan,id_upload',
            'verify' => 'required|in:1,2',
        ]);

        if ($validator->fails()) {
            return redirect('/verifikasi')
                ->withErrors($validator, 'form')
                ->withInput()
                ->with('type', 'danger')
                ->with('message', '<b>Gagal!</b> Permintaan tidak valid.');
        }

        $upload = UploadPersyaratan::find($req->id);
        $upload->status = $req->verify;

        if (!$upload->save()) {
            return redirect('/verifikasi')
                ->with('type', 'danger')
                ->with('message', '<b>Gagal!</b> Terjadi kesalahan pada sistem. Mohon hubungi administrator terkait hal ini.');
        }

        return redirect('/verifikasi')
            ->with('type', 'success')
            ->with('message', '<b>Berhasil!</b> Dokumen telah diverifikasi.');

    }
}
