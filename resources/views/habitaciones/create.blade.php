@extends ('layout')

@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('link')
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.15.5/dist/bootstrap-table.min.css">
@endsection

@section('title','Habitaciones agregar')

@section('script')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.15.5/dist/bootstrap-table.min.js"></script>
<script src="/js/bootstrap-table-es-MX.js"></script>
@endsection

@section('header', 'Habitaciones agregar')

@section('content')

<form onsubmit="return validateForm()" method="POST" action="{{route('habitaciones.store')}}">
    @csrf

    <div class="container" style="margin-top:30px">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="id"> Codigo </label>
                    <input type="text" id="id" name="codigo" readonly>
                </div>
                <div class="form-group">
                    <label for="capacidad"> Capacidad </label>
                    <input type="number" id="capacidad" name="capacidad" value="0" min="0" max="50" required>
                </div>
                <div class="form-group">
                    <label for="estado_habitacion">Estado habitacion</label>
                    <select id="estado_habitacion" name="estado_habitacion" required> 
                        <option value="">--Selecciona una opción--</option>
                        @foreach($habitacion_estados as $estado_h)
                        <option value={{ $estado_h->id }}>{{ $estado_h->descripcion }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <select id="nombre" name="nombre" required> 
                        <option value="">--Selecciona una opción--</option>
                        @foreach($habitacion_nombres as $nom)
                        <option value={{ $nom->id }}>{{ $nom->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="tipo_habitacion">Tipo habitacion</label>
                    <select id="tipo_habitacion" name="tipo_habitacion" required> 
                        <option value="">--Selecciona una opción--</option>
                        @foreach($tipos_habitaciones as $tipo_h)
                        <option value={{ $tipo_h->id }}>{{ $tipo_h->descripcion }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="precio"> Precio </label>
                    <input type="number" id="precio" name="precio" value="0" min="0" max="1000000000" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="descripcion"> Descripcion </label>
                    <input type="text" id="descripcion" required name="descripcion">
                </div>
                <div class="form-group">
                    <label for="ubicacion">Ubicacion</label>
                    <select id="ubicacion" name="ubicacion" required> 
                        <option value="">--Selecciona una opción--</option>
                        @foreach($ubicaciones as $ubicacion)
                        <option value={{ $ubicacion->id }}>{{ $ubicacion->descripcion }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="caracteristicas">Caracteristicas</label>
                    <select id="caracteristicas" }> 
                        <option value="">--Selecciona una opción--</option>
                        @foreach($caracteristicas as $carac)
                        <option value={{ $carac->id }}>{{ $carac->descripcion }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="can"> Cantidad </label>
                    <input type="number" id="cantidad" value="" min="0" max="1000000000">
                </div>
            </div>
        </div>
        <div class="form-group my-2 col-md-10">
            <h5>Caracteristicas</h5>
        </div>
        <div class="row">
            <div class="col-md-10 form-group">
                <div class="table-responsive-md table-hover from-group" style="overflow-y:auto; height:200px">
                <table id="caracteristicas_table" data-toggle="table" data-classes="table table-bordered table-hover table-md">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Caracteristicas</th>
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
                    <button type="button" class="btn btn-dark form-group" onclick="add('caracteristicas')" tabindex="8">Agregar</button>
                </div>
                <div class="col-md-12">
                    <button type="button" class="btn btn-dark form-group" onclick="remove('caracteristicas')">Quitar</button>
                </div>
                <div class="col-md-12">
                    <button type="button" class="btn btn-dark form-group" style="display: none;" onclick="edit('caracteristicas')">Editar</button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="cama">Cama</label>
                    <input type="text" id="cama" readonly autocomplete="off">
                    <input type="button" value="..." class="btn-dark" onclick="openWin('cama')" tabindex="7">
                </div>
            </div>
            <div class="col-md-4">
            </div>
        </div>
        <div class="form-group my-2 col-md-10">
            <h5>Cama</h5>
        </div>
        <div class="row">
            <div class="col-md-10 form-group">
                <div class="table-responsive-md table-hover from-group" style="overflow-y:auto; height:200px">
                <table id="cama_table" data-toggle="table" data-classes="table table-bordered table-hover table-md">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Cama</th>
                                <th>Capacidad</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-2 form-group">
            <div class="col-md-12">
                    <button type="button" class="btn btn-dark form-group" onclick="add('cama')" tabindex="8">Agregar</button>
                </div>
                <div class="col-md-12">
                    <button type="button" class="btn btn-dark form-group" onclick="remove('cama')">Quitar</button>
                </div>
                <div class="col-md-12">
                    <button type="button" class="btn btn-dark form-group" onclick="edit('cama')">Editar</button>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-md-10">
                    <div class="d-flex flex-wrap justify-content-md-around ">
                        <div class="p-2 mx-auto"><button type="submit" class="btn btn-dark" onclick="return mensaje_grabar()" tabindex="18">GRABAR</button></div>
                        <div class="p-2 mx-auto"><button type="reset" class="btn btn-dark" onclick="remove_all()">CANCELAR</button></div>
                        <div class="p-2 mx-auto"><button type="button" onclick="location.href = '{{ route('habitaciones.index') }}'" class="btn btn-dark">SALIR</button></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</form>

<script>
    function add(table) {

        if (table === "caracteristicas") {
            var can = parseInt(document.getElementById('cantidad').value);
            var e = document.getElementById("caracteristicas");
            var x = [];
            x[0] = e.options[e.selectedIndex].value;
            x[1] = e.options[e.selectedIndex].text;
            //console.log(val);
            if (x[0]=="" || isNaN(can)) {
                alert("Cargar campos primero");
                return false;
            }
            if (can <= 0) {
                alert("Cantidad debe ser mayor a cero");
                //console.log(x);
                return false;
            }
            var path = "nothing";
            var table = "#caracteristicas_table";
            addTable(x, table);
        }

        if (table === "cama") {
            var val = parseInt(document.getElementById("cama").value);
            if (isNaN(val)) {
                alert("Cargar campo primero");
                return false;
            }
            var path = "{{route('habitaciones.cama')}}";
            var table = "#cama_table"
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
            if (table === "#caracteristicas_table") {
                var name_tarifa = document.getElementsByName("caracteristicas_detalle[]");
                for (let index = 0; index < name_tarifa.length; index++) {
                    const element = name_tarifa[index];
                    if (element.value == data[0]) {
                        alert("Codigo ya ingresado");
                        return false;
                    }
                }
                $(table).bootstrapTable('insertRow', {
                    index: 0,
                    row: [data[0],
                        data[1] , can+
                        '<input type="hidden" name ="caracteristicas_detalle[]" value=' + data[0] + '>' +
                        '<input type="hidden" name ="cantidad_detalle[]" value=' + can + '>' 
                        
                    ]
                })
                //console.log(data.tarifas[0].habitacion_id);
                // var combo_habitacion = document.getElementsByClassName("hidden");
               // var combo_habitacion = document.getElementsByName("combo_habi[]");
                //console.log(combo_habitacion);
                //console.log(combo_habitacion.value);
                //console.log(combo_habitacion.text);
                //console.log(combo_habitacion[1]);
               /* for (let index = 0; index < combo_habitacion.length; index++) {
                    const element = combo_habitacion[index];
                    if (element.value == data.tarifas[0].habitacion_id) {
                        //console.log(element.value);
                        element.setAttribute("class", "show");
                        $('.show').show();
                    }
                }*/

                document.getElementById("cantidad").value = "";
                document.getElementById("caracteristicas").value = "";
            }
            if (table === "#habitacion_huespedes") {
                var e = document.getElementById("habitacion");
                var id_hues = document.getElementById('persona').value;
                var name_ciudad = document.getElementsByName("persona_ciudad[]");
                var name_documento = document.getElementsByName("persona_documento[]");
                for (let index = 0; index < name_ciudad.length; index++) {
                    const element = name_ciudad[index];
                    const element1 = name_documento[index];
                    const element2 = element.value + "-" + element1.value;
                    if (element2 == id_hues) {
                        alert("Codigo ya ingresado");
                        return false;
                    }
                }

                $(table).bootstrapTable('insertRow', {
                    index: 0,
                    row: [document.getElementById('persona').value,
                        data.personas[0].nombre + " " + data.personas[0].apellido,
                        e.options[e.selectedIndex].value,
                        e.options[e.selectedIndex].text +
                        '<input type="hidden" name ="habitacion_huesped[]" value=' + e.options[e.selectedIndex].value + '>' +
                        '<input type="hidden" name ="persona_ciudad[]" value=' + val[0] + '>' +
                        '<input type="hidden" name ="persona_documento[]" value=' + val[1] + '>'
                    ]
                })
                document.getElementById('persona').value = "";
                var e = document.getElementById("habitacion");
                e.value = "";
            }
            if (table === "#cama_table") {
                var name_habitacion = document.getElementsByName("cama_detalle[]");
                for (let index = 0; index < name_habitacion.length; index++) {
                    const element = name_habitacion[index];
                    if (element.value == data.camas[0].id) {
                        alert("Codigo ya ingresado");
                        return false;
                    }
                }
                $(table).bootstrapTable('insertRow', {
                    index: 0,
                    row: [data.camas[0].id, data.camas[0].descripcion, data.camas[0].capacidad +
                        '<input type="hidden" name ="cama_detalle[]" value=' + data.camas[0].id + '>' 
                    ]
                })
                
                document.getElementById("cama").value = "";
            }
        }

    }
    var id_delete = null;
    $('#caracteristicas_table, #cama_table, #habitacion_huespedes').on('click-row.bs.table', function(e, row, $element, field) {
        id_delete = row;
        //console.log(id_delete[2]);
        //$("#tarifa_table").bootstrapTable('remove', {field: 0, values: [1]});
        //console.log($element.index());
        //var d = $("#tarifa_table").bootstrapTable('getData');
        // console.log(d);
        // console.log(d[0][0]);
        // console.log(d.length);
    })

    $('#cama_table, #caracteristicas_table, #habitacion_huespedes').on('click', 'tbody tr', function(event) {
        $(this).addClass('highlight').siblings().removeClass('highlight');
    });

    function remove_all() {
        $("#cama_table").bootstrapTable('removeAll');
        $("#caracteristicas_huespedes").bootstrapTable('removeAll');
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
        if (table_remove === "caracteristicas") {
            var table_rem = "#caracteristicas_table";
        }
        if (table_remove === "cama") {
            var table_rem = "#cama_table";
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
        if (table_edit === "caracteristicas") {
            var table_edt = "#caracteristicas_table";
        }
        if (table_edit === "cama") {
            var table_edt = "#cama_table";
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
        if (w == "cama") {
            myWindow = window.open("{{ route('searcher.cama') }}", "_blank", "width=1000, height=500, menubar=no, top=50, left=250");
        }
        if (w == "reserva") {
            myWindow = window.open("{{ route('searcher.reservas') }}", "_blank", "width=1000, height=500, menubar=no, top=50, left=250");
        }
        if (w == "tarifa") {
            myWindow = window.open("{{ route('searcher.tarifas') }}", "_blank", "width=1000, height=500, menubar=no, top=50, left=250");
        }
        if (w == "persona") {
            myWindow = window.open("{{ route('searcher.personas') }}", "_blank", "width=1000, height=500, menubar=no, top=50, left=250");
        }
    }

    window.addEventListener('focus', play);

    function play() {
        // console.log("hola");
        var cama = localStorage.getItem("cama");
        var reservas = localStorage.getItem("reservas");
        var tarifas = localStorage.getItem("tarifas");
        var personas = localStorage.getItem("personas");

        if (cama != "nothing" && cama != null) {
            document.getElementById("cama").value = cama;
        }
        if (reservas != "nothing" && reservas != null) {
            document.getElementById("reserva").value = reservas;
        }
        if (tarifas != "nothing" && tarifas != null) {
            document.getElementById("tarifa").value = tarifas;
        }
        if (personas != "nothing" && personas != null) {
            document.getElementById("persona").value = personas;
        }

        localStorage.removeItem("cama");
        localStorage.removeItem("reservas");
        localStorage.removeItem("tarifas");
        localStorage.removeItem("personas");
    }

    $(".readonly").on('keydown paste', function(e) {
        e.preventDefault();
    });

    function validateForm() {
        var taf = document.getElementsByName("caracteristicas_detalle[]");
        if (taf.length == 0) {
            alert("Cargar al menos una caracteristica");
            return false;
        }
        var habi = document.getElementsByName("cama_detalle[]");
        if (habi.length == 0) {
            alert("Cargar al menos una cama");
            return false;
        }
       
        //console.log(taf);
        // console.log(taf.length);
        //console.log(x[0]);
        //console.log(x[0].value);
    }

    $('.hidden').hide();
    $('.show').show();

    function mensaje_grabar(){
            return confirm('Desea grabar el registro');
        }
</script>

@endsection