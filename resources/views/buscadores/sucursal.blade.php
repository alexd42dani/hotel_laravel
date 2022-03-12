@extends ('layout')

@section('title','Buscador sucursal')

@section('link')
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.15.5/dist/bootstrap-table.min.css">
@endsection

@section('script')
<script src="https://unpkg.com/bootstrap-table@1.15.5/dist/bootstrap-table.min.js"></script>
<script src="/js/bootstrap-table-es-MX.js"></script>
@endsection

@section('header', 'Buscador sucursal')

@section('content')

<div class="container" style="margin-top:30px">
    <div class="row">
        <div class="col-md-10 form-group">
            <div>
                <table id="eventsTable" data-toggle="table" data-height="300" data-pagination="true" data-search="true" data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-classes="table table-bordered table-hover table-md" data-toolbar="#toolbar">
                    <thead>
                        <tr>
                            <th data-sortable="true" data-field="id">Id</th>
                            <th>Nombre</th>
                            <th>Numero</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($variables as $vari)
                        <tr>
                            <td>{{$vari->id}}</td>
                            <td>{{$vari->nombre}}</td>
                            <td>{{$vari->numero}}</td>
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
    var a = "nothing";
    var b = "nothing";
    $('#eventsTable').on('click-row.bs.table', function(e, row) {
        console.log(row.id);
        /*var a = document.getElementById("modificar").getAttribute("href");
        var pos = a.lastIndexOf("/");
        var res = a.slice(0, pos + 1);
        var link = res.concat(row.id);
        document.getElementById("modificar").setAttribute("href", link);*/
        a = row.id;
    })

    $('#eventsTable').on('click', 'tbody tr', function(event) {
        $(this).addClass('highlight').siblings().removeClass('highlight');
    });

    function closewin(e) {
        //myWindow.close();
        //sessionStorage.setItem("clientes", a);
        localStorage.setItem("sucursal", a);
        this.close();
        //console.log(e);
    }
</script>

@endsection