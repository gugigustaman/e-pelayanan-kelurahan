<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\Warga;
use App\Persyaratan;
use App\Sesi;
use Validator;
use Hash;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class WargaController extends Controller
{

	public function __construct() {
		$this->middleware('auth', ['except' => ['apiRegister', 'apiLogin', 'apiLogout', 'apiCheck']]);
		$this->middleware('warga_auth', ['only' => ['apiLogout', 'apiCheck']]);
	}
    
    public function index() {
    	return view('warga.index');
    }

    public function dtIndex() {
    	return datatables()->of(Warga::all())->toJson();
    }

    public function create() {
    	$persyaratan = Persyaratan::all();
    	return view('warga.create', ['persyaratan' => $persyaratan]);
    }

    public function save(Request $req) {
		$inputs = $req->all();

		$validator = Validator::make($inputs, [
		    'id_warga' => 'string|required|unique:warga,id_warga',
		    'jenis_warga' => 'string|required',
		    'persyaratan.*' => 'numeric|required|exists:persyaratan,id_persyaratan'
		]);

		if ($validator->fails()) {
		    return redirect('/warga/tambah')
		                ->withErrors($validator, 'form')
		                ->withInput()
		                ->with('type', 'danger')
		                ->with('message', '<b>Gagal!</b> Mohon lengkapi terlebih dahulu formulir berikut.');
		}

		$warga = new Warga();
		$warga->id_warga = $req->get('warga.id_warga');
		$warga->jenis_warga = $req->get('warga.jenis_warga');

		if (!$warga->save()) {
			return redirect('/warga/tambah')
	    		->with('type', 'danger')
	    		->with('message', '<b>Gagal!</b> Terjadi kesalahan pada sistem. Mohon hubungi administrator terkait hal ini.');
		}

		$warga->persyaratan()->attach($req->get('warga.persyaratan'));

		return redirect('/warga')
			->with('type', 'success')
			->with('message', '<b>Berhasil!</b> Warga berhasil ditambahkan.');
    }

    public function edit(Request $req, $id) {
    	$persyaratan = Persyaratan::all();

    	$warga = Warga::find($id);

    	if (!$warga) {
			return redirect('/warga')
				->with('type', 'danger')
				->with('message', '<b>Gagal!</b> Warga tersebut tidak ditemukan pada sistem.');
    	}

    	$selected_syarat = Persyaratan::whereHas('warga', function($query) use ($warga) {
    		$query->where('persyaratan_warga.id_warga', $warga->id_warga);
    	})->pluck('id_persyaratan')->toArray();

    	return view('warga.edit', [
    		'warga' => $warga,
    		'persyaratan' => $persyaratan,
    		'syarat_terpilih' => $selected_syarat
    	]);
    }

    public function update(Request $req) {
		$inputs = $req->all();

		$validator = Validator::make($inputs, [
		    'id_warga' => 'string|required|exists:warga,id_warga',
		    'jenis_warga' => 'string|required',
		    'persyaratan.*' => 'numeric|required|exists:persyaratan,id_persyaratan'
		]);

		if ($validator->fails()) {
		    return redirect('/warga/'.$req->get('warga.id_warga').'/edit')
		                ->withErrors($validator, 'form')
		                ->withInput()
		                ->with('type', 'danger')
		                ->with('message', '<b>Gagal!</b> Mohon lengkapi terlebih dahulu formulir berikut.');
		}

		$warga = Warga::find($req->get('warga.id_warga'));
		$warga->id_warga = $req->get('warga.id_warga');
		$warga->jenis_warga = $req->get('warga.jenis_warga');

		if (!$warga->save()) {
			return redirect('/warga/'.$req->get('warga.id_warga').'/edit')
	    		->with('type', 'danger')
	    		->with('message', '<b>Gagal!</b> Terjadi kesalahan pada sistem. Mohon hubungi administrator terkait hal ini.');
		}

		$warga->persyaratan()->sync($req->get('warga.persyaratan'));

		return redirect('/warga')
			->with('type', 'success')
			->with('message', '<b>Berhasil!</b> Warga berhasil diubah.');
    }

    public function delete(Request $req) {
    	$warga = Warga::find($req->get('warga.nik'));

    	if (!$warga) {
    	    return redirect('/warga')
	    	    ->with('type', 'danger')
	    	    ->with('message', '<b>Gagal!</b> Warga tersebut tidak ditemukan pada sistem.');
    	}

    	if (!$warga->delete()) {
    	    return redirect('/warga')
    	        ->with('type', 'danger')
    	        ->with('message', '<b>Gagal!</b> Terjadi kesalahan pada sistem. Mohon hubungi administrator terkait hal ini.');
    	}

    	return redirect('/warga')
    	    ->with('type', 'success')
    	    ->with('message', '<b>Berhasil!</b> Warga berhasil dihapus dari sistem.');
    }

    public function detail(Request $req) {
    	$warga = Warga::find($req->get('nik'));

    	if (!$warga) {
    	    return response()->json([
    	    	'message' => 'Warga tidak ditemukan pada sistem'
    	    ], 404);
    	}

    	return response()->json([
    		'warga' => $warga
    	], 200);
    }

    public function apiRegister(Request $req) {
		$inputs = $req->all();

		$validator = Validator::make($inputs, [
		    'warga.nik' => 'string|required|unique:warga,nik',
		    'warga.nama' => 'string|required',
		    'warga.tmpt_lahir' => 'string|required',
		    'warga.tgl_lahir' => 'date|required',
		    'warga.jk' => 'string|required|in:L,P',
		    'warga.alamat' => 'string|required',
		    'warga.rt' => 'numeric|required',
		    'warga.rw' => 'numeric|required',
		    'warga.agama' => 'string|required',
		    'warga.status' => [
		    	'string',
		        'required',
		        Rule::in(['KAWIN', 'BELUM KAWIN', 'CERAI'])
		    ],
		    'warga.pekerjaan' => 'string|required',
		    'warga.no_kk' => 'string|required',
		    'warga.email' => 'email|required',
		    'warga.password' => 'string|required'
		]);

		if ($validator->fails()) {
		    return response()->json([
		    	'message' => 'Permintaan tidak valid. Silakan cek kembali formulir yang Anda isi.',
		    	'errors' => $validator->errors()
		    ], 400);
		}

		$warga = new Warga();
		$warga->nik = $req->input('warga.nik');
		$warga->nama = $req->input('warga.nama');
		$warga->tmpt_lahir = $req->input('warga.tmpt_lahir');
		$warga->tgl_lahir = $req->input('warga.tgl_lahir');
		$warga->jk = $req->input('warga.jk');
		$warga->alamat = str_replace(',', ' ', $req->input('warga.alamat'));
		$warga->rt = $req->input('warga.rt');
		$warga->rw = $req->input('warga.rw');
		$warga->agama = $req->input('warga.agama');
		$warga->status = $req->input('warga.status');
		$warga->pekerjaan = $req->input('warga.pekerjaan');
		$warga->no_kk = $req->input('warga.no_kk');
		$warga->email = $req->input('warga.email');
		$warga->password = Hash::make($req->input('warga.password'));

		if (!$warga->save()) {
			return response()->json([
				'warga' => 'Terjadi kesalahan pada sistem. Mohon hubungi administrator terkait hal ini.'
			], 500);
		}

    	return response()->json([
    		'warga' => 'Berhasil! Anda telah terdaftar pada sistem. Silakan masuk untuk melanjutkan.'
    	], 200);
    }

    public function apiLogin(Request $req) {
    	$inputs = $req->all();

    	$validator = Validator::make($inputs, [
    	    'nik' => 'string|required|exists:warga,nik',
    	    'password' => 'string|required',
    	]);

    	if ($validator->fails()) {
    	    return response()->json([
    	    	'message' => 'Kombinasi NIK dan password tidak dikenal.'
    	    ], 400);
    	}

    	$warga = Warga::where('nik', $req->json('nik'))->first();

    	if (!Hash::check($req->json('password'), $warga->password)) {
    	    return response()->json([
    	    	'message' => 'Kombinasi NIK dan password tidak dikenal.'
    	    ], 401);
    	}

    	$token = md5($warga->nik . Carbon::now()->format('Y-m-d H:i:s'));

    	$sesi = new Sesi();
    	$sesi->nik = $warga->nik;
    	$sesi->token = $token;
    	$sesi->ip_address = $req->ip();
    	$sesi->waktu_masuk = Carbon::now();

    	if (!$sesi->save()) {
    		return response()->json([
    			'message' => 'Terjadi kesalahan pada server. Silakan hubungi administrator terkait hal ini.'
    		], 500);
    	}

    	return response()->json([
    		'message' => 'Anda berhasil masuk.',
    		'token' => $token,
    		'warga' => $warga
    	], 200);
    }

    public function apiLogout(Request $req) {
    	$token = $req->json('token');
    	$sesi = Sesi::where('token', $token)->first();
    	$sesi->waktu_keluar = Carbon::now();

    	if (!$sesi->save()) {
    		return response()->json([
    			'message' => 'Terjadi kesalahan pada server. Silakan hubungi administrator terkait hal ini.'
    		], 500);
    	}

    	return response()->json([
    		'message' => 'Anda berhasil keluar.'
    	], 200);
    }

    public function apiCheck(Request $req) {
    	$token = $req->json('token');

    	$sesi = Sesi::where('token', $token)->first();

    	return response()->json([
	    	'warga' => $sesi->warga
	    ], 200);
    }
}
