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
                <h3 class="py-1 px-2 w-100">Agregar Pedido</h3>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-8 card">
                <h4 class="py-1 px-2 w-100">Agregar pedido común</h4>
                <form action="{{ route('admin.add_order') }}" method="POST" class="post-form">@csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Nombre</label>
                                <input type="text" class="form-control" name="name" id="name">
                            </div>
                            <div class="form-group">
                                <select id="id_delivery" name="id_delivery" class="custom-select">
                                    <option selected></option>

                                    @foreach($deliverys as $delivery)
                                        <option value="{{ $delivery->id }}">{{ $delivery->name }}</option>
                                    @endforeach()
                                </select>   
                            </div>
                            <div class="form-group">
                                <label for="address">Dirección</label>
                                <input type="text" class="form-control" name="address" id="address">
                            </div>
                            <div class="form-group">
                                <label for="shift">Turno</label>
                                <input type="number" class="form-control" name="shift" id="shift">
                            </div>
                            <div class="form-group">
                                <label for="date">Fecha</label>
                                <input type="date" class="form-control" name="date" id="date" min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="form-group">
                                <label for="comments">Comentarios</label>
                                <textarea class="form-control" name="comments" id="comments"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Agregar</button>
                        </div>
                        <div class="col-md-6"> 
                            <table id="table-add" class="table table-sm  table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">Cantidad</th>
                                        <th scope="col">Tipo</th>
                                        <th scope="col">-</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="col">
                                            <input type="number" name="qty_balance" step="1" min="0" value="0">
                                        </th>
                                        <th scope="col">Balance</th>
                                        <th scope="col">-</th>
                                    </tr>
                                    <tr>
                                        <th scope="col">
                                            <input type="number" name="qty_salad" step="1" min="0" value="0">
                                        </th>
                                        <th scope="col">Salad</th>
                                        <th scope="col">-</th>
                                    </tr>
                                    <tr>
                                        <th scope="col">
                                            <input type="number" name="qty_temping" step="1" min="0" value="0">
                                        </th>
                                        <th scope="col">Temping</th>
                                        <th scope="col">-</th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-md-4 card">
                <h4 class="py-1 px-2 w-100">Agregar pedido de Empresa</h4>
                <div class="row">
                    <div class="col-md-12">
                    <form action="{{ route('admin.add_order_company') }}" method="POST" class="post-form">@csrf
                        <div class="form-group">
                            <label for="id_empresa">Empresa</label>
                            <select id="id_empresa" class="custom-select">
                                <option selected></option>

                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                                @endforeach()
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="id_user">Empleado</label>
                            <select id="id_user" class="custom-select">
                                
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="id_delivery">Delivery</label>
                            <select id="id_delivery" class="custom-select">
                                <option selected></option>

                                @foreach($deliverys as $delivery)
                                    <option value="{{ $delivery->id }}">{{ $delivery->name }}</option>
                                @endforeach()
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="date">Fecha</label>
                            <input type="date" class="form-control" id="date" min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="form-group">
                            <label for="id_menu">Menú</label>
                            <input type="text" class="form-control" id="id_menu">
                        </div>
                        <div class="form-group">
                            <label for="comments">Comentarios</label>
                            <textarea class="form-control" id="date"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Agregar</button>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
@section('scripts')
<script src="{{ asset('/js/admin/add_order.js') }}" defer></script>
@endsection

