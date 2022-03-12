@extends ('layout')

@section('title','Buscador proveedores')

@section('link')
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.15.5/dist/bootstrap-table.min.css">
@endsection

@section('script')
<script src="https://unpkg.com/bootstrap-table@1.15.5/dist/bootstrap-table.min.js"></script>
<script src="/js/bootstrap-table-es-MX.js"></script>
@endsection

@section('header', 'Buscador proveedores')

@section('content')

<div class="container" style="margin-top:30px">
    <div class="row">
        <div class="col-md-10 form-group">
            <div>
                <table id="eventsTable" data-toggle="table" data-height="300" data-pagination="true" data-search="true" data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-classes="table table-bordered table-hover table-md" data-toolbar="#toolbar">
                    <thead>
                        <tr>
                            <th data-sortable="true" data-field="id">Ruc</th>
                            <th>Nombre</th>
                            <th>Direccion</th>
                            <th>Correo</th>
                            <th>Telefono</th>
                            <th>Pagina</th>
                            <th>Ciudad</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($proveedores as $proveedor)
                        <tr>
                            <td>{{$proveedor->ruc}}</td>
                            <td>{{$proveedor->nombre}}</td>
                            <td>{{$proveedor->direccion}}</td>
                            <td>{{$proveedor->correo}}</td>
                            <td>{{$proveedor->telefono}}</td>
                            <td>{{$proveedor->web_page}}</td>
                            <td>{{$proveedor->ciudad}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-md-2">
            <div class="col-md-12 mt-5 pt-5">
                <button type="button" class="btn btn-dark form-group" onclick="closewin()">Aceptar</button>
            </div>
        </div>
    </div>
</div>

<script>
    var a = "nothing";
    $('#eventsTable').on('click-row.bs.table', function(e, row) {
        a = row.id;
    })

    $('#eventsTable').on('click', 'tbody tr', function(event) {
        $(this).addClass('highlight').siblings().removeClass('highlight');
    });

    function closewin() {
        localStorage.setItem("proveedor", a);
        this.close();
    }
</script>
@endsection