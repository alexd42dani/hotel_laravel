@extends ('layout')

@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('link')
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.15.5/dist/bootstrap-table.min.css">
@endsection

@section('title','Factura agregar')

@section('script')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.15.5/dist/bootstrap-table.min.js"></script>
<script src="/js/bootstrap-table-es-MX.js"></script>
@endsection

@section('header', 'Factura agregar')

@section('content')

<form onsubmit="return validateForm()" method="POST" action="{{route('factura.store')}}">
    @csrf

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
            <div class="col-md-4">
                <div class="form-group">
                    <label for="numero"> Numero </label>
                    <input type="text" id="numero" name="codigo" readonly required value="{{$numero}}">
                </div>
                <div class="form-group">
                    <label for="condicion">Condicion</label>
                    <select id="condicion" required name="condicion" tabindex="2" onchange="condition()">
                        <option value="">--Selecciona una opción--</option>
                        <option value="Credito">Credito</option>
                        <option value="Contado">Contado</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="canc"> Cantidad de Cuotas </label>
                    <input type="number" id="canc" name="cancuo" value="0" min="0" max="24" tabindex="1">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="timbrado"> Timbrado </label>
                    <input type="text" id="timbrado" name="timbrado" readonly required value="{{$timbrado[0]->nro}}">
                </div>
                <div class="form-group">
                    <label for="cliente">Cliente</label>
                    <input type="text" id="cliente" name="cliente" required class="readonly" autocomplete="off" style="caret-color: transparent !important;">
                    <input type="button" value="..." class=" btn-dark" onclick="openWin('cliente')" tabindex="6">
                </div>
                <div class="form-group">
                    <label for="plazo"> Plazo </label>
                    <input type="number" id="plazo" name="plazo" value="30" min="0" max="60" tabindex="1">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="fecha">Fecha</label>
                    <input type="date" name="fecha" id="fecha" required value="{{\Carbon\Carbon::now(new DateTimeZone('America/Asuncion'))->format('Y-m-d')}}">
                </div>
                <div class="form-group">
                    <label for="total"> Total </label>
                    <input type="text" id="total" name="total" readonly required tabindex="1">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="estadia">Estadia</label>
                    <input type="text" id="estadia" name="estadia" readonly required autocomplete="off">
                    <input type="button" value="..." class="btn-dark" onclick="openWin('estadia')" tabindex="7">
                </div>
            </div>
            <div class="col-md-4">
            </div>
        </div>
        <div class="form-group my-2 col-md-12">
            <h5>Estadia</h5>
        </div>
        <div class="row">
            <div class="col-md-12 form-group">
                <div class="table-responsive-md table-hover from-group" style="overflow-y:auto; height:200px">
                    <table id="estadia_table" data-toggle="table" data-classes="table table-bordered table-hover table-md">
                        <thead>
                            <tr>
                                <th data-sortable="true">Id</th>
                                <th>Habitacion</th>
                                <th>Huespedes</th>
                                <th>Entrada</th>
                                <th>Salida</th>
                                <th>Dias</th>
                                <th>Id</th>
                                <th>Tarifa</th>
                                <th>Precio</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
            </div>
            <div class="col-md-4">
            </div>
        </div>
        <div class="form-group my-2 col-md-12">
            <h5>Factura detalle</h5>
        </div>
        <div class="row">
            <div class="col-md-12 form-group">
                <div class="table-responsive-md table-hover from-group" style="overflow-y:auto; height:200px">
                    <table id="servicios_table" data-toggle="table" data-classes="table table-bordered table-hover table-md">
                        <thead>
                            <tr>
                                <th>Descripcion</th>
                                <th>Descuento</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                                <th>Importe</th>
                                <th>Iva</th>
                                <th>%</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row form-group">
            <div class="col-8 form-group">
            </div>
            <div class="col-3 ">
                <label for="total_iva1" class="form-group1">
                    <span> Total iva</span>
                    <input type="text" id="total_iva1" readonly>
                </label>
            </div>
        </div>
        <div class="row form-group">
            <div class="col-8 form-group">
            </div>
            <div class="col-3 ">
                <label for="subtotal1" class="form-group1">
                    <span> Subtotal</span>
                    <input type="text" id="subtotal1" readonly>
                </label>
            </div>
        </div>
        <div class="row form-group">
            <div class="col-8 form-group">
            </div>
            <div class="col-3 ">
                <label for="total2" class="form-group1">
                    <span> Total Factura </span>
                    <input type="text" id="total2" readonly>
                </label>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-md-10">
                    <div class="d-flex flex-wrap justify-content-md-around ">
                        <div class="p-2 mx-auto"><button type="submit" class="btn btn-dark" onclick="return mensaje_grabar()" tabindex="18">GRABAR</button></div>
                        <div class="p-2 mx-auto"><button type="reset" class="btn btn-dark" onclick="remove_all()">CANCELAR</button></div>
                        <div class="p-2 mx-auto"><button type="button" onclick="location.href = '{{ route('factura.index') }}'" class="btn btn-dark">SALIR</button></div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</form>

<script>
    var iva_total = 0;
    var subtotal = 0;
    var total1 = 0;
    var iva_total1 = 0;
    var subtotal1 = 0;
    var total2 = 0;

    function add(table) {

        if (table === "estadia") {
            var val = parseInt(document.getElementById('estadia').value);
            //console.log(val);
            if (Number.isNaN(val)) {
                alert("Cargar campo primero");
                return false;
            }
            var path = "{{route('factura.estadia')}}";
            var table = "#estadia_table"
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
                    console.log(data);
                    document.getElementById("cliente").value = data.estadias[0].clientes_id;
                    addTable(data, table)
                }
            });
        }


        function addTable(data, table) {

            if (table === "#estadia_table") {
                for (let index = 0; index < data.estadia1.length; index++) {
                    $("#estadia_table").bootstrapTable('insertRow', {
                        index: 0,
                        row: [data.estadia1[index].id_estadia,
                            data.estadia1[index].habitacion,
                            data.estadia1[index].huespedes_can,
                            showDate(data.estadia1[index].entrada),
                            showDate(data.estadia1[index].salida),
                            data.estadia1[index].dias,
                            data.estadia1[index].id_tarifa,
                            data.estadia1[index].tarifa,
                            data.estadia1[index].precio.format(0, 3, '.', ',')
                        ]
                    })
                }

                /*for (let index = 0; index < data.tarifa.length; index++) {
                    $("#tarifa_table").bootstrapTable('insertRow', {
                        index: 0,
                        row: [data.tarifa[index].id,
                            data.tarifa[index].estadia_id,
                            data.tarifa[index].descripcion,
                            data.tarifa[index].precio.format(0, 3, '.', ','),
                            (data.tarifa[index].precio / 11).format(0, 3, '.', ',') +
                            '<input type="hidden" name ="tarifa_id[]" value=' + data.tarifa[index].id + '>' +
                            '<input type="hidden" name ="tarifa_iva[]" value=2>'
                        ]
                    })
                    iva_total += (data.tarifa[index].precio / 11);
                    subtotal += data.tarifa[index].precio;
                }
                total1 = iva_total + subtotal;
                document.getElementById("total_iva").value = iva_total.format(0, 3, '.', ',');
                document.getElementById("subtotal").value = subtotal.format(0, 3, '.', ',');
                document.getElementById("total1").value = total1.format(0, 3, '.', ',');*/

                for (let index = 0; index < data.consumicion.length; index++) {
                    //  var descri_tari1 = data.tarifa1[index].descripcion.replace(/ /g,"_");
                    //  console.log(descri_tari1);
                    //   var descri_tari="Tarifa-".concat(descri_tari1);
                    //console.log(desc_tari);
                    var descri1 = data.consumicion[index].producto.replace(/ /g, "_");
                    var descri = "Consumicion_".concat(descri1).concat('_Habitacion_').concat(data.consumicion[index].habitacion);
                    var desc = (data.consumicion[index].precio * data.consumicion[index].promocion) / 100;
                    var importe = (data.consumicion[index].precio - desc) * data.consumicion[index].cantidad;
                    //console.log(desc);
                    //console.log(importe);
                    $("#servicios_table").bootstrapTable('insertRow', {
                        index: 0,
                        row: ['Consumicion ' + data.consumicion[index].producto + ' Habitación ' + data.consumicion[index].habitacion,
                            data.consumicion[index].promocion + "%",
                            (data.consumicion[index].precio - desc).format(0, 3, '.', ','),
                            data.consumicion[index].cantidad,
                            importe.format(0, 3, '.', ','),
                            (importe / data.consumicion[index].porcentaje_iva).format(0, 3, '.', ','),
                            data.consumicion[index].descripcion_iva +
                            '<input type="hidden" name ="descripcion_detalle[]" value=' + descri + '>' +
                            '<input type="hidden" name ="precio_detalle[]" value=' + (data.consumicion[index].precio - desc) + '>' +
                            '<input type="hidden" name ="cantidad_detalle[]" value=' + data.consumicion[index].cantidad + '>' +
                            '<input type="hidden" name ="iva_detalle[]" value=' + data.consumicion[index].id_iva + '>' +
                            '<input type="hidden" name ="iva_descri[]" value=' + data.consumicion[index].descri_iva + '>' +
                            '<input type="hidden" name ="iva_porcentaje[]" value=' + data.consumicion[index].porcentaje_iva + '>' +
                            '<input type="hidden" name ="tarifa_detalle[]" value=' + data.consumicion[index].tarifa_id + '>'
                        ]
                    })
                    iva_total1 += (importe / data.consumicion[index].porcentaje_iva);
                    subtotal1 += importe;

                }


                for (let index = 0; index < data.traslado.length; index++) {
                    //  var descri_tari1 = data.tarifa1[index].descripcion.replace(/ /g,"_");
                    //  console.log(descri_tari1);
                    //   var descri_tari="Tarifa-".concat(descri_tari1);
                    var descri1 = data.traslado[index].descripcion.replace(/ /g, "_");
                    var descri = "Traslado_".concat(descri1);
                    var desc = (data.traslado[index].precio * data.traslado[index].promocion) / 100;
                    var importe = (data.traslado[index].precio - desc) * 1;
                    $("#servicios_table").bootstrapTable('insertRow', {
                        index: 0,
                        row: ['Traslado ' + data.traslado[index].descripcion,
                            data.traslado[index].promocion + "%",
                            (data.traslado[index].precio - desc).format(0, 3, '.', ','),
                            1,
                            importe.format(0, 3, '.', ','),
                            (importe / data.traslado[index].porcentaje_iva).format(0, 3, '.', ','),
                            data.traslado[index].descripcion_iva +
                            '<input type="hidden" name ="descripcion_detalle[]" value=' + descri + '>' +
                            '<input type="hidden" name ="precio_detalle[]" value=' + (data.traslado[index].precio - desc) + '>' +
                            '<input type="hidden" name ="cantidad_detalle[]" value=' + 1 + '>' +
                            '<input type="hidden" name ="iva_detalle[]" value=' + data.traslado[index].id_iva + '>' +
                            '<input type="hidden" name ="iva_descri[]" value=' + data.traslado[index].descri_iva + '>' +
                            '<input type="hidden" name ="iva_porcentaje[]" value=' + data.traslado[index].porcentaje_iva + '>' +
                            '<input type="hidden" name ="tarifa_detalle[]" value=' + data.traslado[index].tarifa_id + '>'
                        ]
                    })
                    iva_total1 += (importe / data.traslado[index].porcentaje_iva);
                    subtotal1 += importe;

                }

                for (let index = 0; index < data.tarifa1.length; index++) {
                    var descri_tari1 = data.tarifa1[index].descripcion.replace(/ /g, "_");
                    //console.log(descri_tari1);
                    var descri_tari = "Tarifa_".concat(descri_tari1);
                    var desc_tari = (data.tarifa1[index].cantidad_personas - data.tarifa1[index].huespedes_can) * parseInt(data.tarifa1[index].descuento_personas);
                    //console.log(desc_tari);
                    var desc_tari1 = (data.tarifa1[index].precio * desc_tari) / 100;
                    var importe_tarifa = (data.tarifa1[index].precio - desc_tari1) * data.tarifa1[index].dias;
                    $("#servicios_table").bootstrapTable('insertRow', {
                        index: 0,
                        row: ['Tarifa ' + data.tarifa1[index].descripcion,
                            desc_tari + '%',
                            (data.tarifa1[index].precio - desc_tari1).format(0, 3, '.', ','),
                            data.tarifa1[index].dias,
                            importe_tarifa.format(0, 3, '.', ','),
                            (importe_tarifa / data.iva_tarifa[0].porcentaje).format(0, 3, '.', ','),
                            data.iva_tarifa[0].descripcion +
                            '<input type="hidden" name ="descripcion_detalle[]" value=' + descri_tari + '>' +
                            '<input type="hidden" name ="precio_detalle[]" value=' + (data.tarifa1[index].precio - desc_tari1) + '>' +
                            '<input type="hidden" name ="cantidad_detalle[]" value=' + data.tarifa1[index].dias + '>' +
                            '<input type="hidden" name ="iva_detalle[]" value=' + data.iva_tarifa[0].id + '>' +
                            '<input type="hidden" name ="iva_descri[]" value=' + data.iva_tarifa[0].descripcion + '>' +
                            '<input type="hidden" name ="iva_porcentaje[]" value=' + data.iva_tarifa[0].porcentaje + '>' +
                            '<input type="hidden" name ="tarifa_detalle[]" value=' + data.tarifa1[index].id_tarifa + '>'
                        ]
                    })
                    iva_total1 += (importe_tarifa / data.iva_tarifa[0].porcentaje);
                    subtotal1 += importe_tarifa;

                }

                total1 = iva_total1 + subtotal1;
                document.getElementById("total_iva1").value = iva_total1.format(0, 3, '.', ',');
                document.getElementById("subtotal1").value = subtotal1.format(0, 3, '.', ',');
                document.getElementById("total2").value = total1.format(0, 3, '.', ',');

                document.getElementById("total").value = total1.format(0, 3, '.', ',');



                //document.getElementById("estadia").value = "";
            }

        }

    }
    var id_delete = null;
    $('#estadia_table').on('click-row.bs.table', function(e, row, $element, field) {
        id_delete = row;
    })

    $('#estadia_table').on('click', 'tbody tr', function(event) {
        $(this).addClass('highlight').siblings().removeClass('highlight');
    });

    function remove_all() {
        $("#tarifa_table").bootstrapTable('removeAll');
        $("#servicios_table").bootstrapTable('removeAll');
        $("#estadia_table").bootstrapTable('removeAll');
    }

    function remove(table_remove) {
        if (id_delete == null) {
            alert("Seleccionar fila primero");
            return false;
        }
        if (table_remove === "estadia") {
            var table_rem = "#estadia_table";
            //var xx = $('#tarifa_table').bootstrapTable('getData');
            //console.log(xx);
            //console.log(xx[0][0]);
            /* $('#tarifa_table').bootstrapTable('remove', {
                 field: 1,
                 values: [id_delete[0]]
             });
             $('#servicios_table').bootstrapTable('remove', {
                 field: 1,
                 values: [id_delete[0]]
             });*/
            $("#tarifa_table").bootstrapTable('removeAll');
            $("#servicios_table").bootstrapTable('removeAll');
            $("#estadia_table").bootstrapTable('removeAll');
            iva_total = 0;
            subtotal = 0;
            total1 = 0;
            iva_total1 = 0;
            subtotal1 = 0;
            total2 = 0;
            document.getElementById("total_iva1").value = "";
            document.getElementById("subtotal1").value = "";
            document.getElementById("total2").value = "";
            document.getElementById("total_iva").value = "";
            document.getElementById("subtotal").value = "";
            document.getElementById("total1").value = "";

            document.getElementById("total").value = "";
        }
        /*$(table_rem).bootstrapTable('remove', {
            field: 0,
            values: [id_delete[0]]
        });*/


        id_delete = null

    }


    function edit(table_edit) {
        if (id_delete == null) {
            alert("Seleccionar fila primero");
            return false;
        }
        if (table_edit === "tarifa") {
            var table_edt = "#estadia_table";
            $("#tarifa_table").bootstrapTable('removeAll');
            $("#servicios_huespedes").bootstrapTable('removeAll');
            $("#estadia_table").bootstrapTable('removeAll');
            iva_total = 0;
            subtotal = 0;
            total1 = 0;
            iva_total1 = 0;
            subtotal1 = 0;
            total2 = 0;
            document.getElementById("total_iva1").value = "";
            document.getElementById("subtotal1").value = "";
            document.getElementById("total2").value = "";
            document.getElementById("total_iva").value = "";
            document.getElementById("subtotal").value = "";
            document.getElementById("total1").value = "";

            document.getElementById("total").value = "";
        }
        /*$(table_edt).bootstrapTable('remove', {
            field: 0,
            values: [id_delete[0]]
        });*/
        id_delete = null
        add(table_edit);
    }

    function showDate(d) {
        var b = d.split('-')
        return b[2] + '/' + b[1] + '/' + b[0];
    }

    function openWin(w) {
        if (w == "cliente") {
            myWindow = window.open("{{ route('searcher.clientes') }}", "_blank", "width=1000, height=500, menubar=no, top=50, left=250");
        }
        if (w == "reserva") {
            myWindow = window.open("{{ route('searcher.reservas') }}", "_blank", "width=1000, height=500, menubar=no, top=50, left=250");
        }
        if (w == "estadia") {
            myWindow = window.open("{{ route('searcher.estadias') }}", "_blank", "width=1000, height=500, menubar=no, top=50, left=250");
        }
    }

    window.addEventListener('focus', play);

    function play() {
        // console.log("hola");
        var clientes = localStorage.getItem("clientes");
        var reservas = localStorage.getItem("reservas");
        var estadias = localStorage.getItem("estadias");

        if (clientes != "nothing" && clientes != null) {
            document.getElementById("cliente").value = clientes;
        }
        if (reservas != "nothing" && reservas != null) {
            document.getElementById("reserva").value = reservas;
        }
        if (estadias != "nothing" && estadias != null) {
            document.getElementById("estadia").value = estadias;
            add('estadia');
        }

        localStorage.removeItem("clientes");
        localStorage.removeItem("reservas");
        localStorage.removeItem("estadias");
    }

    $(".readonly").on('keydown paste', function(e) {
        e.preventDefault();
    });

    function validateForm() {
        var taf = document.getElementsByName("descripcion_detalle[]");
        if (taf.length == 0) {
            alert("Cargar detalle factura");
            return false;
        }
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

    function condition() {
        var sele = document.getElementById("condicion").value;
        if (sele != "" || sele == "Contado") {
            document.getElementById("plazo").value = 0;
            document.getElementById("canc").value = 0;
            document.getElementById("plazo").setAttribute("readonly", true);
            document.getElementById("canc").setAttribute("readonly", true);
        }
        if (sele == "Credito") {
            document.getElementById("plazo").removeAttribute("readonly");
            document.getElementById("canc").removeAttribute("readonly");
            document.getElementById("plazo").value = 30;
            document.getElementById("canc").value = 1;
            document.getElementById("plazo").setAttribute("min", 1);
            document.getElementById("canc").setAttribute("min", 1);
        }
    }
</script>

@endsection