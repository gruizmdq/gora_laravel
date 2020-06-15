@extends('seller.html')

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
    <div class="row mt-3 p-4">
        <div class="col-md-12">
            <h4>Últimos 20 pedidos <a href="{{ route('seller.orders') }}" class="ml-3 save btn btn-primary btn-sm">Ver Todos</a>
            </h4>
            @if(count($orders))
                <div class="mt-3">
                    <table id="tabla-done" class="py-2 px-1 table table-sm table-striped table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">Dirección</th>
                                <th scope="col">Teléfono</th>
                                <th scope="col">Zapattilla</th>
                                <th scope="col">Precio</th>
                                <th scope="col">Zona</th>
                                <th scope="col">Status</th>
                                <th scope="col">-</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($orders as $order)
                                <tr data-id="done-{{ $order->id }}">
                                    <td>{{ $order->street }} {{ $order->number }}</td>
                                    <td>{{ $order->phone }}</td>
                                    <td>{{ $order->product }}</td>
                                    <td>${{ $order->price }}</td>
                                    <td>{{ $order->zone_name }}</td>
                                    <td class="{{ $order->status_color }}"> {{ $order->status_name }}</td>
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