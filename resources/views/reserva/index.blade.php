@extends ('layout')

@section('title','Reserva')

@section('link')
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.15.5/dist/bootstrap-table.min.css">
@endsection

@section('script')
<script src="https://unpkg.com/bootstrap-table@1.15.5/dist/bootstrap-table.min.js"></script>
<script src="/js/bootstrap-table-es-MX.js"></script>
@endsection

@section('header', 'Reserva')

@section('content')

<div class="container" style="margin-top:30px">
    <div class="row">
    @if (session('error_')!==null)
        <div class="col-md-12 mb-3">
            <div class="alert alert-danger">
                <ul>
                    <li>{{ session('error_') }}</li>
                </ul>
            </div>
        </div>
        @endif
        <div class="col-md-10 form-group">
            <div>
                <table id="eventsTable" data-toggle="table" data-height="300" data-pagination="true" data-search="true" data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-classes="table table-bordered table-hover table-md" data-toolbar="#toolbar">
                    <thead>
                        <tr>
                            <th data-sortable="true" data-field="id">Id</th>
                            <th>Operador</th>
                            <th>Comentarios</th>
                            <!-- <th>Precio</th> -->
                            <!-- <th>Estado</th> -->
                            <th>Tipo reserva</th>
                            <!-- <th>Tipo estadia</th> -->
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Ruc</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reservas as $reserva)
                        <tr>
                            <td>{{$reserva->id}}</td>
                            <td>{{$reserva->operador}}</td>
                            <td>{{$reserva->comentarios}}</td>
                            <td>{{$reserva->tipo_reserva}}</td>
                            <td>{{ \Carbon\Carbon::parse($reserva->fecha)->format('d/m/Y')}}</td>
                            <td>{{$reserva->nombre}} {{ $reserva->apellido }}</td>
                            <td>{{$reserva->ruc}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-md-2 form-group mt-5">
            <div class="col-md-12">
                <a class="btn btn-dark form-group" href="{{ route('reserva.create') }}">Agregar</a>
            </div>
            <div class="col-md-12">
                <a id="anular" class="btn btn-dark form-group" onclick="return mensaje_anular()" href="{{ route('reserva.destroy', 'empty') }}">Anular</a>
            </div>
            <div class="col-md-12">
                <a id="modificar" style="display: none;" class="btn btn-dark form-group" onclick="return mensaje_modificar()" href="{{ route('reserva.edit', 'empty') }}">Modificar</a>
            </div>
        </div>
    </div>
</div>

<script>
    /*var checkedRows = [];

        $('#eventsTable').on('check.bs.table', function (e, row) {
            checkedRows.push({ id: row.id, name: row.name, forks: row.forks });
            console.log(checkedRows);
        });

        $('#eventsTable').on('uncheck.bs.table', function (e, row) {
            $.each(checkedRows, function (index, value) {
                if (value.id === row.id) {
                    checkedRows.splice(index, 1);
                }
            });
            console.log(checkedRows);
        });

        $("#add_cart").click(function () {
            $("#output").empty();
            $.each(checkedRows, function (index, value) {
                $('#output').append($('<li></li>').text(value.id + " | " + value.name + " | " + value.forks));
            });
        });
*/
var id_sel;
    $('#eventsTable').on('click-row.bs.table', function(e, row) {
        id_sel=row;
        // console.log(row.id);
        var a = document.getElementById("modificar").getAttribute("href");
        var f = document.getElementById("anular").getAttribute("href");
        //console.log(f);
        var pos = a.lastIndexOf("/");
        var pos1 = f.lastIndexOf("/");
        var res = a.slice(0, pos + 1);
        var res1 = f.slice(0, pos1 + 1);
        var link = res.concat(row.id);
        var link1 = res1.concat(row.id);
        document.getElementById("modificar").setAttribute("href", link);
        document.getElementById("anular").setAttribute("href", link1);
    })

    $('#eventsTable').on('click', 'tbody tr', function(event) {
        $(this).addClass('highlight').siblings().removeClass('highlight');
    });

    function mensaje() {
        return confirm('Desea eliminar el registro');
    }

    function mensaje_anular() {
        if (id_sel == null) {
            alert("Seleccionar fila primero");
            return false;
        }
    }
    function mensaje_modificar() {
        if (id_sel == null) {
            alert("Seleccionar fila primero");
            return false;
        }
    }
</script>

@endsection