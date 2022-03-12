@extends ('layout')

@section('title','Menu')

@section('header', 'Menu')

@section('content')

<nav class="navbar navbar-expand-md bg-dark navbar-dark">
    <div class="container-fluid">
        <a href="{{ route('menu') }}" class="navbar-brand font-weight-bold">Menu</a>

        <button type="button" data-toggle="collapse" data-target="#navbarContent" aria-controls="navbars" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler">
            <span class="navbar-toggler-icon"></span>
        </button>


        <div id="navbarContent" class="collapse navbar-collapse">
            <ul class="navbar-nav mr-auto">
            @if($nivel=="ADMIN")
                <!-- nav dropdown -->
                <li class="nav-item dropdown">

                    <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle">Referenciales</a>
                    <ul class="dropdown-menu">
                        <!-- lvl 1 dropdown -->
                        <li class="dropdown-submenu">
                            <a href="#" role="button" data-toggle="dropdown" class="dropdown-item dropdown-toggle">Compra</a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ route('articulos.index') }}" class="dropdown-item">Articulos</a></li>
                                <li><a href="{{ route('area.index') }}" class="dropdown-item">Area</a></li>
                                <li><a href="{{ route('categoria.index') }}" class="dropdown-item">Categoria</a></li>
                                <li><a href="{{ route('proveedor.index') }}" class="dropdown-item">Proveedor</a></li>
                                <li><a href="{{ route('unidad.index') }}" class="dropdown-item">Unidad</a></li>
                            </ul>
                        </li>

                        <li class="dropdown-submenu">
                            <a href="#" role="button" data-toggle="dropdown" class="dropdown-item dropdown-toggle">Servicios</a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ route('productos.index') }}" class="dropdown-item">Productos</a></li>
                                <li><a href="{{ route('spa_sauna.index') }}" class="dropdown-item">Spa y Sauna</a></li>
                                <li><a href="{{ route('turismo.index') }}" class="dropdown-item">Turismo</a></li>
                            </ul>
                        </li>

                        <li class="dropdown-submenu">
                            <a href="#" role="button" data-toggle="dropdown" class="dropdown-item dropdown-toggle">Tarifa</a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ route('tarifa.index') }}" class="dropdown-item">Tarifas</a></li>
                                <li><a href="{{ route('temporadas.index') }}" class="dropdown-item">Temporadas</a></li>
                                <li><a href="{{ route('tarifas_nombres.index') }}" class="dropdown-item">Tarifas Nombres</a></li>
                            </ul>
                        </li>

                        <li class="dropdown-submenu">
                            <a href="#" role="button" data-toggle="dropdown" class="dropdown-item dropdown-toggle">Habitaciones</a>
                            <ul class="dropdown-menu">
                            <li><a href="{{ route('habitaciones.index') }}" class="dropdown-item">Habitaciones</a></li>
                            <li><a href="{{ route('habitacion_estado.index') }}" class="dropdown-item">Habitacion Estado</a></li>
                            <li><a href="{{ route('ubicaciones.index') }}" class="dropdown-item">Ubicaciones</a></li>
                            <li><a href="{{ route('habitacion_nombres.index') }}" class="dropdown-item">Habitacion Nombres</a></li>
                            <li><a href="{{ route('tipos_habitacion.index') }}" class="dropdown-item">Tipo habitacion</a></li>
                            <li><a href="{{ route('caracteristicas.index') }}" class="dropdown-item">Caractristicas</a></li>
                            <li><a href="{{ route('cama.index') }}" class="dropdown-item">Cama</a></li>
                            </ul>
                        </li>

                        <li class="dropdown-submenu">
                            <a href="#" role="button" data-toggle="dropdown" class="dropdown-item dropdown-toggle">Alojamiento y reserva</a>
                            <ul class="dropdown-menu">
                            <li><a href="{{ route('clientes.index') }}" class="dropdown-item">Clientes</a></li>
                            <li><a href="{{ route('operador_turistico.index') }}" class="dropdown-item">Operador Turistico</a></li>
                            <li><a href="{{ route('tipo_cliente.index') }}" class="dropdown-item">Tipo cliente</a></li>
                            <li><a href="{{ route('tipo_reserva.index') }}" class="dropdown-item">Tipo reserva</a></li>
                            <li><a href="{{ route('tipo_estadia.index') }}" class="dropdown-item">Tipo estadia</a></li>
                            </ul>
                        </li>

                        <li class="dropdown-submenu">
                            <a href="#" role="button" data-toggle="dropdown" class="dropdown-item dropdown-toggle">Personas</a>
                            <ul class="dropdown-menu">
                            <li><a href="{{ route('personas.index') }}" class="dropdown-item">Persona</a></li>
                            <li><a href="{{ route('pais.index') }}" class="dropdown-item">Pais</a></li>
                            <li><a href="{{ route('departamento.index') }}" class="dropdown-item">Departamento</a></li>
                            <li><a href="{{ route('ciudad.index') }}" class="dropdown-item">Ciudad</a></li>
                            <li><a href="{{ route('tipo_documento.index') }}" class="dropdown-item">Tipo documento</a></li>
                            <li><a href="{{ route('cargo.index') }}" class="dropdown-item">Cargo</a></li>
                            </ul>
                        </li>

                        <li class="dropdown-submenu">
                            <a href="#" role="button" data-toggle="dropdown" class="dropdown-item dropdown-toggle">Facturacion</a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ route('sucursal.index') }}" class="dropdown-item">Sucursal</a></li>
                                <li><a href="{{ route('timbrado.index') }}" class="dropdown-item">Timbrado</a></li>
                                <li><a href="{{ route('factura_numero.index') }}" class="dropdown-item">Factura numero</a></li>
                                <li><a href="{{ route('caja.index') }}" class="dropdown-item">Caja</a></li>
                                <li><a href="{{ route('user_numero.index') }}" class="dropdown-item">User numero</a></li>
                                <li><a href="{{ route('entidad.index') }}" class="dropdown-item">Entidad</a></li>
                                <li><a href="{{ route('marca_tarjeta.index') }}" class="dropdown-item">Marca Tarjeta</a></li>
                                <li><a href="{{ route('procesadora.index') }}" class="dropdown-item">Procesadora</a></li>
                                <li><a href="{{ route('tipo_tarjeta.index') }}" class="dropdown-item">Tipo Tarjeta</a></li>
                                <li><a href="{{ route('iva.index') }}" class="dropdown-item">Iva</a></li>
                            </ul>
                        </li>

                    </ul>
                </li>
                @endif
                @if($nivel=="COMPRA"||$nivel=="ADMIN")
                <li class="nav-item dropdown">

                    <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle">Compra</a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('requisicion.index') }}" class="dropdown-item">Requisicion</a></li>
                        <li><a href="{{ route('presupuesto.index') }}" class="dropdown-item">Presupuesto</a></li>
                        <li><a href="{{ route('orden.index') }}" class="dropdown-item">Orden de Compra</a></li>
                        <li><a href="{{ route('factura_compra.index') }}" class="dropdown-item">Factura</a></li>
                        <li><a href="{{ route('remision.index') }}" class="dropdown-item">Nota de remision</a></li>
                        <li><a href="{{ route('entrada.index') }}" class="dropdown-item">Entrada</a></li>
                        <li><a href="{{ route('salida.index') }}" class="dropdown-item">Salida</a></li>
                        <li><a href="{{ route('ajuste.index') }}" class="dropdown-item">Ajuste</a></li>
                        <li><a href="{{ route('nota_credito_c.index') }}" class="dropdown-item">Nota de credito</a></li>
                        <li><a href="{{ route('nota_debito_c.index') }}" class="dropdown-item">Nota de debito</a></li>
                    </ul>
                </li>
                @endif
                @if($nivel=="SERVICIOS"||$nivel=="ADMIN")
                <li class="nav-item dropdown">

                    <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle">Servicios</a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('reserva.index') }}" class="dropdown-item">Reserva</a></li>
                        <li><a href="{{ route('estadia.index') }}" class="dropdown-item">Estadia</a></li>
                        <li><a href="{{ route('servicios_consumicion.index') }}" class="dropdown-item">Servicios Consumicion</a></li>
                        <li><a href="{{ route('servicios_traslado.index') }}" class="dropdown-item">Servicios Traslado</a></li>
                        <li><a href="{{ route('servicios_spa_sauna.index') }}" class="dropdown-item">Servicios Spa Sauna</a></li>
                        <li><a href="{{ route('servicios_turismo.index') }}" class="dropdown-item">Servicios Turismo</a></li>
                        <li><a href="{{ route('servicios_lavanderia.index') }}" class="dropdown-item">Servicios Lavanderia</a></li>
                    </ul>
                </li>
                @endif
                @if($nivel=="FACTURACION"||$nivel=="ADMIN")
                <li class="nav-item dropdown">

                    <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle">Facturacion</a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('factura.index') }}" class="dropdown-item">Factura</a></li>
                        <li><a href="{{ route('apertura.index') }}" class="dropdown-item">Apertura y Cierre</a></li>
                        <li><a href="{{ route('cobros.index') }}" class="dropdown-item">Cobros</a></li>
                        <li><a href="{{ route('arqueo.index') }}" class="dropdown-item">Arqueo</a></li>
                        <li><a href="{{ route('nota_de_credito.index') }}" class="dropdown-item">Nota de Credito</a></li>
                        <li><a href="{{ route('nota_de_debito.index') }}" class="dropdown-item">Nota de Debito</a></li>
                    </ul>
                </li>
                @endif
                <li class="nav-item dropdown">

                    <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle">Informes</a>
                    <ul class="dropdown-menu">
                    <li><a class="dropdown-item" style="font-weight:bold;text-align:center">--Facturacion--</a></li>
                        <li><a href="http://localhost:8080/reportes/Listado_Facturas.jsp" target="_blank" class="dropdown-item">Factura</a></li>
                        <li><a href="http://localhost:8080/reportes/Listado_libro_venta.jsp" target="_blank" class="dropdown-item">Libro ventas</a></li>
                        <li><a href="http://localhost:8080/reportes/Listado_Apertura_Cierre.jsp" target="_blank" class="dropdown-item">Apertura y Cierre</a></li>
                        <li><a href="http://localhost:8080/reportes/Listado_Cobros.jsp" target="_blank" class="dropdown-item">Cobros</a></li>
                        <li><a href="http://localhost:8080/reportes/Listado_Arqueo.jsp" target="_blank" class="dropdown-item">Arqueo</a></li>
                        <li><a href="http://localhost:8080/reportes/Listado_Cuentas_Cobrar.jsp" target="_blank" class="dropdown-item">Cuentas a Cobrar</a></li>
                        <li><a href="http://localhost:8080/reportes/Listado_recaudaciones.jsp" target="_blank" class="dropdown-item">Recaudaciones a depositar</a></li>
                        <li><a href="http://localhost:8080/reportes/Listado_Nota_Credito.jsp" target="_blank" class="dropdown-item">Nota Credito</a></li>
                        <li><a href="http://localhost:8080/reportes/Listado_Nota_Debito.jsp" target="_blank" class="dropdown-item">Nota Debito</a></li>
                        <li><a class="dropdown-item" style="font-weight:bold;text-align:center">--Servicios--</a></li>
                        <li><a href="http://localhost:8080/reportes/Listado_Estadia_2.jsp" target="_blank" class="dropdown-item">Estadia</a></li>
                        <li><a href="http://localhost:8080/reportes/Listado_Reserva.jsp" target="_blank" class="dropdown-item">Reserva</a></li>
                        <li><a href="http://localhost:8080/reportes/Listado_Consumicion.jsp" target="_blank" class="dropdown-item">Consumicion</a></li>
                        <li><a href="http://localhost:8080/reportes/Listado_Traslado.jsp" target="_blank" class="dropdown-item">Traslado</a></li>
                        <li><a href="http://localhost:8080/reportes/Listado_Spa.jsp" target="_blank" class="dropdown-item">Spa/Sauna</a></li>
                        <li><a href="http://localhost:8080/reportes/Listado_Turismo.jsp" target="_blank" class="dropdown-item">Turismo</a></li>
                        <li><a href="http://localhost:8080/reportes/Listado_Lavanderia.jsp" target="_blank" class="dropdown-item">Lavanderia</a></li>
                        <li><a href="http://localhost:8080/reportes/Listado_tarifas.jsp" target="_blank" class="dropdown-item">Tarifas</a></li>
                        <li><a href="http://localhost:8080/reportes/Listado_Habitacion.jsp" target="_blank" class="dropdown-item">Habitaciones</a></li>
                        <li><a href="http://localhost:8084/reportes/Listado_Habitacion_1.jsp" target="_blank" class="dropdown-item">Habitaciones p/ categoria</a></li>
                        <li><a class="dropdown-item" style="font-weight:bold;text-align:center">--Compra--</a></li>
                        <li><a href="http://localhost:8080/reportes/Listado_Orden_De_Compras.jsp" target="_blank" class="dropdown-item">Orden de Compra</a></li>
                        <li><a href="http://localhost:8080/reportes/Listado_Facturas_Compra.jsp" target="_blank" class="dropdown-item">Factura Compra</a></li>
                        <li><a href="http://localhost:8080/reportes/Listado_Inventarios.jsp" target="_blank" class="dropdown-item">Inventarios</a></li>
                        <li><a href="http://localhost:8080/reportes/Listado_Cuentas_Pagar.jsp" target="_blank" class="dropdown-item">Cuentas a Pagar</a></li>
                        <li><a href="http://localhost:8080/reportes/Listado_libro_compras.jsp" target="_blank" class="dropdown-item">Libro de Compras</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a href="http://localhost:8080/reportes/ayuda.htm" target="_blank" class="nav-link">Ayuda</a></li>
                <li class="nav-item"><a href="{{ route('menu.out') }}" class="nav-link">Salir</a></li>
            </ul>
        </div>
    </div>
</nav>

<br>
<br>
<h4>Bienvenido/a {{$usuario}}</h4>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
@endsection