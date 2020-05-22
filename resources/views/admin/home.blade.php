@extends('admin.html')

@if ( session('message') )
    <div class="alert alert-success">{{ session('message') }}</div>
@endif

@section('content')
<div class="container">
    <section>
        <div class="row w-100">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

            <div class="col-12">
                <h3 class="py-1 px-2 w-100">Pedidos hoy</h3>
            </div>

            <div class="col-md-3">
                <div class="card">
                    <h5>Empresa 1</h5>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card">
                    <h5>Empresa 2</h5>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <h5>Individuales</h5>
                </div>
            </div>
        </div>
    </section>

    <section class="pt-3">
        <div class="mt-3 row">
            <div class="col-12">
                <h3 class="py-1 px-2 w-100">Estado de los pedidos</h3>
            </div>
            <div class="mt-3 col-md-12">
                <ul class="boton nav">
                    <li class="nav-item active">
                        <button type="button" class="btn btn-outline-primary waves-effect btn-sm">Todos</button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="btn btn-outline-primary waves-effect btn-sm">Pendientes</button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="btn btn-outline-primary waves-effect btn-sm">Cancelados</button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="btn btn-outline-primary waves-effect btn-sm">Entregados</button>
                    </li>
                </ul>
            </div>
            <div class="mt-2 col-md-12">
                <ul class="boton nav">
                    <li class="nav-item active">
                        <button type="button" class="btn btn-outline-primary waves-effect btn-sm">Todos</button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="btn btn-outline-primary waves-effect btn-sm">Temping</button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="btn btn-outline-primary waves-effect btn-sm">Balance</button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="btn btn-outline-primary waves-effect btn-sm">Salad</button>
                    </li>
                </ul>
            </div>

            <div class="mt-3 col-md-12">
                <table id="tabla" class="py-2 px-1 table table-sm table-striped  table-bordered">
                    <thead>
                        <tr>
                        <th scope="col">Nombre</th>
                        <th scope="col">Dirección</th>
                        <th scope="col">Cantidad</th>
                        <th scope="col">Turno</th>
                        <th scope="col">Delivery</th>
                        <th scope="col">Estado</th>
                        <th scope="col">-</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <th scope="row"></th>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-center"><a href=""><span class="badge badge-warning order-edit-label py-1 px-2">Editar</span></a></td>
                        </tr>
                    </tbody>

                </table>
            </div>
        </div>
    </section>
    <hr>
</div>
@endsection

