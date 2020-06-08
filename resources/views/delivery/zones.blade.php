@extends('layouts.html')

@section('content')
@if ( session('message') )
    <div class="alert alert-success">{{ session('message') }}</div>
@endif
<div class="container">
    <div class="row pb-3">
        <div class="col-lg-4 mt-3">
            <form class="border p-3 needs-validation" method="POST" action="{{ route('delivery.add_zone') }}">
            @csrf
            
                <h4>Agregar nueva zona</h4>
                <div class="form-group">
                    <input required type="text" name="name" class="w-100 form-control validate" placeholder="Nombre">
                </div>
                
                <button id="btn-submit" type="submit" class="save btn btn-primary">Agregar</button>
            </form>
        </div>
    
    @foreach($neighborhoods as $key => $value)
        <div class="col-lg-4 mt-3">
            <h4>{{ $key }}</h4>
            <div class="mt-3">
                <table id="tabla" class="py-2 px-1 table table-sm table-striped  table-bordered">
                    <thead>
                        <tr>
                        <th scope="col">Barrio</th>
                        <th scope="col">Código</th>
                        <th scope="col">Zona</th>
                        <th scope="col">-</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($value as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->code }}</td>
                            <td class="text-center">
                                @if(count($zones))
                                <zone-selector route="{{ route('delivery.set_neighborhood') }}" v-bind:zones="{{ $zones }}" v-bind:id="{{ $item->id }}"></zone-selector>
                                @endif
                            </td>
                            <td class="text-center"><a class="maps-link" target="_blank" href="https://www.google.com/maps/search/?api=1&query={{ $item->lat }},{{ $item->lng }}&zoom=14"><span class="badge badge-warning order-edit-label py-1 px-2">Maps</span></a></td>
                        </tr>
                        @endforeach()
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach()
    </div>

    <div class="row">
        <div class="col-md-12">
            <h4>Barrios sin asignar</h4>
            @if(count($neighborhoods_unasigned))
            <div class="mt-3">
                <table id="tabla" class="py-2 px-1 table table-sm table-striped  table-bordered">
                    <thead>
                        <tr>
                        <th scope="col">Barrio</th>
                        <th scope="col">Código</th>
                        <th scope="col">Zona</th>
                        <th scope="col">-</th>
                        <th scope="col">-</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($neighborhoods_unasigned as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->code }}</td>
                                <td class="text-center">
                                    @if(count($zones))
                                        <zone-selector route="{{ route('delivery.set_neighborhood') }}" v-bind:zones="{{ $zones }}" v-bind:id="{{ $item->id }}"></zone-selector>
                                    @endif
                                </td>
                                <td class="text-center"><a class="maps-link" target="_blank" href="https://www.google.com/maps/search/?api=1&query={{ $item->lat }},{{ $item->lng }}&zoom=14"><span class="badge badge-warning order-edit-label py-1 px-2">Maps</span></a></td>
                            </tr>
                        @endforeach()
                    </tbody>

                </table>
            </div>
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


    })

    function onSuccessUpdate(data) {
        console.log(data)
    }
</script>
@endsection