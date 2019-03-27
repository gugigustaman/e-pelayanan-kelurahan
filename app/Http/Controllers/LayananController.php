<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\Layanan;
use App\Persyaratan;
use Input;
use Validator;
use Carbon\Carbon;
use Storage;
use icircle\Template\Docx\DocxTemplate;

class LayananController extends Controller
{
    
    public function __construct() {
    	$this->middleware('auth', ['except' => ['apiIndex', 'apiDetail']]);
    	$this->middleware('warga_auth', ['only' => ['apiIndex', 'apiDetail']]);
    }

    public function index() {
    	return view('layanan.index');
    }

    public function dtIndex() {
    	return datatables()->of(Layanan::with('persyaratan')->get())->toJson();
    }

    public function create() {
    	$persyaratan = Persyaratan::all();
    	return view('layanan.create', ['persyaratan' => $persyaratan]);
    }

    public function save(Request $req) {
		$inputs = Input::all();

		$validator = Validator::make($inputs, [
		    'id_layanan' => 'string|required|unique:layanan,id_layanan',
            'jenis_layanan' => 'string|required',
		    'template' => 'file|required',
		    'persyaratan.*' => 'numeric|required|exists:persyaratan,id_persyaratan'
		]);

		if ($validator->fails()) {
		    return redirect('/layanan/tambah')
		                ->withErrors($validator, 'form')
		                ->withInput()
		                ->with('type', 'danger')
		                ->with('message', '<b>Gagal!</b> Mohon lengkapi terlebih dahulu formulir berikut.');
		}

        $filename = $req->get('id_layanan').'.docx';

        if (!$req->template->storeAs('public/templates/', $filename)) {
            return redirect('/layanan/tambah')
                ->with('type', 'danger')
                ->with('message', '<b>Gagal!</b> Terjadi kesalahan pada sistem. Mohon hubungi administrator terkait hal ini.');
        }

		$layanan = new Layanan();
		$layanan->id_layanan = $req->get('id_layanan');
		$layanan->jenis_layanan = $req->get('jenis_layanan');
        $layanan->template_path = 'storage/templates/'.$filename;

		if (!$layanan->save()) {
			return redirect('/layanan/tambah')
	    		->with('type', 'danger')
	    		->with('message', '<b>Gagal!</b> Terjadi kesalahan pada sistem. Mohon hubungi administrator terkait hal ini.');
		}

		$layanan->persyaratan()->attach($req->get('persyaratan'));

		return redirect('/layanan')
			->with('type', 'success')
			->with('message', '<b>Berhasil!</b> Layanan berhasil ditambahkan.');
    }

    public function edit(Request $req, $id) {
    	$persyaratan = Persyaratan::all();

    	$layanan = Layanan::find($id);

    	if (!$layanan) {
			return redirect('/layanan')
				->with('type', 'danger')
				->with('message', '<b>Gagal!</b> Layanan tersebut tidak ditemukan pada sistem.');
    	}

    	$selected_syarat = Persyaratan::whereHas('layanan', function($query) use ($layanan) {
    		$query->where('persyaratan_layanan.id_layanan', $layanan->id_layanan);
    	})->pluck('id_persyaratan')->toArray();

    	return view('layanan.edit', [
    		'layanan' => $layanan,
    		'persyaratan' => $persyaratan,
    		'syarat_terpilih' => $selected_syarat
    	]);
    }

    public function update(Request $req) {
		$inputs = Input::all();

		$validator = Validator::make($inputs, [
		    'id_layanan' => 'string|required|exists:layanan,id_layanan',
            'jenis_layanan' => 'string|required',
            'template_path' => 'nullable|file',
		    'persyaratan.*' => 'numeric|required|exists:persyaratan,id_persyaratan'
		]);

		if ($validator->fails()) {
		    return redirect('/layanan/'.$req->get('id_layanan').'/edit')
		                ->withErrors($validator, 'form')
		                ->withInput()
		                ->with('type', 'danger')
		                ->with('message', '<b>Gagal!</b> Mohon lengkapi terlebih dahulu formulir berikut.');
		}

        if ($req->template) {
            $filename = $req->get('id_layanan').'.docx';

            if (!$req->template->storeAs('public/templates/', $filename)) {
                return redirect('/layanan/tambah')
                    ->with('type', 'danger')
                    ->with('message', '<b>Gagal!</b> Terjadi kesalahan pada sistem. Mohon hubungi administrator terkait hal ini.');
            }
        }

		$layanan = Layanan::find($req->get('id_layanan'));
		$layanan->id_layanan = $req->get('id_layanan');
        $layanan->jenis_layanan = $req->get('jenis_layanan');

        if ($req->template) {
            $layanan->template_path = 'storage/templates/'.$filename;
        }

		if (!$layanan->save()) {
			return redirect('/layanan/'.$req->get('id_layanan').'/edit')
	    		->with('type', 'danger')
	    		->with('message', '<b>Gagal!</b> Terjadi kesalahan pada sistem. Mohon hubungi administrator terkait hal ini.');
		}

		$layanan->persyaratan()->sync($req->get('persyaratan'));

		return redirect('/layanan')
			->with('type', 'success')
			->with('message', '<b>Berhasil!</b> Layanan berhasil diubah.');
    }

    public function delete(Request $req) {
    	$layanan = Layanan::find($req->get('id_layanan'));

    	if (!$layanan) {
    	    return redirect('/layanan')
	    	    ->with('type', 'danger')
	    	    ->with('message', '<b>Gagal!</b> Layanan tersebut tidak ditemukan pada sistem.');
    	}

        if (!Storage::delete('public/templates/'.$req->get('id_layanan').'.docx')) {
            return redirect('/layanan')
                ->with('type', 'danger')
                ->with('message', '<b>Gagal!</b> Terjadi kesalahan pada sistem. Mohon hubungi administrator terkait hal ini.');
        }

    	if (!$layanan->delete()) {
    	    return redirect('/layanan')
    	        ->with('type', 'danger')
    	        ->with('message', '<b>Gagal!</b> Terjadi kesalahan pada sistem. Mohon hubungi administrator terkait hal ini.');
    	}

    	return redirect('/layanan')
    	    ->with('type', 'success')
    	    ->with('message', '<b>Berhasil!</b> Layanan berhasil dihapus dari sistem.');
    }

    public function apiIndex(Request $req) {
    	$layanan = Layanan::get(['id_layanan', 'jenis_layanan']);

    	return response()->json([
    		'layanan' => $layanan
    	]);
    }

    public function apiDetail(Request $req) {

    	$inputs = Input::all();

    	$validator = Validator::make($inputs, [
    	    'id_layanan' => 'string|required|exists:layanan,id_layanan'
    	]);

    	if ($validator->fails()) {
    	    return redirect('/layanan/'.$req->get('id_layanan').'/edit')
    	                ->withErrors($validator, 'form')
    	                ->withInput()
    	                ->with('type', 'danger')
    	                ->with('message', 'Layanan tidak ditemukan.');
    	}

    	$layanan = Layanan::with(['persyaratan' => function($query) {
    		$query->get(['persyaratan.id_persyaratan', 'jenis_persyaratan']);
    	}])->find($req->get('id_layanan'));

    	return response()->json([
    		'layanan' => $layanan
    	]);
    }

    public function test() {
        // $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(public_path('/storage/SKD_TEMPLATE.docx'));
        // $templateProcessor->setValue('no', '0001/SKD/KEL-ATG/VI/2018');
        // $templateProcessor->setValue('nama', 'Gugi Gustaman');
        // $templateProcessor->setValue('nik', '3273152708920003');
        // $templateProcessor->setValue('no_kk', '3273152708920001');
        // $templateProcessor->setValue('jk', 'Laki-laki');
        // $templateProcessor->setValue('tmpt_lahir', 'BANDUNG');
        // $templateProcessor->setValue('tgl_lahir', '27 AGUSTUS 1992');
        // $templateProcessor->setValue('status', 'BELUM KAWIN');
        // $templateProcessor->setValue('pekerjaan', 'Karyawan Swasta');
        // $templateProcessor->setValue('agama', 'Islam');
        // $templateProcessor->setValue('alamat', 'Jl. Jend. Sudirman, Gg. Manunggal IIC, RT. 007 RW. 001 No. 54');
        // $templateProcessor->setValue('tanggal', Carbon::now()->format('d M Y'));

        // $templateProcessor->saveAs('test.docx');
        // $phpWord = \PhpOffice\PhpWord\IOFactory::load('test.docx'); // Read the temp file
        // $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        // $xmlWriter->save('result.docx');

        // header("Location: result.docx");


        $docxTemplate = new DocxTemplate(storage_path('SKD_TEMPLATE.docx'));
        $dataArray = [
            'no' => '0001/SKD/KEL-ATG/VI/2018',
            'nama' => 'Gugi Gustaman',
            'nik' => '3273152708920003',
            'no_kk' => '3273152708920001',
            'jk' => 'Laki-laki',
            'tmpt_lahir' => 'BANDUNG',
            'tgl_lahir' => '27 AGUSTUS 1992',
            'status' => 'BELUM KAWIN',
            'pekerjaan' => 'Karyawan Swasta',
            'agama' => 'Islam',
            'alamat' => 'Jl. Jend. Sudirman => Gg. Manunggal IIC => RT. 007 RW. 001 No. 54',
            'tanggal' => Carbon::now()->format('d M Y')
        ];

        $docxTemplate->merge($dataArray,'test.docx');

    }
}
