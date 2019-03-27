<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Pengaduan;
use App\KomentarPengaduan;
use Illuminate\Validation\Rule;
use App\Sesi;
use App\UploadPengaduan;
use Carbon\Carbon;
use Input;
use Auth;

class PengaduanController extends Controller
{
    public function __construct() {
    	$this->middleware('auth', ['except' => ['apiIndex', 'apiCreate', 'apiNewNumber', 'apiDetail', 'apiNewComment']]);
    	$this->middleware('warga_auth', ['only' => ['apiIndex', 'apiCreate', 'apiNewNumber', 'apiDetail', 'apiNewComment']]);
    }

    public function index() {
        return view('pengaduan.index');
    }

    public function dtIndex() {
        $pengaduan = Pengaduan::with([
            'warga' => function($q) {
                $q->select('nik', 'nama');
            }
        ])->get();


        return datatables()->of($pengaduan)->toJson();
    }

    public function detail($id) {
        $pengaduan = Pengaduan::with('warga', 'user', 'komentar', 'upload')->withCount('komentar', 'upload')->find($id);

        if (!$pengaduan) {
            return redirect('/pengaduan')
                ->with('type', 'danger')
                ->with('message', '<b>Gagal!</b> Permohonan tersebut tidak ditemukan pada sistem.');
        }

        return view('pengaduan.detail', [
            'pengaduan' => $pengaduan
        ]);
    }

    public function apiIndex(Request $req) {
    	$sesi = Sesi::where('token', $req->get('token'))->first();
    	$pengaduan = Pengaduan::withCount('komentar')->where('nik', $sesi->nik)->orderBy('id_pengaduan', 'DESC')->get();

    	return response()->json([
    		'pengaduan' => $pengaduan
    	]);
    }

    public function apiCreate(Request $req) {
    	$inputs = $req->all();

    	$validator = Validator::make($inputs, [
    	    'pengaduan.jenis_pengaduan' => [
		    	'string',
		        'required',
		        Rule::in(['PELAYANAN', 'NON PELAYANAN'])
		    ],
		    'pengaduan.isi_pengaduan' => 'required'
    	]);

    	if ($validator->fails()) {
    	    return response()->json([
    	    	'message' => 'Permintaan tidak valid. Silakan cek kembali formulir yang Anda isi.',
    	    	'errors' => $validator->errors()
    	    ], 400);
    	}

    	$sesi = Sesi::where('token', $req->get('token'))->first();

    	$last_no_pengaduan = Pengaduan::whereMonth('created_at', Carbon::now()->month)->orderBy('created_at', 'desc')->first();

    	$pengaduan = new Pengaduan();
    	$pengaduan->no_pengaduan = $last_no_pengaduan ? $last_no_pengaduan->no_pengaduan + 1 : 1;
    	$pengaduan->jenis_pengaduan = $req->input('pengaduan.jenis_pengaduan');
    	$pengaduan->isi_pengaduan = $req->input('pengaduan.isi_pengaduan');
    	$pengaduan->status = '0';
    	$pengaduan->nik = $sesi->nik;

    	if (!$pengaduan->save()) {
    		return response()->json([
    			'message' => 'Terjadi kesalahan pada sistem. Mohon hubungi administrator terkait hal ini.'
    		], 500);
    	}

    	if ($req->input('pengaduan.file')) {
    		$filename = $sesi->nik."/ADUAN/".md5($pengaduan->id_pengaduan.Carbon::now()->format('YmdHis'));
    		$saved_path = UploadPengaduan::saveToFile($filename, $req->input('pengaduan.file'));

    		if ($saved_path == null) {
    			return response()->json([
    				'message' => 'Terjadi kesalahan pada server. Mohon hubungi administrator terkait hal ini.'
    			], 500);
    		}

    		$upload = new UploadPengaduan();
    		$upload->id_pengaduan = $pengaduan->id_pengaduan;
    		$upload->path = $saved_path;

    		if (!$upload->save()) {
    			return response()->json([
    				'message' => 'Terjadi kesalahan pada sistem. Mohon hubungi administrator terkait hal ini.'
    			], 500);
    		}

    		
    	}

    	return response()->json([
    		'message' => 'Pengaduan Anda berhasil dikirim.'
    	], 200);

    }

    public function apiNewNumber(Request $req) {
    	$last_no_pengaduan = Pengaduan::whereMonth('created_at', Carbon::now()->month)->orderBy('created_at', 'desc')->first();

    	$new = ($last_no_pengaduan == null ? 1 : $last_no_pengaduan->no_pengaduan + 1);

    	$no = [str_pad($new, 3, "0", STR_PAD_LEFT), "ADUAN", "ATG"];
        $no[] = $this->romanic_number(Carbon::now()->month);
        $no[] = Carbon::now()->year;

    	return response()->json([
    		'number' => implode('/', $no)
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

    public function komentar(Request $req) {
    	$inputs = Input::all();

    	$validator = Validator::make($inputs, [
    	    'id_pengaduan' => 'numeric|required|exists:pengaduan,id_pengaduan',
    	    'isi_komentar' => 'string|required',
    	]);

    	if ($validator->fails()) {
    	    return redirect('/pengaduan/'.$req->get('id_pengaduan').'/detail')
				->withErrors($validator, 'form')
				->withInput()
				->with('type', 'danger')
				->with('message', '<b>Gagal!</b> Mohon lengkapi terlebih dahulu formulir berikut.');
    	}

    	$komentar = new KomentarPengaduan();
    	$komentar->id_pengaduan = $req->get('id_pengaduan');
    	$komentar->id_user = Auth::getUser()->id_user;
    	$komentar->nik = null;
    	$komentar->status = '0';
    	$komentar->isi_komentar = $req->get('isi_komentar');

    	if (!$komentar->save()) {
    	    return redirect('/pengaduan/'.$req->get('id_pengaduan').'/detail')
	    		->with('type', 'danger')
	    		->with('message', '<b>Gagal!</b> Terjadi kesalahan pada sistem. Mohon hubungi administrator terkait hal ini.');
    	}

    	return redirect('/pengaduan/'.$req->get('id_pengaduan').'/detail')
    		->with('type', 'success')
    		->with('message', '<b>Berhasil!</b> Komentar berhasil disimpan.');
    }

    public function close(Request $req) {
    	$inputs = Input::all();

    	$validator = Validator::make($inputs, [
    	    'id_pengaduan' => 'numeric|required|exists:pengaduan,id_pengaduan'
    	]);

    	if ($validator->fails()) {
    	    return redirect('/pengaduan/'.$req->get('id_pengaduan').'/detail')
				->withErrors($validator, 'form')
				->withInput()
				->with('type', 'danger')
				->with('message', '<b>Gagal!</b> Pengaduan tidak ditemukan pada sistem.');
    	}

    	$pengaduan = Pengaduan::find($req->get('id_pengaduan'));
    	$pengaduan->status = 1;

    	if (!$pengaduan->save()) {
    	    return redirect('/pengaduan/'.$req->get('id_pengaduan').'/detail')
	    		->with('type', 'danger')
	    		->with('message', '<b>Gagal!</b> Terjadi kesalahan pada sistem. Mohon hubungi administrator terkait hal ini.');
    	}

    	return redirect('/pengaduan/'.$req->get('id_pengaduan').'/detail')
    		->with('type', 'success')
    		->with('message', '<b>Berhasil!</b> Pengaduan ini telah berhasil ditandai selesai.');
    }

    public function apiDetail(Request $req) {
    	$inputs = $req->all();

    	$validator = Validator::make($inputs, [
    	    'id_pengaduan' => 'numeric|required|exists:pengaduan,id_pengaduan'
    	]);

    	if ($validator->fails()) {
    	    return response()->json([
    	    	'message' => 'Pengaduan tidak ditemukan pada sistem.',
    	    ], 404);
    	}

    	$pengaduan = Pengaduan::with('komentar.warga', 'komentar.petugas', 'upload')
    		->with([
    			'komentar' => function($query) {
    				$query->orderBy('id', 'ASC');
    			}
    		])
    		->find($req->get('id_pengaduan'));

    	return response()->json([
    	    	'pengaduan' => $pengaduan,
    	    ], 200);
    }

    public function apiNewComment(Request $req) {
    	$inputs = $req->all();

    	$validator = Validator::make($inputs, [
    	    'komentar.id_pengaduan' => 'numeric|required|exists:pengaduan,id_pengaduan',
    	    'komentar.isi_komentar' => 'string|required'
    	]);

    	if ($validator->fails()) {
    	    return response()->json([
    	    	'message' => 'Isi terlebih dahulu form komentar.',
    	    ], 404);
    	}

    	$sesi = Sesi::where('token', $req->get('token'))->first();

    	$komentar = new KomentarPengaduan();
    	$komentar->id_pengaduan = $req->input('komentar.id_pengaduan');
    	$komentar->nik = $sesi->nik;
    	$komentar->status = '0';
    	$komentar->isi_komentar = $req->input('komentar.isi_komentar');

    	if (!$komentar->save()) {
    		return response()->json([
    			'message' => 'Terjadi kesalahan pada sistem. Mohon hubungi administrator terkait hal ini',
    		], 500);
    	}

    	return response()->json([
    	    	'message' => 'Berhasil mengirim komentar baru pada pengaduan',
    	    ], 200);
    }
}
