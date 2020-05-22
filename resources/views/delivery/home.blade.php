@extends('delivery.html')

@section('content')
@if ( session('message') )
    <div class="alert alert-success">{{ session('message') }}</div>
@endif
<div class="container">
    <form class="needs-validation" method="POST" action="{{ route('delivery.get_route') }}">
    @csrf
        <div class="row">
            <div class="col-md-4">
                <h4>Direcciones <span>Agregar Calle</span></h4>
                <div class="form-group">
                    <autocomplete></autocomplete>
                </div>
                <div class="form-group">
				    <input required type="number" name="number" class="w-100 form-control validate" step="1" min="0" placeholder="Número">
                </div>
                <div class="form-group">
				    <input type="text" name="phone" class="w-100 form-control validate" placeholder="Teléfono">
                </div>
                <div class="form-group">
				    <input type="number" name="price" class="w-100 form-control validate" min="0" placeholder="Precio">
			    </div>

            </div>
        </div>

        <button id="btn-submit" type="submit" class="save btn btn-primary">Agregar</button>
    </form>
</div>
@endsection
@section('scripts')
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
    

</script>
@endsection