@extends ('layout')

@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('link')
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.15.5/dist/bootstrap-table.min.css">
@endsection

@section('title','Arqueo agregar')

@section('script')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.15.5/dist/bootstrap-table.min.js"></script>
<script src="/js/bootstrap-table-es-MX.js"></script>
@endsection

@section('header', 'Arqueo agregar')

@section('content')

<form onsubmit="return validateForm()" method="POST" action="{{route('arqueo.store')}}">
    @csrf

    <div class="container" style="margin-top:30px">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="codigo"> Codigo </label>
                    <input type="text" id="codigo" name="codigo" readonly>
                </div>
                <div class="form-group">
                    <label for="me"> Estimado efectivo </label>
                    <input type="text" id="estimado_efectivo" readonly>
                </div>

                <div class="form-group">
                    <label for="mc"> Monto cheque </label>
                    <input type="number" id="mc" name="monto_cheque" value="0" min="0" max="1000000000" onkeyup="efectivo_cal()" onclick="efectivo_cal()" required>
                </div>
                <div class="form-group">
                    <label for="20mil"> Cant. 20 mil </label>
                    <input type="number" id="20mil" name="veintemil" value="0" min="0" max="1000000" onkeyup="efectivo_cal()" onclick="efectivo_cal()" required>
                </div>
                <div class="form-group">
                    <label for="2mil"> Cant. 2 mil </label>
                    <input type="number" id="2mil" name="dosmil" value="0" min="0" max="1000000" onkeyup="efectivo_cal()" onclick="efectivo_cal()" required>
                </div>
                <div class="form-group">
                    <label for="100"> Cant. 100 </label>
                    <input type="number" id="100" name="cien" value="0" min="0" max="1000000" onkeyup="efectivo_cal()" onclick="efectivo_cal()" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="fechac">Fecha</label>
                    <input type="date" id="fecha" name="fecha" required value="{{\Carbon\Carbon::now(new DateTimeZone('America/Asuncion'))->format('Y-m-d')}}">
                </div>
                <div class="form-group">
                    <label for="mc"> Estimado cheque </label>
                    <input type="text" id="estimado_cheque" readonly>
                </div>
                <div class="form-group">
                    <label for="100mil"> Cant. 100 mil </label>
                    <input type="number" id="100mil" name="cienmill" value="0" min="0" max="1000000" onkeyup="efectivo_cal()" onclick="efectivo_cal()" required>
                </div>
                <div class="form-group">
                    <label for="10mil"> Cant. 10 mil </label>
                    <input type="number" id="10mil" name="diezmil" value="0" min="0" max="1000000" onkeyup="efectivo_cal()" onclick="efectivo_cal()" required>
                </div>
                <div class="form-group">
                    <label for="1mil"> Cant. 1 mil </label>
                    <input type="number" id="1mil" name="mil" value="0" min="0" max="1000000" onkeyup="efectivo_cal()" onclick="efectivo_cal()" required>
                </div>
                <div class="form-group">
                    <label for="50"> Cant. 50 </label>
                    <input type="number" id="50" name="cincuenta" value="0" min="0" max="1000000" onkeyup="efectivo_cal()" onclick="efectivo_cal()" required>
                </div>
            </div>
            <div class="col-md-4">

                <div class="form-group">
                    <label for="apertura">Apertura</label>
                    <input type="text" id="apertura" name="apertura" value="{{isset($apertura[0]->id)?$apertura[0]->id:''}}" class="readonly" required autocomplete="off" style="caret-color: transparent !important;">
                </div>
                <div class="form-group">
                    <label for="mc"> Diferencia </label>
                    <input type="text" id="diferencia" readonly>
                </div>
                <div class="form-group">
                    <label for="50mil"> Cant. 50 mil </label>
                    <input type="number" id="50mil" name="cincuentamil" value="0" min="0" max="1000000" onkeyup="efectivo_cal()" onclick="efectivo_cal()" required>
                </div>
                <div class="form-group">
                    <label for="5mil"> Cant. 5 mil </label>
                    <input type="number" id="5mil" name="cincomil" value="0" min="0" max="1000000" onkeyup="efectivo_cal()" onclick="efectivo_cal()" required>
                </div>
                <div class="form-group">
                    <label for="500"> Cant. 500 </label>
                    <input type="number" id="500" name="quinientos" value="0" min="0" max="1000000" onkeyup="efectivo_cal()" onclick="efectivo_cal()" required>
                </div>
                <div class="form-group">
                    <label for="m_efectivo"> Total efectivo </label>
                    <input type="text" id="m_efectivo" name="m_efectivo" value="0" readonly required>
                </div>
            </div>
        </div>


        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="d-flex flex-wrap justify-content-md-around ">
                        <div class="p-2 mx-auto"><button type="submit" class="btn btn-dark" onclick="return mensaje_grabar()" tabindex="18">GRABAR</button></div>
                        <div class="p-2 mx-auto"><button type="reset" class="btn btn-dark">CANCELAR</button></div>
                        <div class="p-2 mx-auto"><button type="button" onclick="location.href = '{{ route('arqueo.index') }}'" class="btn btn-dark">SALIR</button></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</form>

<script>
    var mon_efectivo = 0;
    var mon_cheque = 0;
    //var total_entregado = 0;
    var mon_diferencia = 0;
    var est_efectivo = 0;
    var est_cheque = 0;

    function efectivo_cal() {
        if (!isNaN(parseInt(document.getElementById("estimado_efectivo").value)) && !isNaN(parseInt(document.getElementById("estimado_cheque").value))) {
            //total_entregado -= mon_efectivo;
            //  mon_efectivo = parseInt(document.getElementById("me").value);
            //  mon_cheque = parseInt(document.getElementById("mc").value);
            entregado();
        } else {
            document.getElementById("diferencia").value = 0;
            document.getElementById("m_efectivo").value = 0;
            entregado();
        }
    }

    function entregado() {
        if (!isNaN(parseInt(document.getElementById("100mil").value)) && !isNaN(parseInt(document.getElementById("mc").value))) {
            // total_entregado -= mon_efectivo;
            var monto_efe = (parseInt(document.getElementById("100mil").value) * 100000) +
                (parseInt(document.getElementById("50mil").value) * 50000) + (parseInt(document.getElementById("20mil").value) * 20000) +
                (parseInt(document.getElementById("10mil").value) * 10000) + (parseInt(document.getElementById("5mil").value) * 5000) +
                (parseInt(document.getElementById("2mil").value) * 2000) + (parseInt(document.getElementById("1mil").value) * 1000) +
                (parseInt(document.getElementById("500").value) * 500) + (parseInt(document.getElementById("100").value) * 100) +
                (parseInt(document.getElementById("50").value) * 50);
            var monto_sum= monto_efe + parseInt(document.getElementById("mc").value);
            var monto_est = est_efectivo + est_cheque;
            mon_diferencia = monto_sum - monto_est;
            // total_entregado += mon_efectivo;
            document.getElementById("diferencia").value = mon_diferencia.format(0, 3, '.', ',');
            document.getElementById("m_efectivo").value = monto_efe.format(0, 3, '.', ',');
        } else {
            document.getElementById("diferencia").value = 0;
            document.getElementById("m_efectivo").value = 0;
        }
    }

    function add() {

        var val = parseInt(document.getElementById('apertura').value);
        var path = "{{route('arqueo.estimado')}}";
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
                    document.getElementById("estimado_efectivo").value = est_efectivo.format(0, 3, '.', ',');
                } else {
                    document.getElementById("estimado_efectivo").value = 0;
                }
                // if (typeof data.cobros[0].monto_cheque !== 'undefined') {
                if (data.cobroc[0].cheque !== null) {
                    // if (data.cobros[0].hasOwnProperty('monto_cheque')) {
                    est_cheque = parseInt(data.cobroc[0].cheque);
                    document.getElementById("estimado_cheque").value = est_cheque.format(0, 3, '.', ',');
                } else {
                    document.getElementById("estimado_cheque").value = 0;
                }
            } else {
                document.getElementById("estimado_efectivo").value = 0;
                document.getElementById("estimado_cheque").value = 0;
                document.getElementById("diferencia").value = 0;
                document.getElementById("m_efectivo").value = 0;
                est_efectivo = 0;
                est_cheque = 0;
            }
            entregado();
        }

    }







    function showDate(d) {
        var b = d.split('-')
        return b[2] + '/' + b[1] + '/' + b[0];
    }

    function openWin(w) {

        if (w == "apertura") {
            myWindow = window.open("{{ route('searcher.apertura_cierre') }}", "_blank", "width=1000, height=500, menubar=no, top=50, left=250");
        }
    }

    window.addEventListener('focus', play);

    function play() {
        // console.log("hola");
        var apertura = localStorage.getItem("apertura_cierre");


        if (apertura != "nothing" && apertura != null) {
            document.getElementById("apertura").value = apertura;
            add();
            entregado();
        }

        localStorage.removeItem("apertura_cierre");
    }

    $(document).ready(function() {
        add();
    });


    $(".readonly").on('keydown paste', function(e) {
        e.preventDefault();
    });

    function validateForm() {


    }

    function mensaje_grabar() {
        return confirm('Desea grabar el registro');
    }

    document.querySelector('#mc').addEventListener("keypress", function(evt) {
        if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57) {
            evt.preventDefault();
        }
    });


    Number.prototype.format = function(n, x, s, c) {
        var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
            num = this.toFixed(Math.max(0, ~~n));

        return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
    };
</script>

@endsection