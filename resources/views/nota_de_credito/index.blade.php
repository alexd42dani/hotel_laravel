@extends ('layout')

@section('title','Nota de credito')

@section('link')
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.15.5/dist/bootstrap-table.min.css">
@endsection

@section('script')
<script src="https://unpkg.com/bootstrap-table@1.15.5/dist/bootstrap-table.min.js"></script>
<script src="/js/bootstrap-table-es-MX.js"></script>
@endsection

@section('header', 'Nota de credito')

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
                            <th data-sortable="true" data-field="id">Numero</th>
                            <th>Timbrado</th>
                            <th>Fecha</th>
                            <th>Nro factura</th>
                            <th>Concepto</th>
                            <th>Importe</th>
                            <th>Cliente</th>
                            <th>Ruc</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($notas_creditos as $nota)
                        <tr>
                            <td>{{$nota->numero}}</td>
                            <td>{{$nota->timbrado}}</td>
                            <td>{{ \Carbon\Carbon::parse($nota->fecha)->format('d/m/Y')}}</td>
                            <td>{{$nota->factura_numero}}</td>
                            <td>{{$nota->concepto}}</td>
                            <td>{{number_format($nota->importe,0,'','.')}}</td>
                            <td>{{$nota->nombre}} {{ $nota->apellido }}</td>
                            <td>{{$nota->ruc}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-md-2 form-group mt-5">
            <div class="col-md-12">
                <a class="btn btn-dark form-group" href="{{ route('nota_de_credito.create') }}">Agregar</a>
            </div>
            <div class="col-md-12">
                <a id="anular" class="btn btn-dark form-group" onclick="return mensaje_anular()" href="{{ route('nota_de_credito.destroy', ['empty', 'empty']) }}">Anular</a>

            </div>
            <div class="col-md-12">
                <a id="modificar" style="display: none;" class="btn btn-dark form-group" onclick="return mensaje_modificar()" href="{{ route('nota_de_credito.edit', 'empty') }}">Modificar</a>
            </div>
        </div>
    </div>
</div>

<script>
 var id_sel;
    var url = "{{ route('nota_de_credito.edit', ['empty', 'empty']) }}";
    var url1 = "{{ route('nota_de_credito.destroy', ['empty', 'empty']) }}";
    $('#eventsTable').on('click-row.bs.table', function(e, row) {
        document.getElementById("modificar").setAttribute("href", url);
        document.getElementById("anular").setAttribute("href", url1);
        id_sel = row;
        // console.log(row.id);
        var a = document.getElementById("modificar").getAttribute("href");
        var f = document.getElementById("anular").getAttribute("href");
        //console.log(f);
        //var pos = a.lastIndexOf("/");
        //var pos1 = f.lastIndexOf("/");
        var pos = a.indexOf("empty");
        var pos1 = f.indexOf("empty");
        //var res = a.slice(0, pos + 1);
        //var res1 = f.slice(0, pos1 + 1);
        var res = a.slice(0, pos);
        var res1 = f.slice(0, pos1);
        //var link = res.concat(row[2]);
        //var link1 = res1.concat(row[2]);
        var link = res.concat(row.id, "/" + row[1]);
        var link1 = res1.concat(row.id, "/" + row[1]);
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