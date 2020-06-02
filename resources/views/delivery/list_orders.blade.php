@extends('delivery.html')

@section('content')
@if ( session('message') )
    <div class="alert alert-success">{{ session('message') }}</div>
@endif
<div class="container">
    <div class="row pb-3">
        <div class="col-lg-12 mt-3 p-4">
            <h4>Pedidos sin entregar</h4>
            <div class="mt-3 col-md-12">
                <ul class="boton nav">
                    <li class="nav-item active">
                        <a href="{{ route('delivery.list_orders') }}" class="btn btn-outline-primary waves-effect btn-sm {{ $id_zone < 1 ? 'active' : ''  }}">Todos</a>
                    </li>
                    @foreach($zones as $zone)
                    <li class="nav-item">
                        <a href="{{ route('delivery.list_orders', $zone->id) }}" class="btn btn-outline-primary waves-effect btn-sm  {{ $id_zone == $zone->id ? 'active' : ''  }}">{{ $zone->name }}</a>
                    </li>
                    @endforeach
                </ul>
            </div>
            @if(count($ship_orders))
            @foreach($ship_orders as $key => $value)
            <div class="mt-4">
                <ul class="boton nav my-3">
                    <li class="nav-item">
                        <h4>{{ $key }}</h4>
                    </li>
                    <li class="ml-3 nav-item">
                        <button type="button" class="btn btn-outline-primary waves-effect btn-sm " data-toggle="modal" data-target="#createRouteModal">
                        Crear Ruta
                        </button>
                    </li>
                </ul>
                <table id="tabla" class="py-2 px-1 table table-sm table-striped  table-bordered">
                    <thead>
                        <tr>
                        <th scope="col">Dirección</th>
                        <th scope="col">Teléfono</th>
                        <th scope="col">Zona</th>
                        <th scope="col">Precio</th>
                        <th scope="col">-</th>
                        <th scope="col">-</th>
                        <th scope="col">-</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($value as $item)    
                            <tr data-id="{{ $item->id }}">
                                <td>{{ $item->street }} {{ $item->number }}</td>
                                <td>{{ $item->phone }}</td>
                                <td><zone-selector route="{{ route('delivery.set_zone_order') }}" v-bind:zones="{{ $zones }}" v-bind:id="{{ $item->id }}" zoneselected="{{ $item->zone }}"></zone-selector>
                                </td>
                                <td>{{ $item->price }}</td>
                                <td class="text-center"><a onclick="deleteOrder({{ $item->id }})"><span class="badge badge-warning order-edit-label py-1 px-2">Borrar</span></a></td>
                                <td class="text-center"><a onclick="setDone({{ $item->id }})"><span class="badge badge-warning order-edit-label py-1 px-2">Entregado</span></a></td>
                                <td class="text-center"><a class="maps-link" target="_blank" href="https://www.google.com/maps/dir/?api=1&destination={{ $item->lat }},{{ $item->lng }}&travelmode=driving"><span class="badge badge-warning order-edit-label py-1 px-2">Maps</span></a></td>
                            </tr>
                        @endforeach()
                       
                    </tbody>

                </table>
            </div>
            @endforeach()
            @endif
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
        <form class="create-route" action="#">
            <div class="start">
                <h4>Agregar Dirección de Origen</h4>
                <div class="form-group">
                    <autocomplete></autocomplete>
                </div>
                <div class="form-group">
                    <input required type="number" name="number" class="w-100 form-control validate" step="1" min="0" placeholder="Número">
                </div>
            </div>
            <div class="end">
                <h4>Agregar Dirección de Terminación</h4>
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

        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
        });

        //Update.
        $("form.create-route").submit(function(e) {
           
            /* stop form from submitting normally */
            event.preventDefault();

            var start = {code: $('.start input[name="code"').val(), name: $('.start input[name="street"').val(), number: $('.start input[name="number"').val()}
            var end = {code: $('.end input[name="code"').val(), name: $('.end input[name="street"').val(), number: $('.end input[name="number"').val()}
            var values = {id: '{{ $id_zone }}', start: start, end: end}
          

            $.ajax({
                type:'POST',
                url:'{{ route('delivery.create_route') }}',
                data: JSON.stringify(values),
                contentType: 'json', 
                processData: true,
                success: onSuccessCreateOrder,
                error: onErrorCreateOrder
            });
        })

    })


    

    function onSuccessCreateOrder(data) {
        //location.reload();
        console.log('jejje')
    }

</script>
<!--
<script type="text/javascript">    

    $(document).ready(function() {

        $("aa").submit(function(e){
            e.preventDefault();
            
            
            var $streets = $('form :input.address');
            var $streets_number = $('form :input.address-number');

            var values = []

            $.each($streets, function(index, value) {
                var code = $(value).attr('data-code')
                var name = $(value).val()
                var number = $($streets_number[index]).val()
                values.push({code:code, name:name, number:number})
            })

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type:'POST',
                url:'{{ route('delivery.get_route') }}',
                data: JSON.stringify(values),
                contentType: 'json', 
                processData: true,
                success:function(data){
                    alert(data.success);
            }
            });
        });
    })
    

</script>-->
@endsection