@extends ('layout')

@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('link')
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.15.5/dist/bootstrap-table.min.css">
@endsection

@section('title','Tarifa agregar')

@section('script')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.15.5/dist/bootstrap-table.min.js"></script>
<script src="/js/bootstrap-table-es-MX.js"></script>
@endsection

@section('header', 'Tarifa agregar')

@section('content')

<form onsubmit="return validateForm()" method="POST" action="{{route('tarifa.store')}}">
    @csrf

    <div class="container" style="margin-top:30px">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="codigo"> Codigo </label>
                    <input type="text" id="codigo" name="codigo" readonly>
                </div>
                <div class="form-group">
                    <label for="habitacion">Habitacion</label>
                    <input type="text" id="habitacion" name="habitacion" class="readonly" required autocomplete="off" style="caret-color: transparent !important;">
                    <input type="button" value="..." class=" btn-dark" onclick="openWin('habitacion')">
                </div>
                <div class="form-group">
                    <label for="hlimite"> Hora limite </label>
                    <input type="time" id="hlimite" name="hlimite" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="nombre">Nombre Tarifa</label>
                    <select id="nombre" required name="nombre">
                        <option value="">--Selecciona una opción--</option>
                        @foreach($tarifa_nombres as $tari)
                        <option value={{ $tari->id }}>{{ $tari->descripcion }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="canp"> Cantidad personas </label>
                    <input type="number" id="canp" name="canp" value="0" min="0" max="100" required>
                </div>
                <div class="form-group">
                    <label for="precio"> Precio </label>
                    <input type="number" id="precio" name="precio" value="0" min="0" max="10000000000" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="temp">Temporada</label>
                    <select id="temp" name="temp" require>
                        <option value="">--Selecciona una opción--</option>
                        @foreach($temporadas as $temporada)
                        <option value={{ $temporada->id }}>{{ $temporada->descripcion }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="descp"> Descuento personas </label>
                    <input type="number" id="descp" name="descp" value="0" min="0" max="100" required>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="servicio">Servicio</label>
                    <select id="servicio" tabindex="15">
                        <option value="">--Selecciona una opción--</option>
                        <option value="Lavanderia">Lavanderia</option>
                        <option value="Consumicion">Consumicion</option>
                        <option value="Traslado">Traslado</option>
                        <option value="Turismo">Turismo</option>
                        <option value="Spa_sauna">Spa y Sauna</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="porcentaje">Porcentaje</label>
                    <input type="number" id="porcentaje" value="0" min="0" max="100">
                </div>
            </div>
        </div>

        <div class="form-group mb-4 col-md-10">
            <h5>Promocion</h5>
        </div>

        <div class="row">
            <div class="col-md-10 form-group">
                <div class="table-responsive-md table-hover from-group" style="overflow-y:auto; height:200px">
                    <table id="promocion" data-toggle="table" data-classes="table table-bordered table-hover table-md">
                        <thead>
                            <tr>
                                <th data-sortable="true">Servicio</th>
                                <th>Porcentaje</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-2 form-group">
                <div class="col-md-12">
                    <button type="button" class="btn btn-dark form-group" onclick="add('promocion')" tabindex="17">Agregar</button>
                </div>
                <div class="col-md-12">
                    <button type="button" class="btn btn-dark form-group" onclick="remove('promocion')">Quitar</button>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-md-10">
                    <div class="d-flex flex-wrap justify-content-md-around ">
                        <div class="p-2 mx-auto"><button type="submit" class="btn btn-dark" onclick="return mensaje_grabar()" tabindex="18">GRABAR</button></div>
                        <div class="p-2 mx-auto"><button type="reset" class="btn btn-dark" onclick="remove_all()">CANCELAR</button></div>
                        <div class="p-2 mx-auto"><button type="button" onclick="location.href = '{{ route('tarifa.index') }}'" class="btn btn-dark">SALIR</button></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    var id_monto = 0;
    var importe = 0;
    var subtotal = 0;
    var total_iva = 0;
    var total = 0;


    function add(table) {

        if (table === "promocion") {
            //console.log(val);
            var ca = document.getElementById("porcentaje");
            var pr = document.getElementById("servicio");
            var x = [];
            //x[0] = de.value;
            x[0] = ca.value;
            x[1] = pr.value;
            if (x[0] === "" || x[1] === "" ) {
                alert("Cargar campos primero");
                //console.log(x);
                return false;
            }
            if (parseInt(x[0]) < 0 || parseInt(x[0]) > 100) {
                alert("Porcentaje debe estar entre 0 y 100");
                //console.log(x);
                return false;
            }
            // var val = parseInt(document.getElementById('tarifa').value);
            //var path = "empty";
            var path = "empty";
            var table = "#promocion"
            data = null;
            addTable(data, table)
            //console.log(x);
            // addTable(x, table);
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
            if (table === "#promocion") {

                var name_articulo = document.getElementsByName("servicios_detalle[]");
                for (let index = 0; index < name_articulo.length; index++) {
                    const element = name_articulo[index];
                    if (element.value == x[1]) {
                        alert("Servicio ya ingresado");
                        return false;
                    }
                }

                //id_monto += 1;
                
                $(table).bootstrapTable('insertRow', {
                    index: 0,
                    row: [x[1], x[0] +
                        '<input type="hidden" name ="servicios_detalle[]" value=' + x[1] + '>' +
                        '<input type="hidden" name ="porcentaje_detalle[]" value=' + x[0] + '>'
                    ]
                })
                

                document.getElementById("porcentaje").value = "0";
                document.getElementById("servicio").value = "";
            }
        }

    }
    var id_delete = null;
    $('#orden_table, #promocion').on('click-row.bs.table', function(e, row, $element, field) {
        id_delete = row;
        //console.log(id_delete[4]);
        //$("#tarifa_table").bootstrapTable('remove', {field: 0, values: [1]});
        //console.log($element.index());
        //var d = $("#tarifa_table").bootstrapTable('getData');
        // console.log(d);
        // console.log(d[0][0]);
        // console.log(d.length);
    })

    $('#orden_table, #cheque_table, #promocion').on('click', 'tbody tr', function(event) {
        $(this).addClass('highlight').siblings().removeClass('highlight');
    });

    function remove_all() {
        $("#promocion").bootstrapTable('removeAll');

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
        if (table_remove === "orden") {
            var table_rem = "#orden_table";

            var importe = 0;
            importe = parseInt(id_delete[4].replace(".", ""));
            var iva = 0;
            iva = parseInt(id_delete[5].replace(".", ""));

            total_iva -= iva;
            subtotal -= importe;
            total = total_iva + subtotal;

            document.getElementById("total_iva").value = total_iva.format(0, 3, '.', ',');
            document.getElementById("subtotal").value = subtotal.format(0, 3, '.', ',');
            document.getElementById("total").value = total.format(0, 3, '.', ',');
        }
        if (table_remove === "cheque") {
            var table_rem = "#cheque_table";
            mon_cheque = parseInt(id_delete[2].replace(".", ""));
            total_entregado -= mon_cheque;
            document.getElementById("te").value = total_entregado.format(0, 3, '.', ',');
            entregado();
        }
        if (table_remove === "promocion") {
            var table_rem = "#promocion";
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
        if (table_edit === "orden") {
            var table_rem = "#orden_table";

            var importe = 0;
            importe = parseInt(id_delete[4].replace(".", ""));
            var iva = 0;
            iva = parseInt(id_delete[5].replace(".", ""));

            total_iva -= iva;
            subtotal -= importe;
            total = total_iva + subtotal;

            document.getElementById("total_iva").value = total_iva.format(0, 3, '.', ',');
            document.getElementById("subtotal").value = subtotal.format(0, 3, '.', ',');
            document.getElementById("total").value = total.format(0, 3, '.', ',');
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
        if (w == "habitacion") {
            myWindow = window.open("{{ route('searcher.habitacion') }}", "_blank", "width=1000, height=500, menubar=no, top=50, left=250");
        }
        if (w == "proveedor") {
            myWindow = window.open("{{ route('searcher.proveedor') }}", "_blank", "width=1000, height=500, menubar=no, top=50, left=250");
        }
        if (w == "requisicion") {
            myWindow = window.open("{{ route('searcher.requisicion') }}", "_blank", "width=1000, height=500, menubar=no, top=50, left=250");
        }
    }

    window.addEventListener('focus', play);

    function play() {
        // console.log("hola");
        var habitacion = localStorage.getItem("habitacion");
        var proveedor = localStorage.getItem("proveedor");
        var requisicion = localStorage.getItem("requisicion");

        if (habitacion != "nothing" && habitacion != null) {
            document.getElementById("habitacion").value = habitacion;
        }
        if (requisicion != "nothing" && requisicion != null) {
            document.getElementById("requisicion").value = requisicion;
        }
        if (proveedor != "nothing" && proveedor != null) {
            document.getElementById("proveedor").value = proveedor;
        }
        localStorage.removeItem("habitacion");
        localStorage.removeItem("requisicion");
        localStorage.removeItem("proveedor");
    }

    $(".readonly").on('keydown paste', function(e) {
        e.preventDefault();
    });

    function validateForm() {

        //console.log(taf);
        // console.log(taf.length);
        //console.log(x[0]);
        //console.log(x[0].value);
    }

    $('.hidden').hide();
    $('.show').show();

    function mensaje_grabar() {
        return confirm('Desea grabar el registro');
    }

    document.querySelector('#canp').addEventListener("keypress", function(evt) {
        if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57) {
            evt.preventDefault();
        }
    });
    document.querySelector('#descp').addEventListener("keypress", function(evt) {
        if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57) {
            evt.preventDefault();
        }
    });
    document.querySelector('#porc').addEventListener("keypress", function(evt) {
        if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57) {
            evt.preventDefault();
        }
    });
    document.querySelector('#precio').addEventListener("keypress", function(evt) {
        if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57) {
            evt.preventDefault();
        }
    });

    document.querySelector('#porcentaje').addEventListener("keypress", function(evt) {
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