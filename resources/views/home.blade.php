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
        <h4 class="w-100 py-3 px-3 title z-depth-1 border-left-blue">Pedidos de la semana que viene</h4>
        <form action="{{ route('add_order') }}" method="POST" class="post-form">@csrf
        <div class="my-3 row">
                
            @foreach($next_week_forms as $form)
                <div class="col">
                    <div class="card px-3 py-3 border-left-blue z-depth-1">
                        <h5 class="my-0 mx-0">{{ date('D d-m-Y', strtotime($form['order']->date)) }}</h5>
                        <hr>
                        @foreach($form['menus'] as $menu)
                        <div class="custom-control custom-radio">
                            @if($menu->id == $form['order']->id_menu)
                                <input type="radio" class="custom-control-input" value="{{ $menu->id }}" id="{{ $menu->id }}_{{ $form['order']->date }}" name="{{ $form['order']->date }}" required checked>
                            @else
                                <input type="radio" class="custom-control-input" value="{{ $menu->id }}" id="{{ $menu->id }}_{{ $form['order']->date }}" name="{{ $form['order']->date }}" required>
                            @endif
                            <label class="custom-control-label" for="{{ $menu->id }}_{{ $form['order']->date }}">{{ $menu->name }}</label>
                        </div>
                        @endforeach()
                        <div class="form-group mt-1">
                            <label for="{{ $form['order']->date }}-obs">Observaciones:</label>
                            <textarea class="form-control rounded-0" name="{{ $form['order']->date }}-obs" id="{{ $form['order']->date }}-obs" rows="3">{{ $form['order']->comments }}</textarea>
                        </div>
                    </div>
                </div>   
            @endforeach()

        </div>
        <button type="submit" class="save btn btn-primary">Enviar</button>
    </form>
    </div>
    <hr>
</div>


<div class="container">
    <div class="row w-100">
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
        <h4 class="w-100 py-3 px-3 title z-depth-1 border-left-blue">Pedidos de esta semana</h4>
        <div class="my-3 row">
                
            @foreach($actual_week_forms as $form)
                <div class="col">
                    <div class="card px-3 py-3 border-left-blue z-depth-1 {{$form['order']->status}}">
                        <h5 class="my-0 mx-0">{{ date('D d-m-Y', strtotime($form['order']->date)) }}</h5>
                        <hr>
                        @foreach($form['menus'] as $menu)
                        <div class="custom-control custom-radio">
                            @if($menu->id == $form['order']->id_menu)
                                <input type="radio" class="custom-control-input" value="{{ $menu->id }}" id="{{ $menu->id }}_{{ $form['order']->date }}" name="{{ $form['order']->date }}" required checked>
                            @else
                                <input type="radio" class="custom-control-input" value="{{ $menu->id }}" id="{{ $menu->id }}_{{ $form['order']->date }}" name="{{ $form['order']->date }}" required>
                            @endif
                            <label class="custom-control-label" for="{{ $menu->id }}_{{ $form['order']->date }}">{{ $menu->name }}</label>
                        </div>
                        @endforeach()
                        <div class="form-group mt-1">
                            <label for="{{ $form['order']->date }}-obs">Observaciones:</label>
                            <textarea class="form-control rounded-0" name="{{ $form['order']->date }}-obs" id="{{ $form['order']->date }}-obs" rows="3">{{ $form['order']->comments }}</textarea>
                        </div>
                    </div>
                </div>   
            @endforeach()

        </div>
    </div>
    <hr>
</div>
@endsection
@section('scripts')
<script src="{{ asset('/js/index.js') }}" defer></script>
@endsection
