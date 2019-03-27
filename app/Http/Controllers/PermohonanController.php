<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\Permohonan;
use App\UploadPersyaratan;
use App\Sesi;
use App\Layanan;
use App\Arsip;
use Carbon\Carbon;
use Validator;
use Auth;
use Storage;
use icircle\Template\Docx\DocxTemplate;

class PermohonanController extends Controller
{
	public function __construct() {
		$this->middleware('auth', ['except' => ['apiIndex', 'apiCreate', 'apiNewNumber']]);
		$this->middleware('warga_auth', ['only' => ['apiIndex', 'apiCreate', 'apiNewNumber']]);
	}
    
    public function index() {
    	return view('permohonan.index');
    }

    public function dtIndex() {
    	$permohonan = Permohonan::with([
    		'warga' => function($q) {
    			$q->select('nik', 'nama');
    		},
    		'layanan' => function($q) {
    			$q->select('id_layanan', 'jenis_layanan');
    		}
    	])->get();


    	return datatables()->of($permohonan)->toJson();
    }

    public function edit($id) {
    	$layanan = Layanan::all();
		$permohonan = Permohonan::with('layanan', 'warga', 'user', 'persyaratan')->find($id);

		if (!$permohonan) {
			return redirect('/permohonan')
				->with('type', 'danger')
				->with('message', '<b>Gagal!</b> Permohonan tersebut tidak ditemukan pada sistem.');
		}

		return view('permohonan.edit', [
			'permohonan' => $permohonan,
			'layanan' => $layanan
		]);
    }

    public function apiIndex(Request $req) {
    	$sesi = Sesi::where('token', $req->get('token'))->first();
    	$permohonan = Permohonan::with([
    		'layanan' => function($q) {
    			$q->select('id_layanan', 'jenis_layanan');
    		}
    	])->where('nik', $sesi->nik)->orderBy('id_permohonan', 'DESC')->get();

    	return response()->json([
    		'permohonan' => $permohonan
    	]);
    }

    public function apiCreate(Request $req) {
		$inputs = $req->all();

		$validator = Validator::make($inputs, [
		    'permohonan.id_layanan' => 'string|required|exists:layanan,id_layanan',
		    'permohonan.keterangan' => 'string|required',
		    'permohonan.persyaratan.*.id_persyaratan' => 'required|exists:persyaratan,id_persyaratan',
		    'permohonan.persyaratan.*.file' => 'required'
		]);

		if ($validator->fails()) {
		    return response()->json([
		    	'message' => 'Permintaan tidak valid. Silakan cek kembali formulir yang Anda isi.',
		    	'errors' => $validator->errors()
		    ], 400);
		}

		$sesi = Sesi::where('token', $req->get('token'))->first();

		$tgl = Carbon::now();

		$last_no_sp = Permohonan::where('id_layanan', $req->input('permohonan.id_layanan'))->whereMonth('tgl_sp', $tgl->month)->orderBy('created_at', 'desc')->first();

		$permohonan = new Permohonan();
		$permohonan->id_layanan = $req->input('permohonan.id_layanan');
		$permohonan->no_sp = $last_no_sp == null ? 1 : $last_no_sp->no_sp + 1;
		$permohonan->tgl_sp = $tgl;
		$permohonan->keterangan = $req->input('permohonan.keterangan');
		$permohonan->status = '0';
		$permohonan->nik = $sesi->nik;

		if (!$permohonan->save()) {
			return response()->json([
				'message' => 'Terjadi kesalahan pada sistem. Mohon hubungi administrator terkait hal ini.'
			], 500);
		}

		foreach($req->input('permohonan.persyaratan') as $syarat) {
			// $filename = $sesi->nik."_".$syarat['id_persyaratan']."_".$tgl->timestamp;
			$filename = $sesi->nik."/".md5($syarat['id_persyaratan'].$tgl->timestamp);
			$saved_path = UploadPersyaratan::saveToFile($filename, $syarat['file']);

			if ($saved_path == null) {
				return response()->json([
					'message' => 'Terjadi kesalahan pada server. Mohon hubungi administrator terkait hal ini.'
				], 500);
			}

			$permohonan->persyaratan()->attach($syarat['id_persyaratan'], ['path' => $saved_path]);
		}

    	return response()->json([
    		'message' => 'Permohonan Anda berhasil dikirim.'
    	], 200);
    }

    public function update(Request $req) {
    	$inputs = $req->all();

    	$validator = Validator::make($inputs, [
    	    'id_permohonan' => 'required|exists:permohonan,id_permohonan',
    	    'status' => 'required|in:0,1,2,3',
    	    'alasan_tolak' => 'requiredIf:status,3'
    	]);

    	if ($validator->fails()) {
    	    return redirect('/permohonan'.$req->get('id_permohonan').'/edit')
    	    	->with('type', 'danger')
    	    	->with('message', '<b>Gagal!</b> Permohonan tersebut tidak ditemukan pada sistem.');
    	}

    	$permohonan = Permohonan::find($req->input('id_permohonan'));
    	$permohonan->status = $req->input('status');
    	$permohonan->id_user = Auth::getUser()->id_user;
    	
    	if ($req->get('status') == '3') {
    		$permohonan->alasan_tolak = $req->get('alasan_tolak');
    	}

    	if ($permohonan->status == "2") {

			if ($permohonan->layanan->template_path == "") {
				return redirect('/permohonan'.$req->get('id_permohonan').'/edit')
					->with('type', 'danger')
					->with('message', '<b>Gagal!</b> Template surat belum ada untuk permohonan layanan tersebut. Silakan unggah terlebih dahulu untuk mulai menggunakan.');
			}

			if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
			    $filename = str_replace('\storage\storage', '\storage\app\public', storage_path($permohonan->layanan->template_path));
			    $filename = str_replace('/', '\\', $filename);
			} else {
			    $filename = str_replace('/storage/storage', '/storage/app/public', storage_path($permohonan->layanan->template_path));
			}

			$docxTemplate = new DocxTemplate($filename);
			$dataArray = [
			    'no' => $permohonan->no,
			    'nama' => $permohonan->warga->nama,
			    'nik' => $permohonan->nik,
			    'no_kk' => $permohonan->warga->no_kk,
			    'jk' => $permohonan->warga->jk == "L" ? "Laki-laki" : "Perempuan",
			    'tmpt_lahir' => strtoupper($permohonan->warga->tmpt_lahir),
			    'tgl_lahir' => strtoupper(Carbon::parse($permohonan->warga->tgl_lahir)->format('d-m-Y')),
			    'status' => $permohonan->warga->status,
			    'pekerjaan' => $permohonan->warga->pekerjaan,
			    'agama' => $permohonan->warga->agama,
			    'alamat' => $permohonan->warga->alamat.", RT. ".str_pad($permohonan->warga->rt, 3, "0", STR_PAD_LEFT)." RW. ".str_pad($permohonan->warga->rw, 3, "0", STR_PAD_LEFT),
			    'tanggal' => Carbon::now()->format('d M Y')
			];

			$filename = 'storage/arsip/'.$permohonan->id_layanan.'_'.Carbon::now()->format('Ymd_His').'.docx';

			$docxTemplate->merge($dataArray, $filename);

			$arsip = new Arsip();
			$arsip->id_permohonan = $permohonan->id_permohonan;
			$arsip->file_path = $filename;

			if (!$arsip->save()) {
				return redirect('/permohonan/'.$req->input('id_permohonan').'/edit')
					->with('type', 'danger')
					->with('message', '<b>Gagal!</b> Terdapat kesalahan pada server. Mohon hubungi admin terkait hal ini.');
			}
		}

    	if (!$permohonan->save()) {
    		return redirect('/permohonan/'.$req->input('id_permohonan').'/edit')
    			->with('type', 'danger')
    			->with('message', '<b>Gagal!</b> Terdapat kesalahan pada server. Mohon hubungi admin terkait hal ini.');
    	}

    	return redirect('/permohonan/'.$req->input('id_permohonan').'/edit')
    			->with('type', 'success')
    			->with('message', '<b>Berhasil!</b> Permohonan berhasil diperbarui.');
    }

    public function apiNewNumber(Request $req) {
    	$inputs = $req->all();

    	$validator = Validator::make($inputs, [
    	    'id_layanan' => 'string|required|exists:layanan,id_layanan'
    	]);

    	$last_no_sp = Permohonan::where('id_layanan', $req->input('id_layanan'))->whereMonth('tgl_sp', Carbon::now()->month)->orderBy('created_at', 'desc')->first();

		$new = ($last_no_sp == null ? 1 : $last_no_sp->no_sp + 1);

		$no = [str_pad($new, 3, "0", STR_PAD_LEFT), $req->input('id_layanan'), "ATG"];
	    $no[] = $this->romanic_number(Carbon::now()->month);
	    $no[] = Carbon::now()->year;

		return response()->json([
			'number' => implode('/', $no)
		]);
    }

    public function preview(Request $req) {
    	$inputs = $req->all();

    	$validator = Validator::make($inputs, [
    	    'id_permohonan' => 'string|required|exists:permohonan,id_permohonan'
    	]);

    	$permohonan = Permohonan::find($req->input('id_permohonan'));

    	if (!$permohonan) {
    		return response()->json([
    			'message' => 'Permohonan tersebut tidak ditemukan pada sistem.'
    		]);
    	}

    	if ($permohonan->layanan->template_path == "") {
    		return response()->json([
    			'message' => 'Template surat belum ada untuk permohonan layanan tersebut. Silakan unggah terlebih dahulu untuk mulai menggunakan.'
    		]);
    	}

    	if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
            $filename = str_replace('\storage\storage', '\storage\app\public', storage_path($permohonan->layanan->template_path));
            $filename = str_replace('/', '\\', $filename);
        } else {
            $filename = str_replace('/storage/storage', '/storage/app/public', storage_path($permohonan->layanan->template_path));
        }

    	$docxTemplate = new DocxTemplate($filename);
        $dataArray = [
            'no' => $permohonan->no,
            'nama' => $permohonan->warga->nama,
            'nik' => $permohonan->nik,
            'no_kk' => $permohonan->warga->no_kk,
            'jk' => $permohonan->warga->jk == "L" ? "Laki-laki" : "Perempuan",
            'tmpt_lahir' => strtoupper($permohonan->warga->tmpt_lahir),
            'tgl_lahir' => strtoupper(Carbon::parse($permohonan->warga->tgl_lahir)->format('d-m-Y')),
            'status' => $permohonan->warga->status,
            'pekerjaan' => $permohonan->warga->pekerjaan,
            'agama' => $permohonan->warga->agama,
            'alamat' => $permohonan->warga->alamat.", RT. ".str_pad($permohonan->warga->rt, 3, "0", STR_PAD_LEFT)." RW. ".str_pad($permohonan->warga->rw, 3, "0", STR_PAD_LEFT),
            'tanggal' => Carbon::now()->format('d M Y')
        ];

        $filename = 'storage/arsip/'.$permohonan->id_layanan.'_'.Carbon::now()->format('Ymd_His').'.docx';

        $docxTemplate->merge($dataArray, $filename);

        if ($permohonan->status !== "2") {
			return response()->json([
				'url' => str_replace('storage', '/storage', $filename)
			]);
        }

        $arsip = new Arsip();
        $arsip->id_permohonan = $permohonan->id_permohonan;
        $arsip->file_path = $filename;

        if (!$arsip->save()) {
        	return response()->json([
        		'message' => 'Terdapat kesalahan pada server. Mohon hubungi admin terkait hal ini.'
        	]);
        }

        return response()->json([
        	'url' => str_replace('storage', '/storage', $arsip->file_path)
		]);
    }

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

    public function stats(Request $req) {
    	$layanan = Layanan::withCount('permohonan_menunggu', 'permohonan_proses', 'permohonan_selesai', 'permohonan_ditolak')->get();

    	return response()->json([
        	'layanan' => $layanan
		]);
    }
}
