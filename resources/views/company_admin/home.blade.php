@extends('layouts.app')

@if ( session('message') )
    <div class="alert alert-success">{{ session('message') }}</div>
@endif
<a href="{{ route('company.add_user') }}">Add User</a>
<a href="">Add User</a>
<a href="">Add User</a>
@section('content')
<div class="container">
    <div class="row w-100">
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
        <h4 class="w-100 py-3 px-3 title z-depth-1 border-left-blue">Pedidos de la semana que viene</h4>
        
        @foreach($orders as $date => $orders_by_date)
            <div class="col">
                <p>{{ $date }}</p>
                @if(!empty($orders_by_date['done']))
                    @foreach($orders_by_date['done'] as $order)
                        <div>
                            <a href="{{ route('company.show_users', $order->id_user) }}">{{ $order->user }}</a>
                        </div>
                    @endforeach()
                @endif
                <hr>
                @if(!empty($orders_by_date['not_done']))
                    @foreach($orders_by_date['not_done'] as $order)
                        <div>
                        <a href="{{ route('company.show_users',  $order->id_user) }}">{{ $order->user }}</a>
                        </div>
                    @endforeach()
                @endif
                <hr>
                <p> @if (!empty($orders_by_date['done']))
                        {{ count($orders_by_date['done']) }}
                    @else 
                        0
                    @endif
                    -
                    @if (!empty($orders_by_date['not_done']))
                        {{ count($orders_by_date['not_done']) }}
                    @else 
                        0
                    @endif
                </p>
            </div>   
        @endforeach()
    </div>
    <hr>
</div>
@endsection

