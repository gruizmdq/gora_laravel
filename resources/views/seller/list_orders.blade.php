@extends('seller.html')

@section('content')

<div class="container-fluid" style="background: #fafafa">
    <div class="row pb-3">
        <div class="col-lg-12">
            <div class="">
                <h4 class="z-depth-1 white p-3 bl-blue">Ventas realizadas ({{ $total_orders }} en total)</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <table id="table" class="z-depth-1 white py-2 px-1 table table-sm table-striped  table-bordered">
                <thead>
                    <tr>
                        <th scope="col">Dirección</th>
                        <th scope="col">Teléfono</th>
                        <th scope="col">Zapatilla</th>
                        <th scope="col">Precio</th>
                        <th scope="col">Zona</th>
                        <th scope="col">Estado</th>
                        <th scope="col"></th>
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
        </div>
    </div>
</div>
            
@endsection
@section('scripts')
<script src="{{ asset('js/datatables.min.js') }}"></script>
<script type="text/javascript">    

    $(document).ready(function() {

        $('#table').DataTable();
        $('.dataTables_length').addClass('bs-select');
    })
    

</script>
@endsection