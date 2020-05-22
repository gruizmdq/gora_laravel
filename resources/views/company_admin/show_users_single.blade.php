@extends('layouts.app')

@if ( session('message') )
    <div class="alert alert-success">{{ session('message') }}</div>
@endif

@section('content')

<div class="container">
    <div class="row w-100">
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
        <h4 class="w-100 py-3 px-3 title z-depth-1 border-left-blue">Usuarios</h4>
        <div class="my-3 row">
                
                <div class="col">
                    <div class="card px-3 py-3 border-left-blue z-depth-1">
                    {{ $user->name }}
                    </div>
                </div>   

        </div>
    </div>
    <hr>
</div>
@endsection

