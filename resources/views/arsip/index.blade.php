@extends('layouts.app')

@section('title', 'Daftar Arsip')

@section('styles')
<link href="css/lib/sweetalert/sweetalert.css" rel="stylesheet">
<style type="text/css">
	.detail_arsip p {
		margin-bottom: 10px;
	}
</style>
@endsection

@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="card">
		    <div class="card-body">
		        <h4 class="card-title">Daftar Arsip <!-- <a href="{!! route('arsip.tambah') !!}" class="btn btn-info pull-right"><i class="fa fa-plus"></i> &nbsp; Tambah Arsip</a> --></h4>
		        @if (session('message'))
		            <div class="m-t-40 alert alert-{{ session('type') }}">
		            {!! session('message') !!}
		            </div>
		        @endif
		        <div class="table-responsive">
		            <table id="myTable" class="table table-bordered table-striped">
		                <thead>
		                    <tr>
		                        <th>No.</th>
		                        <th>No. Surat</th>
		                        <th>Pemohon</th>
		                        <th>Jenis Surat</th>
		                        <th>Tanggal</th>
		                        <th>Status</th>
		                        <th>Aksi</th>
		                    </tr>
		                </thead>
		            </table>
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
				ajax: '{!! route('arsip.dtIndex') !!}',
				order: [[ 0, "desc" ]],
				columns: [
					{
						data: 'id', name: 'id'
					},
					{
						data: 'permohonan.no', name: 'permohonan.no'
					},
				    { 
				    	data: 'permohonan.warga.nama', 
				    	name: 'permohonan.warga.nama' 
				    },
				    { 
				    	data: 'permohonan.layanan.jenis_layanan',
				    	name: 'permohonan.layanan.jenis_layanan'
				    },
				    { 
				    	data: 'created_at', name: 'created_at',
				    	render: function(data, type, row) {
				    		return moment(data).format("DD-MM-YYYY");
				    	}
				    },
				    { 
				    	data: 'id', name: 'id', 
				    	render: function(data, type, row) {
				    		return "Selesai";
				    	}
				    },
				    { data: 'id', name: 'id', className: 'text-center' ,
				    	render: function ( data, type, row ) {
				            var actions = "<button class='btn btn-info btn-sm download' data-id='"+data+"' ><i class='fa fa-download'></i></button>";
				            return actions;
				        }
				    }
				]
			});

			$('body').on('click', '.download', function(e) {
				var idarsip = $(this).attr('data-id');
				e.preventDefault();
				$.ajax({
				    type: 'POST',
				    url: '{!! route('arsip.download') !!}',
				    data: JSON.stringify({
				        id_arsip: idarsip
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