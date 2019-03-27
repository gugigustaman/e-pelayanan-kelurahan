@extends('layouts.app')

@section('title', 'Daftar Layanan')

@section('styles')
<link href="css/lib/sweetalert/sweetalert.css" rel="stylesheet">
@endsection

@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="card">
		    <div class="card-body">
		        <h4 class="card-title">Daftar Layanan <a href="{!! route('layanan.tambah') !!}" class="btn btn-info pull-right"><i class="fa fa-plus"></i> &nbsp; Tambah Layanan</a></h4>
		        @if (session('message'))
		            <div class="m-t-40 alert alert-{{ session('type') }}">
		            {!! session('message') !!}
		            </div>
		        @endif
		        <div class="table-responsive">
		            <table id="myTable" class="table table-bordered table-striped">
		                <thead>
		                    <tr>
		                        <th>ID Layanan</th>
		                        <th>Layanan</th>
		                        <th>Persyaratan</th>
		                        <th>Aksi</th>
		                    </tr>
		                </thead>
		            </table>
		            <form id="delete-form" action="{{ route('layanan.delete') }}" method="POST" style="display: none;">
		                {{ csrf_field() }}
		                <input type="hidden" id="id_layanan" name="id_layanan" />
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
				ajax: '{!! route('layanan.dtIndex') !!}',
				columns: [
					{
						data: 'id_layanan', name: 'id_layanan'
					},
				    {
				    	data: 'jenis_layanan', name: 'jenis_layanan'
				    },
				    { data: 'persyaratan', name: 'persyaratan',
				    	render: function(data, type, row) {
				    		var syarat = [];
				    		for (var i = 0; i < data.length; i++) {
				    			syarat[i] = data[i].jenis_persyaratan;
				    		}
				    		console.log(data);
				    		return syarat.join(', ');
				    	}
				    },
				    { data: 'id_layanan', name: 'id_layanan', className: 'text-center' ,
				    	render: function ( data, type, row ) {
				    		var actions = "";
				    		if (row.template_path !== "") {
				    			actions += "<a href='"+row.template_path+"' class='btn btn-info btn-sm'><i class='fa fa-download'></i></a>";
				    		}
			                actions += " <a href='/layanan/"+data+"/edit' class='btn btn-warning btn-sm'><i class='fa fa-edit'></i></a>";
			                actions += " <button data-id='"+data+"' class='btn btn-danger btn-sm delete'><i class='fa fa-trash'></i></button>";
				            return actions;
				        }
				    }
				]
			});

			$('body').on('click', '.delete', function() {
				$('#id_layanan').val($(this).attr('data-id'));
				swal({
			        title: "Apakah Anda yakin ingin menghapus layanan tersebut?",
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