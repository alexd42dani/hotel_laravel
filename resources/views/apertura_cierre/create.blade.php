@extends ('layout')

@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('link')
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.15.5/dist/bootstrap-table.min.css">
@endsection

@section('title','Apertura y cierre agregar')

@section('script')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.15.5/dist/bootstrap-table.min.js"></script>
<script src="/js/bootstrap-table-es-MX.js"></script>
@endsection

@section('header', 'Apertura y cierre agregar')

@section('content')

<form method="POST" action="{{route('apertura.store')}}">
    @csrf

    <div class="container" style="margin-top:30px">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="codigo"> Codigo </label>
                    <input type="text" id="codigo" name="codigo" readonly>
                </div>
                <div class="form-group">
                    <label for="fechaa">Fecha apertura</label>
                    <input type="date" id="fechaa" name="fechaa" required value="{{\Carbon\Carbon::now(new DateTimeZone('America/Asuncion'))->format('Y-m-d')}}">
                </div>
                <div class="form-group">
                    <label for="fechac">Fecha cierre</label>
                    <input type="date" id="fechac" name="fechac" readonly>
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
                    <input type="time" id="horaa" name="horaa" required>
                </div>
                <div class="form-group">
                    <label for="horae">Hora cierre</label>
                    <input type="time" id="horae" name="horac" readonly>
                </div>
                <div class="form-group">
                    <label for="m_efectivo">Monto efectivo</label>
                    <input type="text" id="m_efectivo" readonly>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="caja"> Caja </label>
                    <input type="text" id="caja" readonly value="{{$cajas[0]->descripcion}}">
                </div>
                <div class="form-group">
                    <label for="si"> Saldo inicial </label>
                    <input type="number" id="salini" name="salini" value="" min="0" max="100000000" required>
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
                <div class="col-md-12">
                    <div class="d-flex flex-wrap justify-content-md-around ">
                        <div class="p-2 mx-auto"><button type="submit" class="btn btn-dark" onclick="return mensaje_grabar()">GRABAR</button></div>
                        <div class="p-2 mx-auto"><button type="resert" class="btn btn-dark">CANCELAR</button></div>
                        <div class="p-2 mx-auto"><button type="button" class="btn btn-dark" onclick="location.href =' {{ route('apertura.index') }}'">SALIR</button></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</form>

<script>
    function showDate(d) {
        var b = d.split('-')
        return b[2] + '/' + b[1] + '/' + b[0];
    }



    $(".readonly").on('keydown paste', function(e) {
        e.preventDefault();
    });

    document.querySelector('#salini').addEventListener("keypress", function(evt) {
        if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57) {
            evt.preventDefault();
        }
    });
    document.querySelector('#salfin').addEventListener("keypress", function(evt) {
        if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57) {
            evt.preventDefault();
        }
    });



    function mensaje_grabar() {
        return confirm('Desea grabar el registro');
    }
</script>

@endsection