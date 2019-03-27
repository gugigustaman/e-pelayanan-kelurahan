@extends('layouts.app')

@section('title', 'Ubah Petugas')

@section('styles')

@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card card-outline-primary">
            <div class="card-body">
            	<h4 class="card-title">Ubah Petugas <a href="{!! route('petugas.index') !!}" class="btn btn-info pull-right"><i class="fa fa-arrow-left"></i> &nbsp; Kembali</a></h4>
                <form method="POST" action="{!! route('petugas.update') !!}" style="margin-top: 20px;">
                    {{ csrf_field() }}
                    <input type="hidden" name="id_user" value="{{ $user->id_user }}" />
                    <div class="form-body">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group has-danger">
                                    <label class="control-label">Nama Petugas</label>
                                    <input type="text" name="nama_petugas" id="nama_petugas" class="form-control form-control-danger" value="{{ $user->nama_petugas }}" required>
                                    <!-- <small class="form-control-feedback"> This field has error. </small>  --></div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label">Nama User</label>
                                    <input type="text" id="nama_user" name="nama_user" class="form-control" readonly value="{{ $user->nama_user }}" required >
                                    <small class="form-control-feedback"> Digunakan untuk login </small> </div>
                            </div>
                            <!--/span-->
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label">Password</label>
                                    <input type="password" id="password" name="password" class="form-control" placeholder="Ketik disini untuk mengubah password" >
                                    <small class="form-control-feedback"> Digunakan untuk login </small> </div>
                            </div>
                            <!--/span-->
                        </div>
                        <!--/row-->
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group has-danger">
                                    <label class="control-label">Jabatan</label>
                                    <input type="text" id="jabatan" name="jabatan" class="form-control form-control-danger" value="{{ $user->jabatan }}" required>
                                    <!-- <small class="form-control-feedback"> This field has error. </small>  --></div>
                            </div>
                            <!--/span-->

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Hak Akses</label>
                                    <select class="form-control custom-select" name="lvl_user" required>
                                        <option value="1">Administrator</option>
                                        <option value="2" selected>Operator</option>
                                    </select>
                                    <!-- <small class="form-control-feedback"> Select your gender </small>  --></div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Alamat</label>
                                    <textarea name="alamat" rows="3" class="form-control" required style="height: auto !important">{{ $user->alamat }}</textarea>
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