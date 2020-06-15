@extends('layouts.html')

@section('content')
<div class="container-fluid">
    <div class="row pb-3">
        <div class="offset-md-3 col-md-6 mt-3">
            <form method="POST" action="{{ route('delivery.save_order') }}">
            @csrf

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="name">Nombre</label>
                        <input required type="text" name="name" class="w-100 form-control validate">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="phone">Teléfono</label>
                        <input required type="text" name="phone" class="w-100 form-control validate" >
                    </div>
                    <div class="form-group col-md-4">
                        <label for="price">Precio</label>
                        <input type="number" name="price" class="w-100 form-control validate" min="0">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="street">Calle</label>
                        <autocomplete></autocomplete>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="number">Número</label>
                        <input required type="number" name="number" class="w-100 form-control validate" step="1" min="0">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group col-sm-6 col-md-3">
                        <label for="shoe-brand">Marca</label>
                        <input required type="text" name="shoe-brand" class="w-100 form-control validate" >
                    </div>
                    <div class="form-group col-sm-6 col-md-3">
                        <label for="shoe-code">Artículo</label>
                        <input required type="text" name="shoe-code" class="w-100 form-control validate" step="1" min="0">
                    </div>
                    <div class="form-group col-sm-6 col-md-3">
                        <label for="shoe-color">Color</label>
                        <input required type="text" name="shoe-color" class="w-100 form-control validate" >
                    </div>
                    <div class="form-group col-sm-6 col-md-3">
                        <label for="shoe-size">Número</label>
                        <input required type="text" name="shoe-size" class="w-100 form-control validate" step="1" min="0">
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Descripción</label>
                    <textarea type="text" name="description" class="w-100 form-control validate"></textarea>
                </div>
                
                <div class="w-100 text-center">
                    <button type="submit" class="btn btn-primary btn-md text-center m-auto">Agregar</button>
                </div>
            </form>

        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-lg-12 mt-3 p-4">
            <h4>Pedidos sin entregar</h4>

            @if(count($ship_orders))
            <div class="mt-3 table-responsive">
                <table id="tabla" class="py-2 px-1 table table-sm table-striped  table-bordered">
                    <thead>
                        <tr>
                        <th scope="col">Dirección</th>
                        <th scope="col">Número</th>
                        <th scope="col">Teléfono</th>
                        <th scope="col">Zona</th>
                        <th scope="col">Precio</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Vendedor</th>
                        <th scope="col"></th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($ship_orders as $order)
                            <tr data-id="{{ $order->id }}">
                                <td>{{ $order->street }}</td>
                                <td><input class="form-control w-100 order-input" name="number" type="number" value="{{ $order->number }}" min="0" required step="1"></td>
                                <td><input class="form-control w-100" type="text" name="phone" value="{{ $order->phone }}"></td>
                                <td><zone-selector route="{{ route('delivery.set_zone_order') }}" v-bind:zones="{{ $zones }}" v-bind:id="{{ $order->id }}" zoneselected="{{ $order->zone }}"></zone-selector>
                                <td><input class="form-control w-100" type="number" name="price" value="{{ $order->price }}"></td>
                                <td class="text-center"><status-selector route="{{ route('delivery.order.set_status') }}" v-bind:options="{{ $status }}" v-bind:id="{{ $order->id }}" optionselected="{{ $order->status }}"></status-selector></td>
                                <td class="text-center">{{ $order->user_name }}</td>
                                <td class="text-center"><a class="maps-link" target="_blank" href="https://www.google.com/maps/dir/?api=1&destination={{ $order->lat }},{{ $order->lng }}&travelmode=driving"><span class="badge badge-warning order-edit-label py-1 px-2">Maps</span></a></td>
                            </tr>
                        @endforeach()
                       
                    </tbody>

                </table>
                <a href="{{ route('delivery.list_orders') }}" class="save btn btn-primary">Ver Todos</a>
            </div>
            @endif
        </div>
    </div>
    <hr>
    <div class="row mt-3 p-4">
        <div class="col-md-12">
            <h4>Últimos 20 pedidos entregados</h4>
            @if(count($ship_orders_done))
                <div class="mt-3">
                    <table id="tabla-done" class="py-2 px-1 table table-sm table-striped  table-bordered">
                        <thead>
                            <tr>
                            <th scope="col">Dirección</th>
                            <th scope="col">Número</th>
                            <th scope="col">Teléfono</th>
                            <th scope="col">Barrio</th>
                            <th scope="col">Precio</th>
                            <th scope="col">-</th>
                            <th scope="col">-</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($ship_orders_done as $order)
                                <tr data-id="done-{{ $order->id }}">
                                    <td>{{ $order->street }}</td>
                                    <td>{{ $order->number }}</td>
                                    <td>{{ $order->phone }}</td>
                                    <td class="neighborhood">{{ $order->neighborhood }}</td>
                                    <td>{{ $order->price }}</td>
                                    <td class="text-center"><a href=""><span class="badge badge-warning order-edit-label py-1 px-2">Entregado</span></a></td>
                                    <td class="text-center"><a class="maps-link" target="_blank" href="https://www.google.com/maps/dir/?api=1&destination={{ $order->lat }},{{ $order->lng }}&travelmode=driving"><span class="badge badge-warning order-edit-label py-1 px-2">Maps</span></a></td>
                                </tr>
                            @endforeach()
                        </tbody>

                    </table>
                    
                @endif
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
        $(".order-input").blur(function() {
            var id = $(this).closest('tr').attr('data-id')
            console.log(this)
            var number = $(this).closest('tr').find('input[name="number"]').val()
            var price = $(this).closest('tr').find('[name="price"]').val()
            var phone = $(this).closest('tr').find('[name="phone"]').val()
           
            var values = {id: id, number: number, price:price, phone:phone}
            console.log(values)
          

            $.ajax({
                type:'POST',
                url:'{{ route('delivery.update_order') }}',
                data: JSON.stringify(values),
                contentType: 'json', 
                processData: true,
                success: onSuccessUpdate
            });
        })

    })


    function deleteOrder(id) {
        $.ajax({
            type:'POST',
            url:'{{ route('delivery.delete_order') }}',
            data: JSON.stringify({id: id}),
            contentType: 'json', 
            processData: true,
            success: onSuccessDelete
        })
    }

    function setDone(id) {
        $.ajax({
            type:'POST',
            url:'{{ route('delivery.order.complete_order') }}',
            data: JSON.stringify({id: id}),
            contentType: 'json', 
            processData: true,
            success: onSuccessSetDone
        })
    }

    function onSuccessSetDone(data) {
        location.reload();
    }

    function onSuccessDelete(data) {
         
    }

    function onSuccessUpdate(data) {
        $('tr[data-id='+data.id+']').find('.neighborhood').text(data.neighborhood)        
        $('tr[data-id='+data.id+']').find('.maps-link').attr('href', 'https://www.google.com/maps/dir/?api=1&destination='+data.lat+','+data.lng+'&travelmode=driving')
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