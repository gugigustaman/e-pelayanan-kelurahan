<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\Arsip;
use App\Persyaratan;
use App\Sesi;
use Validator;
use Hash;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class ArsipController extends Controller
{

	public function __construct() {
		$this->middleware('auth');
	}
    
    public function index() {
    	return view('arsip.index');
    }

    public function dtIndex() {
    	return datatables()->of(Arsip::with('permohonan.warga', 'permohonan.layanan')->get())->toJson();
    }

    public function create() {
    	$persyaratan = Persyaratan::all();
    	return view('arsip.create', ['persyaratan' => $persyaratan]);
    }

    public function save(Request $req) {
		$inputs = $req->all();

		$validator = Validator::make($inputs, [
		    'id_arsip' => 'string|required|unique:arsip,id_arsip',
		    'jenis_arsip' => 'string|required',
		    'persyaratan.*' => 'numeric|required|exists:persyaratan,id_persyaratan'
		]);

		if ($validator->fails()) {
		    return redirect('/arsip/tambah')
		                ->withErrors($validator, 'form')
		                ->withInput()
		                ->with('type', 'danger')
		                ->with('message', '<b>Gagal!</b> Mohon lengkapi terlebih dahulu formulir berikut.');
		}

		$arsip = new Arsip();
		$arsip->id_arsip = $req->get('arsip.id_arsip');
		$arsip->jenis_arsip = $req->get('arsip.jenis_arsip');

		if (!$arsip->save()) {
			return redirect('/arsip/tambah')
	    		->with('type', 'danger')
	    		->with('message', '<b>Gagal!</b> Terjadi kesalahan pada sistem. Mohon hubungi administrator terkait hal ini.');
		}

		$arsip->persyaratan()->attach($req->get('arsip.persyaratan'));

		return redirect('/arsip')
			->with('type', 'success')
			->with('message', '<b>Berhasil!</b> Arsip berhasil ditambahkan.');
    }

    public function edit(Request $req, $id) {
    	$persyaratan = Persyaratan::all();

    	$arsip = Arsip::find($id);

    	if (!$arsip) {
			return redirect('/arsip')
				->with('type', 'danger')
				->with('message', '<b>Gagal!</b> Arsip tersebut tidak ditemukan pada sistem.');
    	}

    	$selected_syarat = Persyaratan::whereHas('arsip', function($query) use ($arsip) {
    		$query->where('persyaratan_arsip.id_arsip', $arsip->id_arsip);
    	})->pluck('id_persyaratan')->toArray();

    	return view('arsip.edit', [
    		'arsip' => $arsip,
    		'persyaratan' => $persyaratan,
    		'syarat_terpilih' => $selected_syarat
    	]);
    }

    public function update(Request $req) {
		$inputs = $req->all();

		$validator = Validator::make($inputs, [
		    'id_arsip' => 'string|required|exists:arsip,id_arsip',
		    'jenis_arsip' => 'string|required',
		    'persyaratan.*' => 'numeric|required|exists:persyaratan,id_persyaratan'
		]);

		if ($validator->fails()) {
		    return redirect('/arsip/'.$req->get('arsip.id_arsip').'/edit')
		                ->withErrors($validator, 'form')
		                ->withInput()
		                ->with('type', 'danger')
		                ->with('message', '<b>Gagal!</b> Mohon lengkapi terlebih dahulu formulir berikut.');
		}

		$arsip = Arsip::find($req->get('arsip.id_arsip'));
		$arsip->id_arsip = $req->get('arsip.id_arsip');
		$arsip->jenis_arsip = $req->get('arsip.jenis_arsip');

		if (!$arsip->save()) {
			return redirect('/arsip/'.$req->get('arsip.id_arsip').'/edit')
	    		->with('type', 'danger')
	    		->with('message', '<b>Gagal!</b> Terjadi kesalahan pada sistem. Mohon hubungi administrator terkait hal ini.');
		}

		$arsip->persyaratan()->sync($req->get('arsip.persyaratan'));

		return redirect('/arsip')
			->with('type', 'success')
			->with('message', '<b>Berhasil!</b> Arsip berhasil diubah.');
    }

    public function delete(Request $req) {
    	$arsip = Arsip::find($req->get('arsip.nik'));

    	if (!$arsip) {
    	    return redirect('/arsip')
	    	    ->with('type', 'danger')
	    	    ->with('message', '<b>Gagal!</b> Arsip tersebut tidak ditemukan pada sistem.');
    	}

    	if (!$arsip->delete()) {
    	    return redirect('/arsip')
    	        ->with('type', 'danger')
    	        ->with('message', '<b>Gagal!</b> Terjadi kesalahan pada sistem. Mohon hubungi administrator terkait hal ini.');
    	}

    	return redirect('/arsip')
    	    ->with('type', 'success')
    	    ->with('message', '<b>Berhasil!</b> Arsip berhasil dihapus dari sistem.');
    }

    public function detail(Request $req) {
    	$arsip = Arsip::find($req->get('nik'));

    	if (!$arsip) {
    	    return response()->json([
    	    	'message' => 'Arsip tidak ditemukan pada sistem'
    	    ], 404);
    	}

    	return response()->json([
    		'arsip' => $arsip
    	], 200);
    }

    public function download(Request $req) {
    	$inputs = $req->all();

    	$validator = Validator::make($inputs, [
    	    'id_arsip' => 'string|required|exists:arsip,id'
    	]);

    	if ($validator->fails()) {
    	    return redirect('/arsip/'.$req->get('arsip.id_arsip').'/edit')
    	                ->withErrors($validator, 'form')
    	                ->withInput()
    	                ->with('type', 'danger')
    	                ->with('message', '<b>Gagal!</b> Mohon lengkapi terlebih dahulu formulir berikut.');
    	}

		$arsip = Arsip::find($req->get('id_arsip'));

		return response()->json([
			'url' => str_replace('storage', '/storage', $arsip->file_path)
		]);
    }


}
