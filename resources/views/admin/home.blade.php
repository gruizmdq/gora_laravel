@extends('layouts.html')

@section('content')
<div class="container">
    <div class="row pb-3">
        <div class="col-md-12 mt-3">
            <nav class="mb-1 navbar navbar-expand-lg bl-blue">
            <a class="navbar-brand">Ventas </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent-333"
                aria-controls="navbarSupportedContent-333" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarSupportedContent-333">
                <ul class="navbar-nav mr-auto">
                
                </ul>
                <ul class="navbar-nav ml-auto nav-flex-icons">
                    <li class="nav-item px-1 {{ request()->segment(count(request()->segments())) == 0 ? 'blue' : ''  }}">
                        <a class="nav-link {{ request()->segment(count(request()->segments())) == 0 ? 'text-white' : ''  }}" href="{{ route('admin.home') }}">Hoy
                        <span class="sr-only">(current)</span>
                        </a>
                    </li>
                    @for ($i = 1; $i < 5; $i++)
                    <li class="nav-item {{ request()->segment(count(request()->segments())) == $i ? 'blue' : ''  }}">
                        <a class="nav-link {{ request()->segment(count(request()->segments())) == $i ? 'text-white' : ''  }}" href="{{ route('admin.home', $i) }}">{{ date('d/m',time()-$i*86400) }}</a>
                    </li>
                        
                    @endfor
                </ul>
            </div>
            </nav>
            <!--/.Navbar -->
        </div>
    </div>
    @if (count($orders))
    <div class="row">
        <div class="col-12">
            <h5 class="text-center">
                {{ count($orders) }} ventas en total - <strong>${{number_format($profit, 2)}}</strong>
            </h5>        
        </div>
    </div>
    @endif
    <hr>

    <div class="row pb-3">
        <div class="col-12">
        <table id="table" class="z-depth-1 white py-2 px-1 table table-sm table-striped  table-bordered">
                <thead>
                    <tr>
                        <th scope="col">Dirección</th>
                        <th scope="col">Teléfono</th>
                        <th scope="col">Zapatilla</th>
                        <th scope="col">Zona</th>
                        <th scope="col">Precio</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Vendedor</th>
                        <th scope="col"></th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($orders as $order)
                    <tr data-id="{{ $order->id }}">
                        <td>{{ $order->street }} {{ $order->number }}</td>
                        <td>{{ $order->phone }}</td>
                        <td>{{ $order->product }}</td>
                        <td><zone-selector route="{{ route('delivery.set_zone_order') }}" :zones="{{ $zones }}" :id="{{  $order->id }}" :zoneselected="{{  $order->zone }}"></zone-selector></td>
                        <td>${{ $order->price }}</td>
                        <td class="text-center"><status-selector route="{{ route('delivery.order.set_status') }}" :options="{{ $status }}" :id="{{  $order->id }}" :optionselected="{{  $order->status }}"></status-selector></td>
                        <td> {{ $order->user_name }}</td>
                        <td> 
                            @if ($order->status != 4)
                                <a class="badge badge-pill badge-danger delete-label" data-id="{{ $order->id }}" data-toggle="modal" data-target="#deleteModal">Borrar</a>
                            @endif
                        </td>
                    </tr>    
                    @endforeach()
                    
                </tbody>
            </table>
        </div>
    </div>
</div>


<div id="deleteModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body text-center">
            <h5 class="mb-3">¿Seguro querés borrar la venta bobo?</h5>
            <button type="submit" class="btn btn-sm btn-primary delete-order">Borrar</button>
            <button type="button" class="btn btn-sm btn-outline-primary" data-dismiss="modal">Volver</button>
      </div>
    </div>
  </div>
</div>


@endsection
@section('scripts')
<script src="{{ asset('js/datatables.min.js') }}"></script>
<script type="text/javascript">    
    var delete_id = null
    $(document).ready(function() {
        $('#table').DataTable();
        $('.dataTables_length').addClass('bs-select');

        //DELETE
        $(".delete-label").click(function() {
            delete_id = parseInt($(this).attr('data-id'))
        })

        $(".delete-order").click(function(){
           
            var values = { id: delete_id }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type:'POST',
                url:'{{ route('admin.delete_order') }}',
                data: JSON.stringify(values),
                contentType: 'json', 
                processData: true,
                success:function(data){
                    $('#deleteModal').modal('hide')
                    if (data.status != "error") {
                        $("tr[data-id='"+delete_id+"']").fadeOut( "fast", function() {
                            $(this).remove();
                        });
                    }
                    else 
                        alert(data.msg)
                }, 
                error: function(data) {
                    alert('Hubo un error.')
                }
            });
        });
    })
    

</script>
@endsection