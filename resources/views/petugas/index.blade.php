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
		        <h4 class="card-title">Daftar Petugas <a href="{!! route('petugas.tambah') !!}" class="btn btn-info pull-right"><i class="fa fa-plus"></i> &nbsp; Tambah Petugas</a></h4>
		        @if (session('message'))
		            <div class="m-t-40 alert alert-{{ session('type') }}">
		            {!! session('message') !!}
		            </div>
		        @endif
		        <div class="table-responsive">
		            <table id="myTable" class="table table-bordered table-striped">
		                <thead>
		                    <tr>
		                        <th>ID</th>
		                        <th>Nama</th>
		                        <th>Jabatan</th>
		                        <th>Alamat</th>
		                        <th>Hak Akses</th>
		                        <th>Aksi</th>
		                    </tr>
		                </thead>
		            </table>
		            <form id="delete-form" action="{{ route('petugas.delete') }}" method="POST" style="display: none;">
		                {{ csrf_field() }}
		                <input type="hidden" id="petugasId" name="id" />
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
				ajax: '{!! route('petugas.dtIndex') !!}',
				columns: [
				    {
				    	data: 'id_user', name: 'id_user'
				    },
				    { data: 'nama_petugas', name: 'nama_petugas' },
				    { data: 'jabatan', name: 'jabatan' },
				    { data: 'alamat', name: 'alamat' },
				    { data: 'lvl_user', name: 'lvl_user',
					    render: function(data, type, row) {
					    	if (data == 1) {
					    		return "Administrator";
					    	} else if (data == 2) {
					    		return "Operator";
					    	} else {
					    		return "RW";
					    	}
				    	}
			    	},
				    { data: 'id_user', name: 'id_user', className: 'text-center' ,
				    	render: function ( data, type, row ) {
				            var actions = "<a href='/petugas/"+data+"/edit' class='btn btn-warning btn-sm'><i class='fa fa-edit'></i></a>";
				            if (data != 1) {
				                actions += " <button data-id='"+data+"' class='btn btn-danger btn-sm delete'><i class='fa fa-trash'></i></button>";
				            }
				            return actions;
				        }
				    }
				]
			});

			$('body').on('click', '.delete', function() {
				$('#petugasId').val($(this).attr('data-id'));
				swal({
			        title: "Apakah Anda yakin ingin menghapus petugas tersebut?",
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