@extends ('layout')

@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('link')
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.15.5/dist/bootstrap-table.min.css">
@endsection

@section('title','Servicios lavanderia agregar')

@section('script')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.15.5/dist/bootstrap-table.min.js"></script>
<script src="/js/bootstrap-table-es-MX.js"></script>
@endsection

@section('header', 'Servicios lavanderia agregar')

@section('content')

<form onsubmit="return validateForm()" method="POST" action="{{route('servicios_lavanderia.store')}}">
    @csrf

    <div class="container" style="margin-top:30px">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="id"> Id </label>
                    <input type="text" id="id" name="id">
                </div>
                <div class="form-group">
                    <label for="estadia">Estadia</label>
                    <input type="text" id="estadia" name="estadia" class="readonly" autocomplete="off" style="caret-color: transparent !important;">
                    <input type="button" value="..." class=" btn-dark" onclick="openWin('estadias')" tabindex="5">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="fecha">Fecha</label>
                    <input type="date" id="fecha" name="fecha" required value="{{\Carbon\Carbon::now(new DateTimeZone('America/Asuncion'))->format('Y-m-d')}}">
                </div>
                <div class="form-group">
                    <label for="descripcion"> Descripcion </label>
                    <input type="text" id="descripcion" name="descripcion" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="realizado">Realizado</label>
                    <select id="realizado" required name="realizado">
                        <option value="">--Selecciona una opción--</option>
                        <option value="Si">Si</option>
                        <option selected value="No">No</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="cantidad">Cantidad</label>
                    <input type="number" id="cantidad" value="" min="1" max="1000000000">
                </div>
                <div class="form-group">
                    <label for="habitacion">Habitacion</label>
                    <input type="text" id="habitacion" class="readonly" autocomplete="off" style="caret-color: transparent !important;">
                    <input type="button" value="..." class=" btn-dark" onclick="openWin1('habitacion')">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="lavanderia">Lavanderia</label>
                    <input type="text" id="lavanderia" class="readonly" autocomplete="off" style="caret-color: transparent !important;">
                    <input type="button" value="..." class=" btn-dark" onclick="openWin('lavanderia')">
                </div>
                <div class="form-group">
                    <label for="huesped">Huesped</label>
                    <input type="text" id="huesped" class="readonly" autocomplete="off" style="caret-color: transparent !important;">
                    <input type="button" value="..." class=" btn-dark" onclick="openWin1('huesped')">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="prenda">Prenda</label>
                    <input type="text" id="prenda">
                </div>
            </div>
        </div>
        <div class="form-group my-2 col-md-10">
            <h5>Lavanderia</h5>
        </div>
        <div class="row">
            <div class="col-md-10 form-group">
                <div class="table-responsive-md table-hover from-group" style="overflow-y:auto; height:200px">
                    <table id="consumicion_table" data-toggle="table" data-classes="table table-bordered table-hover table-md">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Lavanderia</th>
                                <th>Cantidad</th>
                                <th>Precio</th>
                                <th>Habitacion</th>
                                <th>Huesped</th>
                                <th>Promocion</th>
                                <th>Prenda</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-2 form-group">
                <div class="col-md-12">
                    <button type="button" class="btn btn-dark form-group" onclick="add('consumicion')" tabindex="8">Agregar</button>
                </div>
                <div class="col-md-12">
                    <button type="button" class="btn btn-dark form-group" onclick="remove('consumicion')">Quitar</button>
                </div>
                <div class="col-md-12">
                    <button type="button" class="btn btn-dark form-group" style="display: consumicion;" onclick="edit('spa')">Editar</button>
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
                    <div class="p-2 mx-auto"><button type="button" onclick="location.href = '{{ route('servicios_lavanderia.index') }}'" class="btn btn-dark">SALIR</button></div>
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

        if (table === "huesped") {
            //console.log(val);
            var val = document.getElementById("huesped").value;

            if (val === "") {
                alert("Cargar campos primero");
                //console.log(x);
                return false;
            }
            // var val = parseInt(document.getElementById('tarifa').value);
            //var path = "empty";
            var path = "{{route('servicios_spa.huesped')}}";
            var table = "#huesped_table"
            //console.log(x);
            // addTable(x, table);
        }

        if (table === "consumicion") {
            //console.log(val);
            var can = document.getElementById("cantidad").value;
            var prenda = document.getElementById("prenda").value;
            //var val = parseInt(document.getElementById("producto").value);
            //var habi = parseInt(document.getElementById("habitacion").value);
            var val = [];
            val[0] = parseInt(document.getElementById("lavanderia").value);
            val[1] = parseInt(document.getElementById("habitacion").value);
            val[2] = parseInt(document.getElementById("huesped").value);
            //console.log(val);
            if (prenda === "" || can === "" || isNaN(val[0]) || isNaN(val[1]) || isNaN(val[2])) {
                alert("Cargar campos primero");
                //console.log(x);
                return false;
            }
            if (parseInt(can) <= 0) {
                alert("Cantidad debe ser mayor a cero");
                //console.log(x);
                return false;
            }

            // var val = parseInt(document.getElementById('tarifa').value);
            //var path = "empty";
            var path = "{{route('servicios_lavanderia.lavanderia')}}";
            var table = "#consumicion_table"
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
            if (table === "#huesped_table") {

                var name_articulo = document.getElementsByName("huesped_detalle[]");
                for (let index = 0; index < name_articulo.length; index++) {
                    const element = name_articulo[index];
                    if (element.value == data.huespedes[0].codigo) {
                        alert("Codigo ya ingresado");
                        return false;
                    }
                }

                //id_monto += 1;
                /* var importe = 0;
                 importe = (parseInt(x[0]) * parseInt(x[1]));
                 var iva = 0;
                 iva = parseInt(importe / parseInt(x[2]));*/
                $(table).bootstrapTable('insertRow', {
                    index: 0,
                    row: [data.huespedes[0].id, data.huespedes[0].nombre + " " + data.huespedes[0].apellido +
                        '<input type="hidden" name ="huesped_detalle[]" value=' + data.huespedes[0].id + '>'
                    ]
                })
                /*total_iva += iva;
                subtotal += importe;
                total = total_iva + subtotal;

                document.getElementById("total_iva").value = total_iva.format(0, 3, '.', ',');
                document.getElementById("subtotal").value = subtotal.format(0, 3, '.', ',');
                document.getElementById("total").value = total.format(0, 3, '.', ',');
                //document.getElementById("importe").value = (total.format(0, 3, '.', ',')).replace(".","");

                document.getElementById("cantidad").value = "";
                document.getElementById("articulo").value = "";
                document.getElementById("precio").value = "";
                document.getElementById("iva").value = "";*/
                document.getElementById("huesped").value = "";
            }
            if (table === "#consumicion_table") {
                var name_articulo = document.getElementsByName("huespedes_detalle[]");
                for (let index = 0; index < name_articulo.length; index++) {
                    const element = document.getElementsByName("prenda_detalle[]")[index];
                    const element1 = document.getElementsByName("huespedes_detalle[]")[index];
                    if (element1.value == prenda && element1.value == data.huespedes[0].id) {
                        alert("Prenda y huesped ya ingresados");
                        return false;
                    }
                }

                var promocion = 0;
                if (data.promocion.length) {
                    promocion = data.promocion[0].porcentaje;
                }

                var descri1 = prenda.replace(/ /g, "_");

                $(table).bootstrapTable('insertRow', {
                    index: 0,
                    row: [data.lavanderia[0].id, data.lavanderia[0].descripcion, can, data.lavanderia[0].precio.format(0, 3, '.', ','),
                        data.habitaciones[0].descripcion, data.huespedes[0].nombre + " " + data.huespedes[0].apellido, promocion + "%" ,
                        prenda+
                        '<input type="hidden" name ="lavanderia_detalle[]" value=' + data.lavanderia[0].id + '>' +
                        '<input type="hidden" name ="cantidad_detalle[]" value=' + parseInt(can) + '>' +
                        '<input type="hidden" name ="prenda_detalle[]" value=' + descri1 + '>' +
                        '<input type="hidden" name ="huespedes_detalle[]" value=' + data.huespedes[0].id + '>' +
                        '<input type="hidden" name ="promocion_detalle[]" value=' + promocion + '>' +
                        '<input type="hidden" name ="habitacion_detalle[]" value=' + data.habitaciones[0].id + '>'
                    ]
                })

                //total += parseInt(parseInt(can) * data.productos[0].precio);

                //document.getElementById("total_iva").value = total_iva.format(0, 3, '.', ',');
                //document.getElementById("subtotal").value = subtotal.format(0, 3, '.', ',');
                //document.getElementById("total").value = total;
                //document.getElementById("importe").value = (total.format(0, 3, '.', ',')).replace(".","");

                document.getElementById("cantidad").value = "";
                document.getElementById("lavanderia").value = "";
                document.getElementById("prenda").value = "";
                document.getElementById("habitacion").value = "";
                document.getElementById("huesped").value = "";
            }
        }

    }
    var id_delete = null;
    $('#huesped_table, #consumicion_table').on('click-row.bs.table', function(e, row, $element, field) {
        id_delete = row;
        //console.log(id_delete[4]);
        //$("#tarifa_table").bootstrapTable('remove', {field: 0, values: [1]});
        //console.log($element.index());
        //var d = $("#tarifa_table").bootstrapTable('getData');
        // console.log(d);
        // console.log(d[0][0]);
        // console.log(d.length);
    })

    $('#huesped_table, #consumicion_table, #habitacion_huespedes').on('click', 'tbody tr', function(event) {
        $(this).addClass('highlight').siblings().removeClass('highlight');
    });

    function remove_all() {
        $("#consumicion_table").bootstrapTable('removeAll');

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
        if (table_remove === "consumicion") {
            var table_rem = "#consumicion_table";

            //   total -= (parseInt(id_delete[2]) * parseInt(id_delete[3].replace(".", "")));

            //   document.getElementById("total").value = total;
        }
        if (table_remove === "huesped") {
            var table_rem = "#huesped_table";
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
        if (table_edit === "consumicion") {
            var table_rem = "#consumicion_table";

            //  total -= (parseInt(id_delete[2]) * parseInt(id_delete[3].replace(".", "")));

            //   document.getElementById("total").value = total;
        }
        if (table_edit === "huesped") {
            var table_edt = "#huesped_table";
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
        if (w == "estadias") {
            myWindow = window.open("{{ route('searcher.estadias') }}", "_blank", "width=1000, height=500, menubar=no, top=50, left=250");
        }
        if (w == "lavanderia") {
            myWindow = window.open("{{ route('searcher.lavanderia') }}", "_blank", "width=1000, height=500, menubar=no, top=50, left=250");
        }
    }

    function openWin1(w) {
        if (w == "habitacion") {
            var link = "{{ route('searcher.habitacion1','0') }}";
            if (document.getElementById("estadia").value !== null && document.getElementById("estadia").value !== "") {
                var position = link.lastIndexOf("/");
                var resultado = link.slice(0, position + 1);
                var link = resultado.concat(document.getElementById("estadia").value);
            }
            myWindow = window.open(link, "_blank", "width=1000, height=500, menubar=no, top=50, left=250");
            document.getElementById("huesped").value = "";
        }
        if (w == "huesped") {
            var link = "{{ route('searcher.huesped1',['0','0']) }}";
            if (document.getElementById("estadia").value !== null && document.getElementById("estadia").value !== "" &&
                document.getElementById("habitacion").value !== "" && document.getElementById("habitacion").value !== null) {
                var position = link.lastIndexOf("/");
                var resultado = link.slice(0, position);
                var position = resultado.lastIndexOf("/");
                var resultado = resultado.slice(0, position + 1);
                var link = resultado.concat(document.getElementById("estadia").value, "/" + document.getElementById("habitacion").value);
            }
            myWindow = window.open(link, "_blank", "width=1000, height=500, menubar=no, top=50, left=250");
        }
    }

    window.addEventListener('focus', play);

    function play() {
        // console.log("hola");
        var estadias = localStorage.getItem("estadias");
        var habitacion = localStorage.getItem("habitacion");
        var lavanderia = localStorage.getItem("lavanderia");
        var huesped = localStorage.getItem("huesped");


        if (estadias != "nothing" && estadias != null) {
            document.getElementById("estadia").value = estadias;
            document.getElementById("huesped").value = "";
            document.getElementById("habitacion").value = "";
            remove_all()
        }
        if (lavanderia != "nothing" && lavanderia != null) {
            document.getElementById("lavanderia").value = lavanderia;
        }
        if (habitacion != "nothing" && habitacion != null) {
            document.getElementById("habitacion").value = habitacion;
        }
        if (huesped != "nothing" && huesped != null) {
            document.getElementById("huesped").value = huesped;
        }
        localStorage.removeItem("estadias");
        localStorage.removeItem("huesped");
        localStorage.removeItem("lavanderia");
        localStorage.removeItem("habitacion");
    }

    $(".readonly").on('keydown paste', function(e) {
        e.preventDefault();
    });

    function validateForm() {
        var taf1 = document.getElementsByName("lavanderia_detalle[]");
        if (taf1.length == 0) {
            alert("Cargar al menos un detalle");
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


    Number.prototype.format = function(n, x, s, c) {
        var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
            num = this.toFixed(Math.max(0, ~~n));

        return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
    };
</script>

@endsection