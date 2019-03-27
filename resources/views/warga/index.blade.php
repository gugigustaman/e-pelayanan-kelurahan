@extends('layouts.app')

@section('title', 'Daftar Warga')

@section('styles')
<link href="css/lib/sweetalert/sweetalert.css" rel="stylesheet">
<style type="text/css">
	.detail_warga p {
		margin-bottom: 10px;
	}
</style>
@endsection

@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="card">
		    <div class="card-body">
		        <h4 class="card-title">Daftar Warga <!-- <a href="{!! route('warga.tambah') !!}" class="btn btn-info pull-right"><i class="fa fa-plus"></i> &nbsp; Tambah Warga</a> --></h4>
		        @if (session('message'))
		            <div class="m-t-40 alert alert-{{ session('type') }}">
		            {!! session('message') !!}
		            </div>
		        @endif
		        <div class="table-responsive">
		            <table id="myTable" class="table table-bordered table-striped">
		                <thead>
		                    <tr>
		                        <th>NIK</th>
		                        <th>Nama Lengkap</th>
		                        <th>Tempat, Tanggal Lahir</th>
		                        <th>Jenis Kelamin</th>
		                        <th>Aksi</th>
		                    </tr>
		                </thead>
		            </table>
		            <form id="delete-form" action="{{ route('warga.delete') }}" method="POST" style="display: none;">
		                {{ csrf_field() }}
		                <input type="hidden" id="nik" name="nik" />
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
				serverSide: false,
				ajax: '{!! route('warga.dtIndex') !!}',
				columns: [
					{
						data: 'nik', name: 'nik'
					},
				    { data: 'nama', name: 'nama' },
				    { data: 'tmpt_lahir', name: 'tmpt_lahir',
				    	render: function (data, type, row) {
				    		return data + ', ' + moment(row.tgl_lahir).format('D MMMM YYYY');
				    	}
				    },
				    { data: 'jk', name: 'jk',
				    	render: function (data, type, row) {
				    		if (data == "L") {
				    			return "Laki-laki";
				    		} else {
				    			return "Perempuan";
				    		}
				    	}
				    },
				    { data: 'nik', name: 'nik', className: 'text-center' ,
				    	render: function ( data, type, row ) {
				            var actions = "<button class='btn btn-info btn-sm view' data-id='"+data+"' ><i class='fa fa-user'></i></button>";
				            // actions = "<a href='/warga/"+data+"/edit' class='btn btn-warning btn-sm'><i class='fa fa-edit'></i></a>";
				                actions += " <button data-id='"+data+"' class='btn btn-danger btn-sm delete'><i class='fa fa-trash'></i></button>";
				            return actions;
				        }
				    }
				]
			});

			$('body').on('click', '.view', function() {
				var nik = $(this).attr('data-id');
				$.ajax({
					url: '/warga/detail',
					type: 'POST',
					data: JSON.stringify({
						nik: nik
					}),
					dataType: 'json',
					contentType: 'application/json'
				}).done(function( data, textStatus, jqXHR ) {
					var warga = data.warga;
					console.log(warga);
					if (textStatus == 'success') {
						swal({
						    title: "Detail Warga",
						    text: '<div class="row detail_warga">'+
						    		'<div class="col-md-6 col-xs-12 text-left">'+
						    			'<strong>NIK</strong>'+
						    			'<p>'+warga.nik+'</p>'+
						    			'<strong>Nama Lengkap</strong>'+
						    			'<p>'+warga.nama+'</p>'+
						    			'<strong>Tempat, Tanggal Lahir</strong>'+
						    			'<p>'+warga.tmpt_lahir+', '+moment(warga.tgl_lahir).format('D MMMM YYYY')+'</p>'+
						    			'<strong>Jenis Kelamin</strong>'+
						    			'<p>'+(warga.jk == 'L' ? 'Laki-laki' : 'Perempuan')+'</p>'+
						    			'<strong>Alamat</strong>'+
						    			'<p>'+warga.alamat+', RT. '+ warga.rt + ' / RW. ' + warga.rw + '</p>'+
						    		'</div>'+
						    		'<div class="col-md-6 col-xs-12 text-left">'+
						    			'<strong>Agama</strong>'+
						    			'<p>'+warga.agama+'</p>'+
						    			'<strong>Status Perkawinan</strong>'+
						    			'<p>'+warga.status+'</p>'+
						    			'<strong>Pekerjaan</strong>'+
						    			'<p>'+warga.pekerjaan+'</p>'+
						    			'<strong>No. KK</strong>'+
						    			'<p>'+warga.no_kk+'</p>'+
						    			'<strong>E-mail</strong>'+
						    			'<p>'+warga.email+'</p>'+
						    		'</div>'+
						    	'</div>',

						    html: true
						});
					} else {
						swal({
						    title: "Detail Warga",
						    text: data.message,
						    html: true
						});
					}
				});
			});

			$('body').on('click', '.delete', function() {
				$('#nik').val($(this).attr('data-id'));
				swal({
			        title: "Apakah Anda yakin ingin menghapus warga tersebut?",
			        text: "Tindakan ini tidak dapat dikembalikan seperti sebelumnya.",
			        type: "warning",
			        showCancelButton: true,
			        confirmButtonColor: "#DD6B55",
			        confirmButtonText: "Ya, hapus!!",
			        closeOnConfirm: false
			    },
			    function(){
			        $('#delete-form').submit();
			    });
			});
		});
	</script>
@endsection