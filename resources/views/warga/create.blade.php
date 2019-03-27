@extends('layouts.app')

@section('title', 'Tambah Layanan')

@section('styles')

@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card card-outline-primary">
            <div class="card-body">
            	<h4 class="card-title">Tambah Layanan <a href="{!! route('petugas.index') !!}" class="btn btn-info pull-right"><i class="fa fa-arrow-left"></i> &nbsp; Kembali</a></h4>
                @if (session('message'))
                    <div class="m-t-40 alert alert-{{ session('type') }}">
                    {!! session('message') !!}
                    </div>
                @endif
                <form method="POST" action="{!! route('layanan.save') !!}" style="margin-top: 20px;">
                    {{ csrf_field() }}
                    <div class="form-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group has-danger">
                                    <label class="control-label">Kode Layanan</label>
                                    <input type="text" name="id_layanan" id="id_layanan" class="form-control form-control-danger" required>
                                    <!-- <small class="form-control-feedback"> This field has error. </small>  --></div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group has-danger">
                                    <label class="control-label">Nama Layanan</label>
                                    <input type="text" name="jenis_layanan" id="jenis_layanan" class="form-control form-control-danger" required>
                                    <!-- <small class="form-control-feedback"> This field has error. </small>  --></div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label class="control-label">Persyaratan</label>
                                        @foreach($persyaratan as $syarat)
                                        <div class="checkbox checkbox-success">
                                            <input id="checkbox33" type="checkbox" name="persyaratan[]" value="{{ $syarat->id_persyaratan }}">
                                            <label for="checkbox33"> &nbsp; {{ $syarat->jenis_persyaratan }}</label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
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
					    	} else {
					    		return "Operator";
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
		});
	</script>
@endsection