@extends ('layout')

@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('link')
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.15.5/dist/bootstrap-table.min.css">
@endsection

@section('title','Requisicion agregar')

@section('script')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.15.5/dist/bootstrap-table.min.js"></script>
<script src="/js/bootstrap-table-es-MX.js"></script>
@endsection

@section('header', 'Requisicion agregar')

@section('content')

<form onsubmit="return validateForm()" method="POST" action="{{route('requisicion.store')}}">
    @csrf

    <div class="container" style="margin-top:30px">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="numero"> Numero </label>
                    <input type="text" id="numero" name="numero" required>
                </div>
                <div class="form-group">
                    <label for="solicitante"> Solicitante </label>
                    <input type="text" id="solicitante" name="solicitante" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="fecha">Fecha</label>
                    <input type="date" id="fecha" name="fecha" required value="{{\Carbon\Carbon::now(new DateTimeZone('America/Asuncion'))->format('Y-m-d')}}">
                </div>
                <div class="form-group">
                    <label for="area">Area</label>
                    <select id="area" required name="area">
                        <option value="">--Selecciona una opción--</option>
                        @foreach($areas as $area)
                        <option value={{ $area->id }}>{{ $area->descripcion }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="observaciones">Observaciones</label>
                    <textarea autofocus id="observaciones" cols="30" rows="4" name="observaciones" maxlength="100" required tabindex="1"></textarea>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="cantidad">Cantidad</label>
                    <input type="number" id="cantidad" value="" min="1" max="1000000000">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="articulo">Articulo</label>
                    <input type="text" id="articulo" name="articulo" class="readonly" autocomplete="off" style="caret-color: transparent !important;">
                    <input type="button" value="..." class=" btn-dark" onclick="openWin('articulo')">
                </div>
            </div>
        </div>
        <div class="form-group my-2 col-md-10">
            <h5>Requisicion Detalle</h5>
        </div>
        <div class="row">
            <div class="col-md-10 form-group">
            <div class="table-responsive-md table-hover from-group" style="overflow-y:auto; height:200px">
            <table id="requisicion_table" data-toggle="table" data-classes="table table-bordered table-hover table-md">
                        <thead>
                            <tr>
                                <th data-sortable="true">Id</th>
                                <th>Articulo</th>
                                <th>Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-2 form-group">
            <div class="col-md-12">
                    <button type="button" class="btn btn-dark form-group" onclick="add('requisicion')" tabindex="8">Agregar</button>
                </div>
                <div class="col-md-12">
                    <button type="button" class="btn btn-dark form-group" onclick="remove('requisicion')">Quitar</button>
                </div>
                <div class="col-md-12">
                    <button type="button" class="btn btn-dark form-group" onclick="edit('requisicion')">Editar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-10">
                <div class="d-flex flex-wrap justify-content-md-around ">
                    <div class="p-2 mx-auto"><button type="submit" class="btn btn-dark" onclick="return mensaje_grabar()" tabindex="18">GRABAR</button></div>
                    <div class="p-2 mx-auto"><button type="reset" class="btn btn-dark" onclick="remove_all()">CANCELAR</button></div>
                    <div class="p-2 mx-auto"><button type="button" onclick="location.href = '{{ route('requisicion.index') }}'" class="btn btn-dark">SALIR</button></div>
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

        if (table === "requisicion") {
            //console.log(val);
            var val = parseInt(document.getElementById('articulo').value);
            var ca = document.getElementById("cantidad").value;      
            if (ca == "" || isNaN(val)) {
                alert("Cargar campos primero");
                return false;
            }
            if (ca <= 0 ) {
                alert("Cantidad debe ser mayor a cero");
                return false;
            }
            // var val = parseInt(document.getElementById('tarifa').value);
            var path = "{{route('requisicion.articulo')}}";
            var table = "#requisicion_table"
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
            if (table === "#requisicion_table") {
               /* id_monto += 1;
                var importe = 0;
                importe = (parseInt(data[2]) * parseInt(data[1]));
                var iva = 0;
                iva = (importe/parseInt(data[3]));*/
                $(table).bootstrapTable('insertRow', {
                    index: 0,
                    row: [data.articulos[0].codigo, data.articulos[0].nombre, ca+
                        '<input type="hidden" name ="cantidad_detalle[]" value=' + ca + '>' +
                        '<input type="hidden" name ="articulo_detalle[]" value=' + data.articulos[0].codigo + '>' 
                    ]
                })
               /* total_iva += iva;
                subtotal += importe;
                total = total_iva + subtotal;

                document.getElementById("total_iva").value = total_iva.format(0, 3, '.', ',');
                document.getElementById("subtotal").value = subtotal.format(0, 3, '.', ',');
                document.getElementById("total").value = total.format(0, 3, '.', ',');
                document.getElementById("importe").value = (total.format(0, 3, '.', ',')).replace(".","");

                document.getElementById("cantidad").value = "";
                document.getElementById("descripcion").value = "";
                document.getElementById("precio").value = "";
                document.getElementById("iva").value = "";*/
                document.getElementById("cantidad").value = "";
                document.getElementById("articulo").value = "";
            }
        }

    }
    var id_delete = null;
    $('#requisicion_table, #cheque_table').on('click-row.bs.table', function(e, row, $element, field) {
        id_delete = row;
        //console.log(id_delete[2]);
        //$("#tarifa_table").bootstrapTable('remove', {field: 0, values: [1]});
        //console.log($element.index());
        //var d = $("#tarifa_table").bootstrapTable('getData');
        // console.log(d);
        // console.log(d[0][0]);
        // console.log(d.length);
    })

    $('#requisicion_table, #cheque_table, #habitacion_huespedes').on('click', 'tbody tr', function(event) {
        $(this).addClass('highlight').siblings().removeClass('highlight');
    });

    function remove_all() {
        $("#nota_table").bootstrapTable('removeAll');
        total_iva = 0;
        subtotal = 0;
        total = total_iva + subtotal;
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
        if (table_remove === "requisicion") {
            var table_rem = "#requisicion_table";
            /*mon_tarjeta = parseInt(id_delete[7].replace(".", ""));
            total_entregado -= mon_tarjeta;
            document.getElementById("te").value = total_entregado.format(0, 3, '.', ',');
            entregado();*/
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
        if (table_edit === "requisicion_table") {
            var table_edt = "#tarjeta_table";
           /* mon_tarjeta = parseInt(id_delete[7].replace(".", ""));
            total_entregado -= mon_tarjeta;
            document.getElementById("te").value = total_entregado.format(0, 3, '.', ',');
            entregado();*/
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
        if (w == "articulo") {
            myWindow = window.open("{{ route('searcher.articulo') }}", "_blank", "width=1000, height=500, menubar=no, top=50, left=250");
        }
        if (w == "apertura") {
            myWindow = window.open("{{ route('searcher.apertura_cierre') }}", "_blank", "width=1000, height=500, menubar=no, top=50, left=250");
        }
    }

    window.addEventListener('focus', play);

    function play() {
        // console.log("hola");
        var articulo = localStorage.getItem("articulo");
        var cuentas_monto = localStorage.getItem("cuentas_monto");
        var apertura = localStorage.getItem("apertura_cierre");

        if (articulo!= "nothing" && articulo != null) {
            document.getElementById("articulo").value = articulo;
        }
        if (apertura != "nothing" && apertura != null) {
            document.getElementById("apertura").value = apertura;
        }
        if (cuentas_monto != "nothing" && cuentas_monto != null) {
            document.getElementById("monto").value = cuentas_monto;
            entregado();
        }
        localStorage.removeItem("articulo");
        localStorage.removeItem("apertura_cierre");
        localStorage.removeItem("cuentas_monto");
    }

    $(".readonly").on('keydown paste', function(e) {
        e.preventDefault();
    });

    function validateForm() {
        var taf = document.getElementsByName("articulo_detalle[]");
        if (taf.length == 0) {
            alert("Cargar al menos una requisicion detalle");
            return false;
        }
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

    document.querySelector('#cantidad').addEventListener("keypress", function(evt) {
        if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57) {
            evt.preventDefault();
        }
    });
    document.querySelector('#numero').addEventListener("keypress", function(evt) {
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