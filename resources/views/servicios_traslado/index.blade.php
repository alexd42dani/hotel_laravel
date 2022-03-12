@extends ('layout')

@section('title','Servicios traslado')

@section('link')
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.15.5/dist/bootstrap-table.min.css">
@endsection

@section('script')
<script src="https://unpkg.com/bootstrap-table@1.15.5/dist/bootstrap-table.min.js"></script>
<script src="/js/bootstrap-table-es-MX.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.15.4/extensions/print/bootstrap-table-print.js"></script>
@endsection

@section('header', 'Servicios traslado')

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
                <table id="eventsTable" data-show-print="true" data-toggle="table" data-height="300" data-pagination="true" data-search="true" data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-classes="table table-bordered table-hover table-md" data-toolbar="#toolbar">
                    <thead>
                        <tr>
                            <th data-sortable="true" data-field="id">Id</th>
                            <th>Descripcion</th>
                            <th>Fecha</th>
                            <th>Realizado</th>
                            <th>Estadia id</th>
                            <th>Cliente</th>
                            <th>Ruc</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($servicios as $servicio)
                        <tr>
                            <td>{{$servicio->id}}</td>
                            <td>{{$servicio->descripcion}}</td>
                            <td>{{ \Carbon\Carbon::parse($servicio->fecha)->format('d/m/Y')}}</td>
                            <td>{{$servicio->realizado}}</td>                            
                            <td>{{$servicio->estadia_id}}</td>      
                            <td>{{$servicio->nombre}} {{ $servicio->apellido }}</td>
                            <td>{{$servicio->ruc}}</td>                      
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-md-2 form-group mt-5">
            <div class="col-md-12">
                <a class="btn btn-dark form-group" href="{{ route('servicios_traslado.create') }}">Agregar</a>
            </div>
            <div class="col-md-12">
                <a id="anular" class="btn btn-dark form-group" onclick="return mensaje_anular()" href="{{ route('servicios_traslado.destroy', 'empty') }}">Anular</a>
            </div>
            <div class="col-md-12">
                <a id="realizado" class="btn btn-dark form-group" onclick="return mensaje_realizado()" href="{{ route('traslado.realizado', 'empty') }}">Realizado</a>
            </div>
            <div class="col-md-12">
                <a id="modificar" style="display: none;" class="btn btn-dark form-group" onclick="return mensaje_modificar()" href="{{ route('servicios_consumicion.edit', 'empty') }}">Modificar</a>
            </div>
        </div>
    </div>
</div>

<script>
var id_sel;
    $('#eventsTable').on('click-row.bs.table', function(e, row) {
        id_sel=row;
        // console.log(row.id);
        var a = document.getElementById("modificar").getAttribute("href");
        var f = document.getElementById("anular").getAttribute("href");
        var z = document.getElementById("realizado").getAttribute("href");
        //console.log(f);
        var pos = a.lastIndexOf("/");
        var pos1 = f.lastIndexOf("/");
        var pos2 = z.lastIndexOf("/");
        var res = a.slice(0, pos + 1);
        var res1 = f.slice(0, pos1 + 1);
        var res2 = z.slice(0, pos2 + 1);
        var link = res.concat(row.id);
        var link1 = res1.concat(row.id);
        var link2 = res2.concat(row.id);
        document.getElementById("modificar").setAttribute("href", link);
        document.getElementById("anular").setAttribute("href", link1);
        document.getElementById("realizado").setAttribute("href", link2);
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
    function mensaje_realizado() {
        if (id_sel == null) {
            alert("Seleccionar fila primero");
            return false;
        }
    }
</script>

@endsection