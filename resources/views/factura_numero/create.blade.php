@extends ('layout')

@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('link')
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.15.5/dist/bootstrap-table.min.css">
@endsection

@section('title','Factura numero agregar')

@section('script')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.15.5/dist/bootstrap-table.min.js"></script>
<script src="/js/bootstrap-table-es-MX.js"></script>
@endsection

@section('header', 'Factura numero agregar')

@section('content')

<form onsubmit="return validateForm()" method="POST" action="{{route('factura_numero.store')}}">
    @csrf

    <div class="container" style="margin-top:30px">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="numero"> Codigo </label>
                    <input type="text" id="numero" name="numero" autocomplete="off" class="readonly" style="caret-color: transparent !important;">
                </div>
                <div class="form-group">
                    <label for="timbrado">Timbrado</label>
                    <input type="text" id="timbrado" name="timbrado" class="readonly" required autocomplete="off" style="caret-color: transparent !important;">
                    <input type="button" value="..." class=" btn-dark" onclick="openWin('timbrado')">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="nro_desde">Nro desde</label>
                    <input type="number" id="nro_desde" name="nro_desde" value="" min="1" max="9999999" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="nro_hasta">Nro hasta</label>
                    <input type="number" id="nro_hasta" name="nro_hasta" value="" min="1" max="9999999" required>
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
                    <div class="p-2 mx-auto"><button type="button" onclick="location.href = '{{ route('factura_numero.index') }}'" class="btn btn-dark">SALIR</button></div>
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

        if (table === "orden") {
            //console.log(val);
            var ca = document.getElementById("cantidad");
            var val = parseInt(document.getElementById("articulo").value);
            var pr = document.getElementById("precio");
            var iv = document.getElementById("iva");
            var x = [];
            //x[0] = de.value;
            x[0] = ca.value;
            x[1] = pr.value;
            x[2] = iv.value;
            x[3] = iv.text;
            if (x[0] === "" || x[1] === "" ||
                x[2] === "" || val === "") {
                alert("Cargar campos primero");
                //console.log(x);
                return false;
            }
            if (parseInt(x[0]) <= 0) {
                alert("Cantidad debe ser mayor a cero");
                //console.log(x);
                return false;
            }
            // var val = parseInt(document.getElementById('tarifa').value);
            //var path = "empty";
            var path = "{{route('requisicion.articulo')}}";
            var table = "#orden_table"
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
            if (table === "#orden_table") {

                var name_articulo = document.getElementsByName("articulo_detalle[]");
                for (let index = 0; index < name_articulo.length; index++) {
                    const element = name_articulo[index];
                    if (element.value == data.articulos[0].codigo) {
                        alert("Codigo ya ingresado");
                        return false;
                    }
                }

                //id_monto += 1;
                var importe = 0;
                importe = (parseInt(x[0]) * parseInt(x[1]));
                var iva = 0;
                iva = parseInt(importe / parseInt(x[2]));
                $(table).bootstrapTable('insertRow', {
                    index: 0,
                    row: [data.articulos[0].codigo, data.articulos[0].nombre, x[0], parseInt(x[1]).format(0, 3, '.', ','), importe.format(0, 3, '.', ','), iva.format(0, 3, '.', ',') +
                        '<input type="hidden" name ="articulo_detalle[]" value=' + data.articulos[0].codigo + '>' +
                        '<input type="hidden" name ="precio_detalle[]" value=' + x[1] + '>' +
                        '<input type="hidden" name ="cantidad_detalle[]" value=' + x[0] + '>' +
                        '<input type="hidden" name ="iva_detalle[]" value=' + x[2] + '>'
                    ]
                })
                total_iva += iva;
                subtotal += importe;
                total = total_iva + subtotal;

                document.getElementById("total_iva").value = total_iva.format(0, 3, '.', ',');
                document.getElementById("subtotal").value = subtotal.format(0, 3, '.', ',');
                document.getElementById("total").value = total.format(0, 3, '.', ',');
                //document.getElementById("importe").value = (total.format(0, 3, '.', ',')).replace(".","");

                document.getElementById("cantidad").value = "";
                document.getElementById("articulo").value = "";
                document.getElementById("precio").value = "";
                document.getElementById("iva").value = "";
            }
        }

    }
    var id_delete = null;
    $('#orden_table, #cheque_table').on('click-row.bs.table', function(e, row, $element, field) {
        id_delete = row;
        //console.log(id_delete[4]);
        //$("#tarifa_table").bootstrapTable('remove', {field: 0, values: [1]});
        //console.log($element.index());
        //var d = $("#tarifa_table").bootstrapTable('getData');
        // console.log(d);
        // console.log(d[0][0]);
        // console.log(d.length);
    })

    $('#orden_table, #cheque_table, #habitacion_huespedes').on('click', 'tbody tr', function(event) {
        $(this).addClass('highlight').siblings().removeClass('highlight');
    });

    function remove_all() {
        $("#orden_table").bootstrapTable('removeAll');
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
        if (w == "articulo") {
            myWindow = window.open("{{ route('searcher.articulo') }}", "_blank", "width=1000, height=500, menubar=no, top=50, left=250");
        }
        if (w == "timbrado") {
            myWindow = window.open("{{ route('searcher.timbrado') }}", "_blank", "width=1000, height=500, menubar=no, top=50, left=250");
        }
        if (w == "requisicion") {
            myWindow = window.open("{{ route('searcher.requisicion') }}", "_blank", "width=1000, height=500, menubar=no, top=50, left=250");
        }
    }

    window.addEventListener('focus', play);

    function play() {
        // console.log("hola");
        var articulo = localStorage.getItem("articulo");
        var timbrado = localStorage.getItem("timbrado");
        var requisicion = localStorage.getItem("requisicion");

        if (articulo != "nothing" && articulo != null) {
            document.getElementById("articulo").value = articulo;
        }
        if (requisicion != "nothing" && requisicion != null) {
            document.getElementById("requisicion").value = requisicion;
        }
        if (timbrado != "nothing" && timbrado != null) {
            document.getElementById("timbrado").value = timbrado;
        }
        localStorage.removeItem("articulo");
        localStorage.removeItem("requisicion");
        localStorage.removeItem("timbrado");
    }

    $(".readonly").on('keydown paste', function(e) {
        e.preventDefault();
    });

    function validateForm() {
        var val1 = document.getElementById("nro_desde").value;
        var val2 = document.getElementById("nro_hasta").value;
        if (parseInt(val1) >= parseInt(val2)) {
            alert("Numero desde debe ser menor a numero hasta");
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

    document.querySelector('#timbrado').addEventListener("keypress", function(evt) {
        if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57) {
            evt.preventDefault();
        }
    });
    document.querySelector('#nro_desde').addEventListener("keypress", function(evt) {
        if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57) {
            evt.preventDefault();
        }
    });
    document.querySelector('#nro_hasta').addEventListener("keypress", function(evt) {
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