@extends('layouts.app')

@section('title', 'Ubah Permohonan')

@section('styles')
<link href="/css/lib/sweetalert/sweetalert.css" rel="stylesheet">
<style type="text/css">
    #pemohon .b-r {
        margin: 10px auto;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }
    textarea.form-control {
        height: 100px !important;
    }
    #tolakPermohonan {
        display: none;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card card-outline-primary">
            <div class="card-body">
            	<h4 class="card-title">Ubah Permohonan <a href="{!! route('permohonan.index') !!}" class="btn btn-info pull-right"><i class="fa fa-arrow-left"></i> &nbsp; Kembali</a></h4>
                @if (session('message'))
                    <div class="m-t-40 alert alert-{{ session('type') }}">
                    {!! session('message') !!}
                    </div>
                @endif
                <form method="POST" action="{!! route('permohonan.update') !!}" style="margin-top: 20px;">
                    {{ csrf_field() }}
                    <input type="hidden" name="id_permohonan" value="{{ $permohonan->id_permohonan }}" />
                    <div class="form-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Layanan</label>
                                    <select id="id_layanan" name="id_layanan" class="form-control" disabled>
                                        @foreach ($layanan as $l)
                                        <option value="{{ $l->id_layanan }}"

                                            @if ($permohonan->id_layanan == $l->id_layanan)
                                                selected 
                                            @endif

                                            >{{ $l->jenis_layanan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs profile-tab" role="tablist">
                                    <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#persyaratan" role="tab">Persyaratan</a> </li>
                                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#pemohon" role="tab">Pemohon</a> </li>
                                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#settings" role="tab">Data Surat</a> </li>
                                </ul>
                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <div class="tab-pane active" id="persyaratan" role="tabpanel">
                                        <div class="card-body" style="padding-top: 20px">
                                            <div class="row">
                                                @foreach ($permohonan->persyaratan as $syarat)
                                                <div class="col-md-4 col-xs-12">
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <label class="col-md-12">{{ $syarat->jenis_persyaratan }}<br />
                                                                @if ($syarat->pivot->status == '0')
                                                                (Belum diverifikasi)
                                                                @elseif ($syarat->pivot->status == '1')
                                                                <span class="text-success">(Terverifikasi BENAR oleh RW terkait)</span>
                                                                @elseif ($syarat->pivot->status == '2')
                                                                <span class="text-danger">(Terverifikasi TIDAK BENAR oleh RW terkait)</span>
                                                                @endif
                                                            </label>
                                                            <div class="col-md-12">
                                                                <a href="{{ Storage::url('public/'.$syarat->pivot->path) }}" target="_blank"><img src="{{ Storage::url('public/'.$syarat->pivot->path) }}" height="200" class="img-responsive" /></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <!--second tab-->
                                    <div class="tab-pane" id="pemohon" role="tabpanel">
                                        <div class="card-body" style="padding-top: 20px">
                                            <div class="row">
                                                <div class="col-md-4 col-xs-6 b-r"> <strong>NIK</strong>
                                                    <br>
                                                    <p class="text-muted">{{ $permohonan->warga->nik }}</p>
                                                </div>
                                                <div class="col-md-4 col-xs-6 b-r"> <strong>Nama</strong>
                                                    <br>
                                                    <p class="text-muted">{{ $permohonan->warga->nama }}</p>
                                                </div>
                                                <div class="col-md-4 col-xs-6 b-r"> <strong>No. KK</strong>
                                                    <br>
                                                    <p class="text-muted">{{ $permohonan->warga->no_kk }}</p>
                                                </div>
                                                <div class="col-md-4 col-xs-6 b-r"> <strong>Jenis Kelamin</strong>
                                                    <br>
                                                    <p class="text-muted">{{ $permohonan->warga->jk == "L" ? "Laki-laki" : "Perempuan" }}</p>
                                                </div>
                                                <div class="col-md-4 col-xs-6 b-r"> <strong>Tempat Lahir</strong>
                                                    <br>
                                                    <p class="text-muted">{{ $permohonan->warga->tmpt_lahir }}</p>
                                                </div>
                                                <div class="col-md-4 col-xs-6 b-r"> <strong>Tanggal Lahir</strong>
                                                    <br>
                                                    <p class="text-muted">{{ Carbon\Carbon::parse($permohonan->warga->tgl_lahir)->format('d-m-Y') }}</p>
                                                </div>
                                                <div class="col-md-4 col-xs-6 b-r"> <strong>Alamat</strong>
                                                    <br>
                                                    <p class="text-muted">{{ $permohonan->warga->alamat }}</p>
                                                </div>
                                                <div class="col-md-4 col-xs-6 b-r"> <strong>RT/RW</strong>
                                                    <br>
                                                    <p class="text-muted">{{ str_pad($permohonan->warga->rt, 3, "0", STR_PAD_LEFT) }}/{{ str_pad($permohonan->warga->rw, 3, "0", STR_PAD_LEFT) }}</p>
                                                </div>
                                                <div class="col-md-4 col-xs-6 b-r"> <strong>Agama</strong>
                                                    <br>
                                                    <p class="text-muted">{{ $permohonan->warga->agama }}</p>
                                                </div>
                                                <div class="col-md-4 col-xs-6 b-r"> <strong>Status</strong>
                                                    <br>
                                                    <p class="text-muted">{{ $permohonan->warga->status }}</p>
                                                </div>
                                                <div class="col-md-4 col-xs-6 b-r"> <strong>Pekerjaan</strong>
                                                    <br>
                                                    <p class="text-muted">{{ $permohonan->warga->pekerjaan }}</p>
                                                </div>
                                                <div class="col-md-4 col-xs-6 b-r"> <strong>Email</strong>
                                                    <br>
                                                    <p class="text-muted">{{ $permohonan->warga->email }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="settings" role="tabpanel">
                                        <div class="card-body" style="padding-top: 20px">
                                            <div class="row">
                                                <div class="col-md-6 col-xs-12">
                                                    <div class="form-group">
                                                        <label class="col-md-12">No. Surat</label>
                                                        <div class="col-md-12">
                                                            <input type="text" class="form-control form-control-line" value="{{ $permohonan->no }}" disabled="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-xs-12">
                                                    <div class="form-group">
                                                        <label>Status</label>
                                                        <select id="status" name="status" class="form-control">
                                                            <option value="0"
                                                                @if ($permohonan->status == "0")
                                                                    selected 
                                                                @endif
                                                                >Menunggu</option>
                                                            <option value="1"
                                                                @if ($permohonan->status == "1")
                                                                    selected 
                                                                @endif
                                                                >Proses</option>
                                                            <option value="2"
                                                                @if ($permohonan->status == "2")
                                                                    selected 
                                                                @endif
                                                                >Selesai</option>
                                                            <option value="3"
                                                                @if ($permohonan->status == "3")
                                                                    selected 
                                                                @endif
                                                                >Ditolak</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-xs-12">
                                                    <div class="form-group">
                                                        <label class="col-md-12">Keterangan</label>
                                                        <div class="col-md-12">
                                                            <textarea rows="3" class="form-control form-control-line" disabled="">{{ $permohonan->keterangan}}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-xs-12" id="tolakPermohonan">
                                                    <div class="form-group">
                                                        <label class="col-md-12">Alasan Tolak Permohonan</label>
                                                        <div class="col-md-12">
                                                            <textarea rows="3" name="alasan_tolak" class="form-control form-control-line"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Simpan</button>
                        <button id="preview" data-id="{{ $permohonan->id_permohonan }}" class="btn btn-info"> <i class="fa fa-search"></i> Preview</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
	<script src="/js/lib/sweetalert/sweetalert.min.js"></script>

	<script type="text/javascript">
		$(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#status').change(function() {
                if ($(this).val() == "3") {
                    $('#tolakPermohonan').show();
                } else {
                    $('#tolakPermohonan').hide();
                }
            });

            $('#preview').click(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '{!! route('permohonan.preview') !!}',
                    data: JSON.stringify({
                        id_permohonan: {{ $permohonan->id_permohonan }}
                    }),
                    contentType: 'application/json',
                    dataType: 'json',
                    success: function(data) {
                        if (data.url) {
                            window.location.href=data.url;
                        } else {
                            swal({
                                title: "Gagal",
                                text: data.message,
                                html: true
                            });
                        }
                    }
                });
            });
		});
	</script>
@endsection