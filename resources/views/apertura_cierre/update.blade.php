@extends ('layout')

@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('link')
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.15.5/dist/bootstrap-table.min.css">
@endsection

@section('title','Apertura y cierre editar')

@section('script')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.15.5/dist/bootstrap-table.min.js"></script>
<script src="/js/bootstrap-table-es-MX.js"></script>
@endsection

@section('header', 'Apertura y cierre editar')

@section('content')

<form onsubmit="return validateForm()" method="POST" action="{{route('apertura.update',$aperturas[0]->id)}}">
    @csrf
    @method('PUT')
    <div class="container" style="margin-top:30px">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="codigo"> Codigo </label>
                    <input type="text" id="codigo" name="codigo" readonly value="{{$aperturas[0]->id}}">
                </div>
                <div class="form-group">
                    <label for="fechaa">Fecha apertura</label>
                    <input type="date" id="fechaa" name="fechaa" required value="{{$aperturas[0]->fecha_apertura}}">
                </div>
                <div class="form-group">
                    <label for="fechac">Fecha cierre</label>
                    <input type="date" id="fechac" name="fechac" required>
                </div>
                <div class="form-group">
                    <label for="m_cheque">Monto cheque</label>
                    <input type="text" id="m_cheque" readonly>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="caja"> Caja id </label>
                    <input type="text" id="caja" name="caja" readonly value="{{$cajas[0]->id}}">
                </div>
                <div class="form-group">
                    <label for="horaa">Hora apertura</label>
                    <input type="time" id="horaa" name="horaa" required value="{{$aperturas[0]->hora_apertura}}">
                </div>
                <div class="form-group">
                    <label for="horae">Hora cierre</label>
                    <input type="time" id="horae" name="horac" required>
                </div>
                <div class="form-group">
                    <label for="m_efectivo">Monto efectivo</label>
                    <input type="text" id="m_efectivo" readonly>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="caja1"> Caja </label>
                    <input type="text" id="caja1" readonly value="{{$cajas[0]->descripcion}}">
                </div>
                <div class="form-group">
                    <label for="si"> Saldo inicial </label>
                    <input type="number" id="salini" name="salini" min="0" max="100000000" required value="{{$aperturas[0]->saldo_inicial}}">
                </div>
                <div class="form-group">
                    <label for="sf"> Saldo final </label>
                    <input type="text" id="salfin" name="salfin" readonly>
                </div>
                <div class="form-group">
                    <label for="m_tarjeta">Monto tarjeta</label>
                    <input type="text" id="m_tarjeta" readonly>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-md-10">
                    <div class="d-flex flex-wrap justify-content-md-around ">
                        <div class="p-2 mx-auto"><button type="submit" class="btn btn-dark" onclick="return mensaje_grabar()" tabindex="18">GRABAR</button></div>
                        <div class="p-2 mx-auto"><button type="reset" class="btn btn-dark" onclick="remove_all()">CANCELAR</button></div>
                        <div class="p-2 mx-auto"><button type="button" onclick="location.href = '{{ route('estadia.index') }}'" class="btn btn-dark">SALIR</button></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</form>

<script>
    add();

    function add() {

        var val = parseInt(document.getElementById('codigo').value);
        var path = "{{route('apertura.estimado')}}";
        var table = "#nothing"

        if (path !== "empty") {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: path,
                data: {
                    id: val
                },
                success: function(data) {
                    //alert(data.id);
                    //console.log(data.cobros.length);
                    //alert(data.tarifas[0].descripcion);
                    //$('#tarifa_table').bootstrapTable('insertRow', {index: 0, row: [1,"hola"]})
                    //console.log([1,"hola"]);
                    addTable(data, table)
                }
            });
        }

        function addTable(data, table) {
            /*  $(table).bootstrapTable('insertRow', {
                  index: 0,
                  row: [data.tarifas[0].id,
                      data.tarifas[0].descripcion +
                      '<input type="hidden" name ="tarifa[]" value=' + data.tarifas[0].id + '>',
                      data.tarifas[0].habitacion_id
                  ]
              })*/
            if (data.cobroe.length !== 0) {
                // document.getElementById("tarifa").value = "";
                //if (typeof data.cobros[0].monto_efectivo !== 'undefined') {
                if (data.cobroe[0].saldo !== null) {
                    //if (data.cobros[0].hasOwnProperty('monto_efectivo')) {
                    est_efectivo = parseInt(data.cobroe[0].saldo);
                    document.getElementById("m_efectivo").value = est_efectivo.format(0, 3, '.', ',');
                } else {
                    document.getElementById("m_efectivo").value = 0;
                }
                // if (typeof data.cobros[0].monto_cheque !== 'undefined') {
                if (data.cobroc[0].cheque !== null) {
                    // if (data.cobros[0].hasOwnProperty('monto_cheque')) {
                    est_cheque = parseInt(data.cobroc[0].cheque);
                    document.getElementById("m_cheque").value = est_cheque.format(0, 3, '.', ',');
                } else {
                    document.getElementById("m_cheque").value = 0;
                }

                if (data.cobrot[0].tarjeta !== null) {
                    // if (data.cobros[0].hasOwnProperty('monto_cheque')) {
                    est_tarjeta = parseInt(data.cobrot[0].tarjeta);
                    document.getElementById("m_tarjeta").value = est_tarjeta.format(0, 3, '.', ',');
                } else {
                    document.getElementById("m_tarjeta").value = 0;
                }
            } else {
                document.getElementById("m_efectivo").value = 0;
                document.getElementById("m_cheque").value = 0;
                document.getElementById("m_tarjeta").value = 0;
                est_efectivo = 0;
                est_cheque = 0;
                est_tarjeta = 0;
            }
            document.getElementById("salfin").value = (est_cheque + est_tarjeta + est_efectivo).format(0, 3, '.', ',');
        }

    }


    function showDate(d) {
        var b = d.split('-')
        return b[2] + '/' + b[1] + '/' + b[0];
    }



    function validateForm() {
        var a = document.getElementById("fechaa").value;
        var c = document.getElementById("fechac").value;
        if (a > c) {
            alert("Fecha apertura no puede ser mayor");
            //console.log(x);
            return false;
        }
    }

    Number.prototype.format = function(n, x, s, c) {
        var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
            num = this.toFixed(Math.max(0, ~~n));

        return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
    };

    function mensaje_grabar() {
        return confirm('Desea grabar el registro');
    }
</script>

@endsection