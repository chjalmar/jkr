function edit_practicante(id) {
	
  document.getElementById('p_ci_' + id).disabled = false;
  document.getElementById('p_nombre_' + id).disabled = false;
  document.getElementById('p_apellido_' + id).disabled = false;
  document.getElementById('p_dojo_' + id).disabled = false;
  document.getElementById('p_grado_actual_' + id).disabled = false;
}

function guardar_practicante(id) {
  
  var ci;
  var nombre;
  var apellido;
  var dojo_id;
  var grado_actual_id;
  
  if (!id) {
    ci = document.getElementById('p_ci').value;
    nombre = document.getElementById('p_nombre').value;
    apellido = document.getElementById('p_apellido').value;
    dojo_id = document.getElementById('p_dojo').value;
    grado_actual_id = document.getElementById('p_grado_actual').value;
  } else { 	
    ci = document.getElementById('p_ci_' + id).value;
    nombre = document.getElementById('p_nombre_' + id).value;
    apellido = document.getElementById('p_apellido_' + id).value;
    dojo_id = document.getElementById('p_dojo_' + id).value;
    grado_actual_id = document.getElementById('p_grado_actual_' + id).value;
  }
  $.ajax({
        url: "save_practicante.php",
        type:'POST',
        data:
        {
            id: id,
            ci: ci,
            nombre: nombre,
            apellido: apellido,
            dojo_id:  dojo_id,
            grado_actual_id: grado_actual_id
            
        },
        success: function(msg)
        {
          var new_id = msg;
          if (id) {
            alert("Registro actualizado correctamente.");
          } else {
          	alert("Registro creado correctamente.");
            jQuery('#p_ci').attr('id', 'p_ci_' + new_id);
            jQuery('#p_nombre').attr('id', 'p_nombre_' + new_id);
            jQuery('#p_apellido').attr('id', 'p_apellido_' + new_id);
            jQuery('#p_dojo').attr('id', 'p_dojo_' + new_id);
            jQuery('#p_grado_actual').attr('id', 'p_grado_actual_' + new_id);
          }
          id = new_id;
          document.getElementById('p_ci_' + id).disabled = true;
          document.getElementById('p_nombre_' + id).disabled = true;
          document.getElementById('p_apellido_' + id).disabled = true;
          document.getElementById('p_dojo_' + id).disabled = true;
          document.getElementById('p_grado_actual_' + id).disabled = true;
        }               
    });	
}

function add_practicante() {
	var new_row = "<tr>"
		  +"<td><input type=text id=p_ci></td>"
			+"<td><input type=text id=p_nombre></td>"
			+"<td><input type=text id=p_apellido></td>"
			+"<td><select id='p_dojo'>" + jQuery("#dojo_proto_select").html() + "</select></td>"
	    +"<td><select id='p_grado_actual'>" + jQuery("#grado_proto_select").html() + "</select></td>"
			+"<td><input type=submit value='Editar' style='visibility:hidden' onclick=edit_practicante()>"
			+"<input type=submit value='Guardar' onclick=guardar_practicante()>"
			+"<input type=submit value='Borrar' style='visibility:hidden' onclick=del_practicante()>"
		  +"</td></tr>";
	
  $('#practicantes tr:last').after(new_row);	
}

function del_practicante(id) {
  
  if (confirm('¿Está seguro de querer eliminar el registro?')) {
    $.ajax({
          url: "del_practicante.php",
          type:'POST',
          data:
          {
              id: id,
          },
          success: function(msg)
          {
            alert("Registro eliminado correctamente: " + msg);
            jQuery('#p_ci_' + id).closest('tr').fadeOut('fast');
          
          }
    });
  }
  
        
}