@extends('layouts.vistageneral')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
    @section('contenidoPrincipal')
        <div class="" style="margin-left: 5rem; margin-right: 5rem">
        <br />
        <h2 align="center">Administrar registros UF</h2>
        <br />
        <div align="right">
            <button type="button" name="create_record" id="create_record" class="btn btn-success"> <i class="bi bi-plus-square"></i> Agregar</button>
        </div>
        <br />
            <table class="table table-striped table-bordered indicador_datatable" id="indicador_datatable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Código</th>
                        <th>Medida</th>
                        <th>Valor</th>
                        <th>Fecha</th>
                        <th width="180px">Opciones</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

    <div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
        <form method="post" id="sample_form" class="form-horizontal">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">Agregar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <span id="form_result"></span>
                <div class="form-group">
                    <label>Nombre : </label>
                    <input type="text" name="nombre" id="nombre" value="UNIDAD DE FOMENTO (UF)" class="form-control" required />
                </div>
                <div class="form-group">
                    <label>Código : </label>
                    <input type="text" name="codigo" id="codigo" value="UF" class="form-control" required  />
                </div>

                <div class="form-group">
                    <label>Unidad de Medida : </label>
                    <input type="text" name="unidad" id="unidad" value="Pesos" class="form-control" required  />
                </div>
                <div class="form-group">
                    <label>Valor : </label>
                    <input type="number" name="valor" min="0" max="99999.99" step=".01" id="valor"  placeholder="99999.99" class="form-control" required />
                </div>
                <div class="form-group">
                    <label>Fecha : </label>

                    <input type="date" name="fecha" id="fecha" class="form-control" required />
                </div>
                <input type="hidden" name="action" id="action" value="Agregar" />
                <input type="hidden" name="hidden_id" id="hidden_id" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <input type="submit" name="action_button" id="action_button" value="Agregar" class="btn btn-info" />
            </div>
        </form>
        </div>
    </div>
</div>
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
        <form method="post" id="sample_form" class="form-horizontal">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">Confirmar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h4 align="center" style="margin:0;">Estás seguro de eliminar el registro?</h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" name="ok_button" id="ok_button" class="btn btn-danger">Aceptar</button>
            </div>
        </form>
        </div>
        </div>
    </div>

@endsection
@section('scripts')
<script type="text/javascript">

$(document).ready(function() {


    var table = $('.indicador_datatable').DataTable({

        processing: true,
        serverSide: true,
        ajax: "{{ route('indicadors.index') }}",
        columns: [
            {data: 'id', name: 'id'},
            {data: 'nombreIndicador', name: 'nombre'},
            {data: 'codigoIndicador', name: 'codigo'},
            {data: 'unidadMedidaIndicador', name: 'medida'},
            {data: 'valorIndicador', name: 'valor'},
            {data: 'fechaIndicador', name: 'fecha'},
            {data: 'action', name: 'action', orderable: true},
        ]
    });

    $('#create_record').click(function(){
        $('.modal-title').text('Indicador');
        $('#action_button').val('Registrar');
        $('#action').val('Add');
        $('#form_result').html('');
        $('#formModal').modal('show');

    });

    $('#sample_form').on('submit', function(event){
        event.preventDefault();
        var action_url = '';

        if($('#action').val() == 'Add')
        {
            action_url = "{{ route('indicadors.store') }}";
        }

        if($('#action').val() == 'Edit')
        {
            action_url = "{{ route('indicadors.update') }}";
        }

        $.ajax({
            type: 'post',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: action_url,
            data:$(this).serialize(),
            dataType: 'json',
            success: function(data) {
                console.log('success: '+data);
                var html = '';
                if(data.errors)
                {
                    html = '<div class="alert alert-danger">';
                    for(var count = 0; count < data.errors.length; count++)
                    {
                        html += '<p>' + data.errors[count] + '</p>';
                    }
                    html += '</div>';
                }
                if(data.success)
                {
                    html = '<div class="alert alert-success">' + data.success + '</div>';
                    $('#sample_form')[0].reset();
                    setTimeout(function(){
                $('#formModal').modal('hide');
                $('#indicador_datatable').DataTable().ajax.reload();
                }, 2000);
                }
                $('#form_result').html(html);
            },
            error: function(data) {
                var errors = data.responseJSON;
                console.log(errors);
            }
        });
    });

    $(document).on('click', '.edit', function(event){
        event.preventDefault();
        var id = $(this).attr('id');
        $('#form_result').html('');



        $.ajax({
            url :"/indicadors/edit/"+id+"/",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            dataType:"json",
            data:{
        'id': id,
        '_token': '{{ csrf_token() }}',
    },
            success:function(data)
            {
                console.log('success: '+data);
                $('#nombre').val(data.result.nombreIndicador);
                $('#codigo').val(data.result.codigoIndicador);
                $('#unidad').val(data.result.unidadMedidaIndicador);
                $('#valor').val(data.result.valorIndicador);
                $('#fecha').val(data.result.fechaIndicador);
                $('#hidden_id').val(id);
                $('.modal-title').text('Editar Registro');
                $('#action_button').val('Actualizar');
                $('#action').val('Edit');
                $('#formModal').modal('show');
                if(data.success)
                {
                $('#sample_form')[0].reset();
                setTimeout(function(){
                $('#confirmModal').modal('hide');
                $('#indicador_datatable').DataTable().ajax.reload();
                }, 2000);
            }
            },
            error: function(data) {
                var errors = data.responseJSON;
                console.log(errors);
            }
        })
    });

    var indicador_id;

    $(document).on('click', '.delete', function(){
        indicador_id = $(this).attr('id');
        $('#confirmModal').modal('show');
    });

    $('#ok_button').click(function(){

        $.ajax({
            type: "post",
            url:'/destroy/'+indicador_id,
            data:{
        'id': indicador_id,
        '_token': '{{ csrf_token() }}',
    },
            beforeSend:function(){
                $('#ok_button').text('Eliminando...');
            },
            success:function(data)
            {

                setTimeout(function(){
                $('#confirmModal').modal('hide');
                $('#indicador_datatable').DataTable().ajax.reload();
                alert('Data Deleted');
                }, 2000);

            }
        })
    });

});
</script>
@endsection
