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
        <form action="{{ route('company.add_user') }}" method="POST" class="post-form">@csrf
            <p class="h4 mb-4">Agregar Usuario</p>
            <input type="text" name='name' id="name" class="form-control mb-4" placeholder="Nombre">
            <input type="email" name='email' id="email" class="form-control mb-4" placeholder="E-mail">
            <input type="password" name='password' id="password" class="form-control mb-4" placeholder="Password">

            <button type="submit" class="save btn btn-primary">Enviar</button>
        </form>
    </div>
    <hr>
</div>
@endsection

