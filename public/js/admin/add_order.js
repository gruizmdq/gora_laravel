function getEmpleados(id) {
    var token = $('meta[name="csrf_token"]').attr('content')
   
    $.ajax({
       type:'GET',
       url:'/getEmpleados/'+id,
       data: {_token: token},
       success: setEmpleados
    });
 }

function setEmpleados(data){
   $('#id_user option').remove()
   data['users'].forEach(element => {
      $('#id_user').append(new Option(element['name'], element['id']))
   });
}

 $('#id_empresa').change( function() {
    $(this).find(":selected").each(function () {
         getEmpleados($(this).val());
     });
  });