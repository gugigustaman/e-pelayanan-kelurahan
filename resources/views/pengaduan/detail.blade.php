@extends('layouts.app')

@section('title', 'Respon Pengaduan')

@section('styles')
<style type="text/css">
    textarea.form-control {
        height: 100px !important;
    }
    .card-box h5 {
        margin-bottom: 0px !important;
    }
    .card-box h6 {
        font-size: 10px !important;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card card-outline-primary">
            <div class="card-body">
            	<h4 class="card-title">Respon Pengaduan
                    <a href="{!! route('pengaduan.close') !!}" onclick="event.preventDefault(); document.getElementById('check-form').submit();" class="btn btn-success pull-right" style="margin-left: 10px"><i class="fa fa-check"></i> &nbsp; Tandai Selesai</a>
                    <form id="check-form" action="{{ route('pengaduan.close') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                        <input type="hidden" name="id_pengaduan" value="{{ $pengaduan->id_pengaduan }}" />
                    </form>
                    <a href="{!! route('pengaduan.index') !!}" class="btn btn-info pull-right"><i class="fa fa-arrow-left"></i> &nbsp; Kembali</a>
                </h4>
                @if (session('message'))
                    <div class="m-t-40 alert alert-{{ session('type') }}">
                    {!! session('message') !!}
                    </div>
                @endif
                <div class="mt-4">
                    <h5>
                        {{ $pengaduan->no }} &nbsp; 
                        {!! $pengaduan->status == '0' ? '<span class="label label-rouded label-warning">AKTIF</span>' : '<span class="label label-rouded label-success">SELESAI</span>' !!}
                    </h5>
                    <hr/>

                    <div class="media mb-4 mt-1">
                        <!-- <img class="d-flex mr-3 rounded-circle thumb-sm" src="images/users/avatar-2.jpg" alt="Generic placeholder image"> -->
                        <div class="media-body">
                            <span class="pull-right">{{ Carbon\Carbon::parse($pengaduan->created_at)->format('d-m-Y H:i') }}</span>
                            <h6 class="m-0">{{ $pengaduan->warga->nama }}</h6>
                            <small class="text-muted">Jenis: {{ $pengaduan->jenis_pengaduan }}</small>
                        </div>
                    </div>
                    {{ $pengaduan->isi_pengaduan }}
                    <hr />
                    <h6><i class="fa fa-paperclip"></i> Lampiran ({{ $pengaduan->upload_count }})</h6>
                    @foreach ($pengaduan->upload as $upload)
                    <a href="{{ Storage::url('public/'.$upload->path) }}" target="_blank">
                    @if (in_array(pathinfo(Storage::url('public/'.$upload->path))['extension'], ['jpg', 'jpeg', 'gif', 'png']))
                        <img src="{{ Storage::url('public/'.$upload->path) }}" height="200" />
                    @else
                        Klik untuk melihat lampiran
                    @endif
                    </a>
                    @endforeach
                    <hr/>
                </div>
                <!-- card-box -->

                <h6> <i class="fa fa-comments-o mb-2"></i> Balasan <span>({{ $pengaduan->komentar_count }})</span> </h6>

                @foreach($pengaduan->komentar as $komentar)
                <div class="media mb-0 mt-3">
                    <div class="media-body">
                        <div class="card-box">
                            <h5>{{ $komentar->id_user == null ? $komentar->warga->nama : $komentar->petugas->nama_petugas }}</h5>
                            <h6>{{ $komentar->created_at }}</h6>
                            <p>{{ $komentar->isi_komentar }}</p>
                        </div>
                    </div>
                </div>
                <hr />
                @endforeach

                <div class="media mb-0 mt-3">
                    <div class="media-body">
                        <div class="card-box">
                            <h5>{{ Auth::getUser()->nama_petugas }}</h5>
                            <form method="POST" action="{{ route('pengaduan.komentar') }}">
                                {{ csrf_field() }}
                                <input type="hidden" name="id_pengaduan" value="{{ $pengaduan->id_pengaduan }}" />
                                <div class="form-body">
                                    <div class="form-group">
                                        <label class="control-label">Isi Komentar</label>
                                        <textarea name="isi_komentar" rows="5" class="form-control" required style="height: auto !important"></textarea>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-info"> <i class="fa fa-send"></i> &nbsp; Kirim</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
	<script src="/js/lib/datatables/datatables.min.js"></script>
	<script src="/js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
	<script src="/js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
	<script src="/js/lib/datatables/cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
	<script src="/js/lib/datatables/cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
	<script src="/js/lib/datatables/cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
	<script src="/js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
	<script src="/js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>

	<script type="text/javascript">
		$(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
		});
	</script>
@endsection