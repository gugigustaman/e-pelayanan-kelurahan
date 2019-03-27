@extends('layouts.app')

@section('title', 'Ubah Layanan')

@section('styles')

@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card card-outline-primary">
            <div class="card-body">
            	<h4 class="card-title">Ubah Layanan <a href="{!! route('layanan.index') !!}" class="btn btn-info pull-right"><i class="fa fa-arrow-left"></i> &nbsp; Kembali</a></h4>
                @if (session('message'))
                    <div class="m-t-40 alert alert-{{ session('type') }}">
                    {!! session('message') !!}
                    </div>
                @endif
                <form method="POST" action="{!! route('layanan.update') !!}" enctype="multipart/form-data" style="margin-top: 20px;">
                    {{ csrf_field() }}
                    <div class="form-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group has-danger">
                                    <label class="control-label">Kode Layanan</label>
                                    <input type="text" name="id_layanan" id="id_layanan" class="form-control form-control-danger" value="{{ $layanan->id_layanan }}" readonly required>
                                    <!-- <small class="form-control-feedback"> This field has error. </small>  --></div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group has-danger">
                                    <label class="control-label">Nama Layanan</label>
                                    <input type="text" name="jenis_layanan" id="jenis_layanan" class="form-control form-control-danger" value="{{ $layanan->jenis_layanan }}" required>
                                    <!-- <small class="form-control-feedback"> This field has error. </small>  --></div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label class="control-label">Persyaratan</label>
                                        @foreach($persyaratan as $syarat)
                                        <div class="checkbox checkbox-success">
                                            <input id="checkbox33" type="checkbox" name="persyaratan[]" value="{{ $syarat->id_persyaratan }}"

                                            @if (in_array($syarat->id_persyaratan, $syarat_terpilih))
                                                checked 
                                            @endif
                                            >
                                            <label for="checkbox33"> &nbsp; {{ $syarat->jenis_persyaratan }}</label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group has-danger">
                                    <label class="control-label">Template Surat (DOCX)</label>
                                    <input type="file" name="template" id="template" class="form-control form-control-danger" accept=".docx">
                                    <span class="help-block">
                                        <small>Silakan pilih file kembali jika ingin mengubah template surat.</small>
                                    </span>
                                    <!-- <small class="form-control-feedback"> This field has error. </small>  --></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Simpan</button>
                        <button type="reset" class="btn btn-inverse">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')

@endsection