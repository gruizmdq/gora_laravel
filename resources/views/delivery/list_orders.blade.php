@extends('layouts.html')

@section('content')

<div class="container-fluid" style="background: #fafafa">
    <div class="row pb-3">
        <div class="col-lg-12">
            <div class="z-depth-1 white p-4">
                <h4 class="">Pedidos sin entregar</h4>
                <div class="mt-3 col-md-12">
                    <ul class="boton nav">
                        <li class="nav-item active">
                            <a href="{{ route('delivery.list_orders') }}" class="btn btn-outline-primary waves-effect btn-sm {{ $id_zone < 1 ? 'active' : ''  }}">Todos</a>
                        </li>
                        @foreach($zones as $zone)
                        <li class="nav-item {{ request()->segment(count(request()->segments())) == $zone->id ? 'active' : ''  }}">
                            <a href="{{ route('delivery.list_orders', $zone->id) }}" class="btn waves-effect btn-sm  {{ request()->segment(count(request()->segments())) == $zone->id ? 'btn-primary' : 'btn-outline-primary'  }}">{{ $zone->name }}</a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @if(count($ship_orders))
            @foreach($ship_orders as $key => $value)
            <div class="mt-4">
                <ul class="boton nav my-3">
                    <li class="ml-3 nav-item">
                        
                    </li>
                </ul>
                <h5 class="py-3 px-3 mb-3 bl-blue z-depth-1 white">Ya asignados en una ruta 
                    <a type="button" class="align-middle float-right btn btn-primary waves-effect btn-sm m-0 ml-2" href="{{ route('delivery.routes', $zone->id) }}">
                        Ver Ruta
                    </a>
                    <button type="button" class="align-middle float-right btn btn-outline-primary waves-effect btn-sm m-0 " data-toggle="modal" data-target="#createRouteModal">
                        Crear Ruta
                    </button>
                </h5>
                <table id="tabla" class="z-depth-1 white py-2 px-1 table table-sm table-striped  table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">Dirección</th>
                            <th scope="col">Número</th>
                            <th scope="col">Teléfono</th>
                            <th scope="col">Zona</th>
                            <th scope="col">Precio</th>
                            <th scope="col">Estado</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($value as $order)
                          <tr is="order-row" :order="{{ $order }}" :zones="{{ $zones }}" route="{{ route('delivery.order.update_address') }}" v-bind:status="{{ $status }}"></tr> 
                        @endforeach()
                       
                    </tbody>

                </table>
            </div>
            @endforeach()
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="mt-4">
                <h5 class="py-3 px-3 mb-3 bl-blue z-depth-1 white">No asignados en una ruta                
                    <button type="button" class="align-middle float-right btn btn-outline-primary waves-effect btn-sm m-0 " data-toggle="modal" data-target="#createRouteModal">
                        Crear Ruta
                    </button>
                </h5>
                
                @if(count($orders_unassigned))
                <table id="tabla" class="z-depth-1 white py-2 px-1 table table-sm table-striped  table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">Dirección</th>
                            <th scope="col">Número</th>
                            <th scope="col">Teléfono</th>
                            <th scope="col">Zona</th>
                            <th scope="col">Precio</th>
                            <th scope="col">Estado</th>
                            <th scope="col"></th>
                            <th scope="col"></th>
                            <th scope="col"></th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($orders_unassigned as $order)
                          <tr is="order-row" :order="{{ $order }}" :zones="{{ $zones }}" route="{{ route('delivery.order.update_address') }}" v-bind:status="{{ $status }}"></tr> 
                        @endforeach()
                       
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>
</div>

<div id="createRouteModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Calcular Ruta para: {{ key($ship_orders) }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" class="create-route">
            <div class="start">
                <h5>Agregar dirección de origen</h5>
                <div class="form-group">
                    <autocomplete></autocomplete>
                </div>
                <div class="form-group">
                    <input required type="number" name="number" class="w-100 form-control validate" step="1" min="0" placeholder="Número">
                </div>
            </div>
            <div class="end">
                <h5>Agregar dirección de terminación</h5>
                <div class="form-group">
                    <autocomplete></autocomplete>
                </div>
                <div class="form-group">
                    <input required type="number" name="number" class="w-100 form-control validate" step="1" min="0" placeholder="Número">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Calcular Ruta</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Volver</button>
        </form>
      </div>
      <div class="modal-footer">
        
      </div>
    </div>
  </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">    

    $(document).ready(function() {

        $(".create-route").submit(function(e){
            e.preventDefault();
            
            
            var $start = { name: $('.start input[name="street"]').val(), code: $('.start input[name="street"]').attr('data-code'), number: $('.start input[name="number"]').val() };
            var $end = { name: $('.start input[name="street"]').val(), code: $('.end input[name="street"]').attr('data-code'), number: $('.start input[name="number"]').val() };
           
            var values = { id_zone: '{{ request()->segment(count(request()->segments())) }}', start: $start, end: $end }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type:'POST',
                url:'{{ route('delivery.create_route') }}',
                data: JSON.stringify(values),
                contentType: 'json', 
                processData: true,
                success:function(data){
                    alert(data.msg);
            }
            });
        });
    })
    

</script>
@endsection