@extends ('layout')

@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('link')
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.15.5/dist/bootstrap-table.min.css">
@endsection

@section('title','Cobros agregar')

@section('script')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.15.5/dist/bootstrap-table.min.js"></script>
<script src="/js/bootstrap-table-es-MX.js"></script>
@endsection

@section('header', 'Cobros agregar')

@section('content')

<form onsubmit="return validateForm()" method="POST" action="{{route('cobros.store')}}">
    @csrf

    <div class="container" style="margin-top:30px">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="codigo"> Codigo </label>
                    <input type="text" id="codigo" name="codigo" readonly>
                </div>
                <div class="form-group">
                    <label for="apertura">Apertura</label>
                    <input type="text" id="apertura" name="apertura" class="readonly" required autocomplete="off" style="caret-color: transparent !important;">
                    <input type="button" value="..." class=" btn-dark" onclick="openWin('apertura')">
                </div>
                <div class="form-group">
                    <label for="efec">Efectivo</label>
                    <input type="number" id="efectivo" name="efectivo" value="0" min="0" max="1000000000" onkeyup="efectivo_cal()" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="fecha">Fecha</label>
                    <input type="date" id="fecha" name="fecha" required value="{{\Carbon\Carbon::now(new DateTimeZone('America/Asuncion'))->format('Y-m-d')}}">
                </div>
                <div class="form-group">
                    <label for="monto">Monto a cobrar</label>
                    <input type="text" id="monto" readonly>
                </div>
                <div class="form-group">
                    <label for="te">Total entregado</label>
                    <input type="text" id="te" readonly value=0>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="cuentas">Cuentas a cobrar</label>
                    <input type="text" id="cuenta" name="cuenta" class="readonly" required autocomplete="off" style="caret-color: transparent !important;">
                    <input type="button" value="..." class=" btn-dark" onclick="openWin('cuenta')">
                </div>
                <div class="form-group">
                    <label for="diferencia">Diferencia</label>
                    <input type="text" id="diferencia" readonly>
                </div>
                <div class="form-group">
                    <label for="vuelto">Vuelto</label>
                    <input type="number" id="vuelto" name="vuelto" value="0" min="0" max="1000000000" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="entidad">Entidad</label>
                    <select id="entidad">
                        <option value="">--Selecciona una opción--</option>
                        @foreach($entidades as $entidad)
                        <option value={{ $entidad->id }}>{{ $entidad->Descripcion }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="marcat">Marca tarjeta</label>
                    <select id="marcat">
                        <option value="">--Selecciona una opción--</option>
                        @foreach($marca_tarjetas as $marca_tarjeta)
                        <option value={{ $marca_tarjeta->id }}>{{ $marca_tarjeta->Descripcion }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="monto">Monto</label>
                    <input type="number" id="monto_tarjeta" value="" min="0" max="1000000000">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="tipot">Tipo tarjeta</label>
                    <select id="tipot">
                        <option value="">--Selecciona una opción--</option>
                        @foreach($tipo_tarjetas as $tipo_tarjeta)
                        <option value={{ $tipo_tarjeta->id }}>{{ $tipo_tarjeta->descripcion }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="procesa">Procesadora</label>
                    <select id="procesa">
                        <option value="">--Selecciona una opción--</option>
                        @foreach($procesadoras as $procesadora)
                        <option value={{ $procesadora->id }}>{{ $procesadora->descripcion }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="ticket">Ticket</label>
                    <input type="number" id="ticket" min="0">
                </div>
                <div class="form-group">
                    <label for="serie">Serie</label>
                    <input type="number" id="serie" min="0">
                </div>
            </div>
        </div>

        <div class="form-group my-2 col-md-10">
            <h5>Tarjeta</h5>
        </div>
        <div class="row">
            <div class="col-md-10 form-group">
                <div class="table-responsive-md table-hover from-group" style="overflow-y:auto; height:200px">
                    <table id="tarjeta_table" data-toggle="table" data-classes="table table-bordered table-hover table-md">
                        <thead>
                            <tr>
                                <th data-sortable="true">Id</th>
                                <th>Entidad</th>
                                <th>Tipo tarjeta</th>
                                <th>Marca tarjeta</th>
                                <th>Procesadora</th>
                                <th>Serie</th>
                                <th>Ticket</th>
                                <th>Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-2 form-group">
                <div class="col-md-12">
                    <button type="button" class="btn btn-dark form-group" onclick="add('tarjeta')" tabindex="8">Agregar</button>
                </div>
                <div class="col-md-12">
                    <button type="button" class="btn btn-dark form-group" onclick="remove('tarjeta')">Quitar</button>
                </div>
                <div class="col-md-12">
                    <button type="button" class="btn btn-dark form-group" onclick="edit('tarjeta')">Editar</button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="entidad">Entidad</label>
                    <select id="entidad_cheque">
                        <option value="">--Selecciona una opción--</option>
                        @foreach($entidades as $entidad)
                        <option value={{ $entidad->id }}>{{ $entidad->Descripcion }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="emision">Emision</label>
                    <input type="date" id="emision">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="monto">Monto</label>
                    <input type="number" id="monto_cheque" value="" min="0" max="1000000000">
                </div>
                <div class="form-group">
                    <label for="vencimiento">Vencimiento</label>
                    <input type="date" id="vencimiento">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="ncheque">Numero de cheque</label>
                    <input type="number" id="ncheque" min="0">
                </div>
                <div class="form-group">
                    <label for="titular">Titular</label>
                    <input type="text" id="titular">
                </div>
            </div>
        </div>

        <div class="form-group my-2 col-md-10">
            <h5>Cheque</h5>
        </div>
        <div class="row">
            <div class="col-md-10 form-group">
                <div class="table-responsive-md table-hover from-group" style="overflow-y:auto; height:200px">
                    <table id="cheque_table" data-toggle="table" data-classes="table table-bordered table-hover table-md">
                        <thead>
                            <tr>
                                <th data-sortable="true">Id</th>
                                <th>Entidad</th>
                                <th>Monto</th>
                                <th>Numero de cheque</th>
                                <th>Emision</th>
                                <th>Vencimiento</th>
                                <th>Titular</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-2 form-group">
                <div class="col-md-12">
                    <button type="button" class="btn btn-dark form-group" onclick="add('cheque')" tabindex="8">Agregar</button>
                </div>
                <div class="col-md-12">
                    <button type="button" class="btn btn-dark form-group" onclick="remove('cheque')">Quitar</button>
                </div>
                <div class="col-md-12">
                    <button type="button" class="btn btn-dark form-group" onclick="edit('cheque')">Editar</button>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-md-10">
                    <div class="d-flex flex-wrap justify-content-md-around ">
                        <div class="p-2 mx-auto"><button type="submit" class="btn btn-dark" onclick="return mensaje_grabar()" tabindex="18">GRABAR</button></div>
                        <div class="p-2 mx-auto"><button type="reset" class="btn btn-dark" onclick="remove_all()">CANCELAR</button></div>
                        <div class="p-2 mx-auto"><button type="button" onclick="location.href = '{{ route('cobros.index') }}'" class="btn btn-dark">SALIR</button></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</form>

<script>
    var id_tarjeta = 0;
    var id_cheque = 0;
    var mon_tarjeta = 0;
    var mon_cheque = 0;
    var mon_efectivo = 0;
    var total_entregado = 0;
    var mon_diferencia = 0;

    function efectivo_cal() {
        if (!isNaN(parseInt(document.getElementById("efectivo").value))) {
            total_entregado -= mon_efectivo;
            mon_efectivo = parseInt(document.getElementById("efectivo").value);
            total_entregado += mon_efectivo;
            document.getElementById("te").value = total_entregado.format(0, 3, '.', ',');
            entregado();
        } else {
            document.getElementById("te").value = 0;
            entregado();
        }
    }

    function entregado() {
        if (!isNaN(parseInt(document.getElementById("monto").value))) {
            // total_entregado -= mon_efectivo;
            mon_diferencia = (parseInt((document.getElementById("te").value.replace(".", ""))) - parseInt((document.getElementById("monto").value.replace(".", ""))));
            // total_entregado += mon_efectivo;
            document.getElementById("diferencia").value = mon_diferencia.format(0, 3, '.', ',');
        }
    }

    function add(table) {

        if (table === "tarjeta") {
            //console.log(val);
            var e = document.getElementById("entidad");
            var t = document.getElementById("tipot");
            var p = document.getElementById("procesa");
            var m = document.getElementById("marcat");
            var x = [];
            x[0] = e.options[e.selectedIndex].value;
            x[1] = e.options[e.selectedIndex].text;
            x[2] = t.options[t.selectedIndex].value;
            x[3] = t.options[t.selectedIndex].text;
            x[4] = p.options[p.selectedIndex].value;
            x[5] = p.options[p.selectedIndex].text;
            x[6] = m.options[m.selectedIndex].value;
            x[7] = m.options[m.selectedIndex].text;
            x[8] = document.getElementById("serie").value;
            x[9] = document.getElementById("ticket").value;
            x[10] = document.getElementById("monto_tarjeta").value;
            if (x[0] === "" || x[2] === "" ||
                x[4] === "" || x[6] === "" ||
                x[8] === "" || x[9] === "" || x[10] === "") {
                alert("Cargar campos primero");
                //console.log(x);
                return false;
            }
            // var val = parseInt(document.getElementById('tarifa').value);
            var path = "empty";
            var table = "#tarjeta_table"
            //console.log(x);
            addTable(x, table);
        }

        if (table === "cheque") {
            //console.log(val);
            var e = document.getElementById("entidad_cheque");
            var x = [];
            x[0] = e.options[e.selectedIndex].value;
            x[1] = e.options[e.selectedIndex].text;
            x[2] = document.getElementById("monto_cheque").value;
            x[3] = document.getElementById("ncheque").value;
            x[4] = document.getElementById("emision").value;
            x[5] = document.getElementById("vencimiento").value;
            x[6] = document.getElementById("titular").value;
            if (x[0] === "" || x[2] === "" ||
                x[3] === "" || x[4] === "" ||
                x[5] === "" || x[6] === "") {
                alert("Cargar campos primero");
                //console.log(x);
                return false;
            }
            if (x[4] > x[5]) {
                alert("Fecha emision no puede ser mayor");
                //console.log(x);
                return false;
            }
            // var val = parseInt(document.getElementById('tarifa').value);
            var path = "empty";
            var table = "#cheque_table"
            //console.log(x);
            addTable(x, table);
        }

        if (table === "habitacion") {
            //console.log(val);
            var e = document.getElementById("habi");
            var x = [];
            x[0] = e.options[e.selectedIndex].value;
            x[1] = e.options[e.selectedIndex].text;
            x[2] = document.getElementById("fechae").value;
            x[3] = document.getElementById("fechas").value;
            x[4] = document.getElementById("horae").value;
            x[5] = document.getElementById("horas").value;
            if (e.options[e.selectedIndex].value === "" || document.getElementById("fechae").value === "" ||
                document.getElementById("fechas").value === "" || document.getElementById("horae").value === "" ||
                document.getElementById("horas").value === "") {
                alert("Cargar campos primero");
                //console.log(x);
                return false;
            }
            if (x[2] > x[3]) {
                alert("Fecha entrada no puede ser mayor");
                //console.log(x);
                return false;
            }
            // var val = parseInt(document.getElementById('tarifa').value);
            var path = "empty";
            var table = "#habitacion_table"
            //console.log(x);
            addTable(x, table);
        }

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
                    //console.log(data);
                    //alert(data.tarifas[0].descripcion);
                    //$('#tarifa_table').bootstrapTable('insertRow', {index: 0, row: [1,"hola"]})
                    //console.log([1,"hola"]);
                    addTable(data, table)
                }
            });
        }

        function addTable(data, table) {
            if (table === "#blalba_table") {
                $(table).bootstrapTable('insertRow', {
                    index: 0,
                    row: [data.tarifas[0].id,
                        data.tarifas[0].descripcion +
                        '<input type="hidden" name ="tarifa[]" value=' + data.tarifas[0].id + '>',
                        data.tarifas[0].habitacion_id
                    ]
                })

                document.getElementById("tarifa").value = "";
            }
            if (table === "#cheque_table") {
                id_cheque += 1;
                var mon = 0;
                mon = parseInt(data[2]);
                mon = mon.format(0, 3, '.', ',');
                $(table).bootstrapTable('insertRow', {
                    index: 0,
                    row: [id_cheque, data[1], mon, data[3], showDate(data[4]), showDate(data[5]), data[6] +
                        '<input type="hidden" name ="entidad_cheque[]" value=' + data[0] + '>' +
                        '<input type="hidden" name ="numero_cheque[]" value=' + data[3] + '>' +
                        '<input type="hidden" name ="emision_cheque[]" value=' + data[4] + '>' +
                        '<input type="hidden" name ="vencimiento_cheque[]" value=' + data[5] + '>' +
                        '<input type="hidden" name ="monto[]" value=' + data[2] + '>' +
                        '<input type="hidden" name ="titular[]" value=' + data[6] + '>'
                    ]
                })
                mon_cheque = parseInt(data[2]);
                total_entregado += mon_cheque;
                document.getElementById("te").value = total_entregado.format(0, 3, '.', ',');
                entregado();
                document.getElementById("monto_cheque").value = "";
                document.getElementById("ncheque").value = "";
                document.getElementById("emision").value = "";
                document.getElementById("vencimiento").value = "";
                document.getElementById("titular").value = "";
                document.getElementById("entidad_cheque").value = "";
            }
            if (table === "#tarjeta_table") {
                id_tarjeta += 1;
                var mon = 0;
                mon = parseInt(data[10]);
                mon = mon.format(0, 3, '.', ',');
                $(table).bootstrapTable('insertRow', {
                    index: 0,
                    row: [id_tarjeta, data[1], data[3], data[7], data[5], data[8], data[9], mon +
                        '<input type="hidden" name ="monto_tarjeta[]" value=' + data[10] + '>' +
                        '<input type="hidden" name ="ticket_tarjeta[]" value=' + data[9] + '>' +
                        '<input type="hidden" name ="entidad_tarjeta[]" value=' + data[0] + '>' +
                        '<input type="hidden" name ="tipo_tarjeta[]" value=' + data[2] + '>' +
                        '<input type="hidden" name ="marca_tarjeta[]" value=' + data[6] + '>' +
                        '<input type="hidden" name ="serie_tarjeta[]" value=' + data[8] + '>' +
                        '<input type="hidden" name ="procesadora_tarjeta[]" value=' + data[4] + '>'
                    ]
                })
                mon_tarjeta = parseInt(data[10]);
                total_entregado += mon_tarjeta;
                document.getElementById("te").value = total_entregado.format(0, 3, '.', ',');
                entregado();
                document.getElementById("entidad").value = "";
                document.getElementById("tipot").value = "";
                document.getElementById("procesa").value = "";
                document.getElementById("marcat").value = "";
                document.getElementById("serie").value = "";
                document.getElementById("ticket").value = "";
                document.getElementById("monto_tarjeta").value = "";
            }
        }

    }
    var id_delete = null;
    $('#tarjeta_table, #cheque_table').on('click-row.bs.table', function(e, row, $element, field) {
        id_delete = row;
        //console.log(id_delete[2]);
        //$("#tarifa_table").bootstrapTable('remove', {field: 0, values: [1]});
        //console.log($element.index());
        //var d = $("#tarifa_table").bootstrapTable('getData');
        // console.log(d);
        // console.log(d[0][0]);
        // console.log(d.length);
    })

    $('#tarjeta_table, #cheque_table, #habitacion_huespedes').on('click', 'tbody tr', function(event) {
        $(this).addClass('highlight').siblings().removeClass('highlight');
    });

    function remove_all() {
        $("#tarjeta_table").bootstrapTable('removeAll');
        $("#habitacion_table").bootstrapTable('removeAll');
        $("#habitacion_table").bootstrapTable('removeAll');
    }

    function remove(table_remove) {
        if (id_delete == null) {
            alert("Seleccionar fila primero");
            return false;
        }
        //console.log(id_delete[0]);
        //const combo_habi_edit = document.getElementsByClassName("show");
        //console.log(combo_habi_edit);
        //console.log(combo_habi_edit);
        if (table_remove === "tarjeta") {
            var table_rem = "#tarjeta_table";
            mon_tarjeta = parseInt(id_delete[7].replace(".", ""));
            total_entregado -= mon_tarjeta;
            document.getElementById("te").value = total_entregado.format(0, 3, '.', ',');
            entregado();
        }
        if (table_remove === "cheque") {
            var table_rem = "#cheque_table";
            mon_cheque = parseInt(id_delete[2].replace(".", ""));
            total_entregado -= mon_cheque;
            document.getElementById("te").value = total_entregado.format(0, 3, '.', ',');
            entregado();
        }
        if (table_remove === "habitacion") {
            var table_rem = "#habitacion_table";
        }
        $(table_rem).bootstrapTable('remove', {
            field: 0,
            values: [id_delete[0]]
        });
        //var combo_habi_edit = document.getElementsByClassName("show");
        // console.log(combo_habi_edit);

        id_delete = null
        // $("#tarifa_table").bootstrapTable('remove', {field: 'id', values: [id_delete[0]]});
        //$("#tarifa_table").bootstrapTable('updateRow', {index: 0, row: []});
        //  $("#tarifa_table").bootstrapTable('removeAll');
        // $("#tarifa_table").bootstrapTable('removeByUniqueId', id_delete[0]);
    }

    function hide(v) {
        for (let index = 0; index < v.length; index++) {
            const element = v[index];
            // console.log(element);
            //  console.log(index);
            if (element.value == id_delete[2]) {
                //  console.log(element.value);
                element.setAttribute("class", "hidden");
                $('.hidden').hide();
            }
        }
    }

    function edit(table_edit) {
        if (id_delete == null) {
            alert("Seleccionar fila primero");
            return false;
        }
        //console.log(id_delete[0]);
        if (table_edit === "tarjeta") {
            var table_edt = "#tarjeta_table";
            mon_tarjeta = parseInt(id_delete[7].replace(".", ""));
            total_entregado -= mon_tarjeta;
            document.getElementById("te").value = total_entregado.format(0, 3, '.', ',');
            entregado();
        }
        if (table_edit === "cheque") {
            var table_edt = "#cheque_table";
            mon_cheque = parseInt(id_delete[2].replace(".", ""));
            total_entregado -= mon_cheque;
            document.getElementById("te").value = total_entregado.format(0, 3, '.', ',');
            entregado();
        }
        if (table_edit === "habitacion") {
            var table_edt = "#habitacion_table";
        }
        $(table_edt).bootstrapTable('remove', {
            field: 0,
            values: [id_delete[0]]
        });
        id_delete = null
        add(table_edit);
    }

    function showDate(d) {
        var b = d.split('-')
        return b[2] + '/' + b[1] + '/' + b[0];
    }

    function openWin(w) {
        if (w == "cuenta") {
            myWindow = window.open("{{ route('searcher.cuentas_a_cobrar') }}", "_blank", "width=1000, height=500, menubar=no, top=50, left=250");
        }
        if (w == "apertura") {
            myWindow = window.open("{{ route('searcher.apertura_cierre') }}", "_blank", "width=1000, height=500, menubar=no, top=50, left=250");
        }
    }

    window.addEventListener('focus', play);

    function play() {
        // console.log("hola");
        var cuentas = localStorage.getItem("cuentas_a_cobrar");
        var cuentas_monto = localStorage.getItem("cuentas_monto");
        var apertura = localStorage.getItem("apertura_cierre");

        if (cuentas != "nothing" && cuentas != null) {
            document.getElementById("cuenta").value = cuentas;
        }
        if (apertura != "nothing" && apertura != null) {
            document.getElementById("apertura").value = apertura;
        }
        if (cuentas_monto != "nothing" && cuentas_monto != null) {
            document.getElementById("monto").value = cuentas_monto;
            entregado();
        }
        localStorage.removeItem("cuentas_a_cobrar");
        localStorage.removeItem("apertura_cierre");
        localStorage.removeItem("cuentas_monto");
    }

    $(".readonly").on('keydown paste', function(e) {
        e.preventDefault();
    });

    function validateForm() {

        var a = document.getElementById("diferencia").value;
        if (a < 0) {
            alert("Diferencia no debe ser negativa");
            //console.log(x);
            return false;
        }
    }

    $('.hidden').hide();
    $('.show').show();

    function mensaje_grabar() {
        return confirm('Desea grabar el registro');
    }

    document.querySelector('#efectivo').addEventListener("keypress", function(evt) {
        if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57) {
            evt.preventDefault();
        }
    });
    document.querySelector('#monto_tarjeta').addEventListener("keypress", function(evt) {
        if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57) {
            evt.preventDefault();
        }
    });
    document.querySelector('#vuelto').addEventListener("keypress", function(evt) {
        if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57) {
            evt.preventDefault();
        }
    });
    document.querySelector('#monto_cheque').addEventListener("keypress", function(evt) {
        if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57) {
            evt.preventDefault();
        }
    });
    document.querySelector('#ncheque').addEventListener("keypress", function(evt) {
        if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57) {
            evt.preventDefault();
        }
    });
    document.querySelector('#ticket').addEventListener("keypress", function(evt) {
        if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57) {
            evt.preventDefault();
        }
    });
    document.querySelector('#serie').addEventListener("keypress", function(evt) {
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