@extends('layouts.app')

@section('title', 'Daftar Pengaduan')

@section('styles')
<link href="css/lib/sweetalert/sweetalert.css" rel="stylesheet">
@endsection

@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="card">
		    <div class="card-body">
		        <h4 class="card-title">Daftar Pengaduan</h4>
		        @if (session('message'))
		            <div class="m-t-40 alert alert-{{ session('type') }}">
		            {!! session('message') !!}
		            </div>
		        @endif
		        <div class="table-responsive">
		            <table id="myTable" class="table table-bordered table-striped">
		                <thead>
		                    <tr>
		                        <th>No. Surat</th>
		                        <th>Nama Warga</th>
		                        <th>Tanggal</th>
		                        <th>Status</th>
		                        <th>Aksi</th>
		                    </tr>
		                </thead>
		            </table>
		            <form id="delete-form" action="{{ route('pengaduan.delete') }}" method="POST" style="display: none;">
		                {{ csrf_field() }}
		                <input type="hidden" id="id_pengaduan" name="id_pengaduan" />
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
			$('#myTable').DataTable({
				// processing: true,
				serverSide: true,
				ajax: '{!! route('pengaduan.dtIndex') !!}',
				order: [[ 2, "desc" ]],
				columns: [
					{
						data: 'no', name: 'no'
					},
				    {
				    	data: 'warga.nama', name: 'warga.nama'
				    },
				    { data: 'created_at', name: 'created_at', 
					    render: function(data, type, row) {
					    	return moment(data).format('DD-MM-YYYY')
					    }
					},
				    { data: 'status', name: 'status',
				    	render: function(data, type, row) {
				    		switch(data) {
				    			case '0': return "Aktif";
				    			case '1': return "Selesai";
				    		}
				    	}
					},
				    { data: 'id_pengaduan', name: 'id_pengaduan', className: 'text-center' ,
				    	render: function ( data, type, row ) {
				            var actions = "<a href='/pengaduan/"+data+"/detail' class='btn btn-info btn-sm'><i class='fa fa-reply'></i> Respon</a>";
				                // actions += " <button data-id='"+data+"' class='btn btn-danger btn-sm delete'><i class='fa fa-trash'></i></button>";
				            return actions;
				        }
				    }
				]
			});

			$('body').on('click', '.delete', function() {
				$('#id_pengaduan').val($(this).attr('data-id'));
				swal({
			        title: "Apakah Anda yakin ingin menghapus pengaduan tersebut?",
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