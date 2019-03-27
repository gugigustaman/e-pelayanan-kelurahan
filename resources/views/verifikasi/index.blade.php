@extends('layouts.app')

@section('title', 'Daftar Petugas')

@section('styles')
	<link href="css/lib/sweetalert/sweetalert.css" rel="stylesheet">
@endsection

@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="card">
		    <div class="card-body">
		        <h4 class="card-title">Daftar Surat Pengantar </h4>
		        @if (session('message'))
		            <div class="m-t-40 alert alert-{{ session('type') }}">
		            {!! session('message') !!}
		            </div>
		        @endif
		        <div class="table-responsive">
		            <table id="myTable" class="table table-bordered table-striped">
		                <thead>
		                    <tr>
		                        <th>Nama</th>
		                        <th>Jenis Permohonan</th>
		                        <th>Tanggal</th>
		                        <th>Aksi</th>
		                    </tr>
		                </thead>
		            </table>
		            <form id="verify-form" action="{{ route('verifikasi.verify') }}" method="POST" style="display: none;">
		                {{ csrf_field() }}
		                <input type="hidden" id="verify" name="verify" />
		                <input type="hidden" id="uploadId" name="id" />
		            </form>
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
	<script src="js/lib/sweetalert/sweetalert.min.js"></script>

	<script type="text/javascript">
		$(function() {
			$.ajaxSetup({
			    headers: {
			        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			    }
			});

			$('#myTable').DataTable({
				// processing: true,
				serverSide: true,
				ajax: '{!! route('verifikasi.dtIndex') !!}',
				columns: [
				    { data: 'permohonan.warga.nama', name: 'permohonan.warga.nama' },
				    { data: 'permohonan.layanan.jenis_layanan', name: 'permohonan.layanan.jenis_layanan' },
				    { data: 'created_at', name: 'created_at' },
				    { data: 'id_upload', name: 'id_upload', className: 'text-center' ,
				    	render: function ( data, type, row ) {
				            var actions = "<a href='/storage/"+row.path+"' target='_blank' class='btn btn-info btn-sm'><i class='fa fa-search'></i></a>";
			                actions += " <button data-id='"+data+"' class='btn btn-success btn-sm accept'><i class='fa fa-check'></i></button>";
			                actions += " <button data-id='"+data+"' class='btn btn-danger btn-sm refuse'><i class='fa fa-times'></i></button>";
				            return actions;
				        }
				    }
				]
			});

			$('body').on('click', '.accept', function() {
				$('#uploadId').val($(this).attr('data-id'));
				$('#verify').val('1');
				swal({
			        title: "Apakah dokumen tersebut BENAR berasal dari RW Anda?",
			        text: "Tindakan ini tidak dapat dikembalikan seperti sebelumnya.",
			        type: "warning",
			        showCancelButton: true,
			        confirmButtonColor: "#DD6B55",
			        confirmButtonText: "YA",
			        closeOnConfirm: false
			    },
			    function(){
			        $('#verify-form').submit();
			    });
			});

			$('body').on('click', '.refuse', function() {
				$('#uploadId').val($(this).attr('data-id'));
				$('#verify').val('2');
				swal({
			        title: "Apakah dokumen tersebut TIDAK BENAR berasal dari RW Anda?",
			        text: "Tindakan ini tidak dapat dikembalikan seperti sebelumnya.",
			        type: "warning",
			        showCancelButton: true,
			        confirmButtonColor: "#DD6B55",
			        confirmButtonText: "YA",
			        closeOnConfirm: false
			    },
			    function(){
			        $('#verify-form').submit();
			    });
			});
		});
	</script>
@endsection