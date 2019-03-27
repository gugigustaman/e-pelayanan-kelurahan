<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\User;
use Input;
use Validator;
use Hash;

class PetugasController extends Controller
{
    public function index() {
    	return view('petugas.index');
    }

    public function dtIndex() {
    	return datatables()->of(User::all())->toJson();
    }

    public function create() {
    	return view('petugas.create');
    }

    public function save(Request $req) {
		$inputs = Input::all();

		$validator = Validator::make($inputs, [
		    'nama_user' => 'string|required|unique:user,nama_user',
		    'password' => 'string|required',
		    'nama_petugas' => 'string|required',
		    'jabatan' => 'string|required',
		    'lvl_user' => 'required|in:1,2,3',
		    'alamat' => 'string|required',
		]);

		if ($validator->fails()) {
		    return redirect('/petugas/tambah')
		                ->withErrors($validator, 'form')
		                ->withInput()
		                ->with('type', 'danger')
		                ->with('message', '<b>Gagal!</b> Mohon lengkapi terlebih dahulu formulir berikut.');
		}

		$user = new User();
		$user->nama_user = $req->get('nama_user');
		$user->password = Hash::make($req->get('password'));
		$user->nama_petugas = $req->get('nama_petugas');
		$user->jabatan = $req->get('jabatan');
		$user->lvl_user = $req->get('lvl_user');
		$user->alamat = $req->get('alamat');

		if (!$user->save()) {
			return redirect('/petugas/tambah')
	    		->with('type', 'danger')
	    		->with('message', '<b>Gagal!</b> Terjadi kesalahan pada sistem. Mohon hubungi administrator terkait hal ini.');
		}

		return redirect('/petugas')
			->with('type', 'success')
			->with('message', '<b>Berhasil!</b> Petugas berhasil ditambahkan.');
    }

    public function edit(Request $req, $id) {
    	$user = User::find($id);

    	if (!$user) {
			return redirect('/petugas')
	    		->with('type', 'danger')
	    		->with('message', '<b>Gagal!</b> Data petugas tidak ditemukan.');
    	}

    	return view('petugas.edit', ['user' => $user]);
    }

	public function update(Request $req) {
		$inputs = Input::all();

		$validator = Validator::make($inputs, [
		    'id_user' => 'numeric|required|exists:user,id_user',
		    // 'nama_user' => 'string|required|unique:user,nama_user',
		    'nama_petugas' => 'string|required',
		    'jabatan' => 'string|required',
		    'lvl_user' => 'required|in:1,2',
		    'alamat' => 'string|required',
		]);

		if ($validator->fails()) {
		    return redirect('/petugas/tambah')
		                ->withErrors($validator, 'form')
		                ->withInput()
		                ->with('type', 'danger')
		                ->with('message', '<b>Gagal!</b> Mohon lengkapi terlebih dahulu formulir berikut.');
		}

		$user = User::find($req->get('id_user'));
		// $user->nama_user = $req->get('nama_user');
		
		if ($req->get('password')) {
			$user->password = Hash::make($req->get('password'));
		}

		$user->nama_petugas = $req->get('nama_petugas');
		$user->jabatan = $req->get('jabatan');
		$user->lvl_user = $req->get('lvl_user');
		$user->alamat = $req->get('alamat');

		if (!$user->save()) {
			return redirect('/petugas/tambah')
	    		->with('type', 'danger')
	    		->with('message', '<b>Gagal!</b> Terjadi kesalahan pada sistem. Mohon hubungi administrator terkait hal ini.');
		}

		return redirect('/petugas')
			->with('type', 'success')
			->with('message', '<b>Berhasil!</b> Petugas berhasil diubah.');
    }

    public function delete(Request $req) {
    	$user = User::find($req->get('id'));

    	if (!$user) {
    	    return redirect('/petugas')
	    	    ->with('type', 'danger')
	    	    ->with('message', '<b>Gagal!</b> Petugas tersebut tidak ditemukan pada sistem.');
    	}

    	if (!$user->delete()) {
    	    return redirect('/petugas')
    	        ->with('type', 'danger')
    	        ->with('message', '<b>Gagal!</b> Terjadi kesalahan pada sistem. Mohon hubungi administrator terkait hal ini.');
    	}

    	return redirect('/petugas')
    	    ->with('type', 'success')
    	    ->with('message', '<b>Berhasil!</b> Petugas berhasil dihapus dari sistem.');
    }
}
