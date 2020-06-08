@extends('layouts.html')

@section('content')
<div class="container-fluid" style="background: #fafafa">
    <div class="row pb-3">
        <div class="col-lg-12">
            <div class="z-depth-1 white p-4">
                <h4 class="">Rutas</h4>
                <div class="mt-3 col-md-12">
                    <ul class="boton nav">
                        @foreach($zones as $zone)
                        <li class="nav-item {{ request()->segment(count(request()->segments())) == $zone->id ? 'active' : ''  }}">
                            <a href="{{ route('delivery.routes', $zone->id) }}" class="btn waves-effect btn-sm  {{ request()->segment(count(request()->segments())) == $zone->id ? 'btn-primary' : 'btn-outline-primary'  }}">{{ $zone->name }}</a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            @if(count($orders))
            <route-component :orders="{{ $orders }}"></route-component>
            @else 
            <h4>No hay ninguna ruta armada para la zona</h4>
            @endif
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">

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