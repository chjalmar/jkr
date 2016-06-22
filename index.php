<!DOCTYPE html>
<html>
    
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        
	<title>Base de Datos JKR Mérida</title>
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <script src="js/jquery.min.js"></script>		
        <script src="js/scripts.js"></script>		
	  
    </head>    
    <body>
        <?php 
        
        $con = new mysqli("localhost","root","","jkr");
        mysqli_set_charset($con,"utf8");
            
        if ($con->connect_error){
          die( "Failed to connect to MySQL: " . $con->connect_error);
        } 
        
        $loginform = "<form action=index.php method=post>
												<p align=center>
												<input id=username name=username type=search value='' placeholder=Usuario>
											  </p>
											  <p align=center>
												<input id=password name=password type=password value='' placeholder=Contraseña>
												</p>
											  <p align=center>
												<button type=submit>Entrar</button>
												</p>
											</form>";
        
        ?>
	
	      <h1 align=center><img src="images/logo.jpg"></h1>
        <h1 align=center>Base de Datos JKR Mérida</h1>
        <br>
				        
		<?php
			if ($_POST) {  
			  if (isset($_POST['username']) and isset($_POST['password'])) {
			    //Hicimos login
			    
			    $result = $con->query("SELECT * FROM usuario where username = '" . $_POST['username'] . "' and password = '". $_POST['password'] ."'");
			    
			    
			    if ($result->num_rows > 0) {
			      ?>
			      
			      <form action="index.php" method="post">
			        <p align=center><input type="submit" name=accion value="Dojos">&nbsp;<input type="submit" name=accion value="Practicantes"></p>
			      </form>
			      
			      <?php
			    } else {
			      echo $loginform . "<br><p align=center fontcolor=#FF0000>El usuario / contraseña no existe. Intente de nuevo.</p>";
			    }  
			  } else if (isset($_POST['accion'])) {
			      echo "<div align='center'><h3>" . $_POST['accion'] . "</h3></div><br>";
			      if ($_POST['accion'] == "Dojos") {
			        $querystring = "SELECT
			                        nombre,
			                        direccion,
			                        (SELECT concat(nombre, ' ', apellido) FROM practicante where id = dojo.instructor_id) as instructor,
			                        id
			                        FROM dojo
			        
			        ";
			  	    $result2 = $con->query($querystring);
			        
			        echo "<div align='center'><table border=1>
			              <tr>
			                <td>Nombre del Dojo</td>
			                <td>Dirección</td>
			                <td>Instructor</td>
			                <td>Acción</td>			                
			              </tr>";
			        while($row = $result2->fetch_assoc()){
			          //Imprimir listado de Dojos
			          
			          echo "
			            <tr>
			              <td>".$row['nombre']."</td>
			              <td>".$row['direccion']."</td>
			              <td>".$row['instructor']."</td>
			              <td><form action=index.php method=post><input type=hidden name=accion value=Practicantes><input type=hidden name=dojo_id value=". $row['id'] ."><input type=submit value='Ver Practicantes'></form></td>
			            </tr>
			          ";
			            
			        }
			      } else if ($_POST['accion'] == "Practicantes") {
			      	
			      	$querystring = "SELECT 
			      	                practicante.id as id, 
			      	                practicante.ci as ci,
			      	                practicante.nombre as nombre,
			      	                practicante.apellido as apellido,
			      	                practicante.dojo_id as dojo_id,
			      	                practicante.grado_actual_id as grado_id,
			      	                (SELECT nombre FROM dojo where id = practicante.dojo_id) as dojo,
			      	                (SELECT nombre FROM grado WHERE id = practicante.grado_actual_id) as grado_actual
			      	                FROM practicante
			      	                
			      	";
			      	
			      	if (isset($_POST['dojo_id'])) {
			      	  $querystring .= " WHERE dojo_id = " . $_POST['dojo_id'] . " ORDER BY grado_actual_id";	
			      	  
			      	} else {
			      	  $querystring .= " ORDER BY dojo_id";	
			      	}
			      	
			      	$result3 = $con->query($querystring);
			        
			        $query_dojos = "select * from dojo";
			        
			        if (isset($_POST['dojo_id'])) {
			      	  $query_dojos .= " WHERE id = " . $_POST['dojo_id'];	
			      	  
			      	}
			        
			        $result4 = $con->query($query_dojos);
			        $result5 = $con->query("select * from grado");
			        
			        $dojo_options = [];
			        $grado_options = [];
			     
			        while ($row = $result4->fetch_assoc()) {
			          $dojo_options[ $row['id'] ] = $row['nombre'];
			        }
			        
			        while ($row = $result5->fetch_assoc()) {
			          $grado_options[ $row['id'] ] = $row['nombre'];
			        }
			        
			        echo "<select style='visibility:hidden' id=dojo_proto_select disabled=true><>";
			        echo "<option value='' selected='selected'>Seleccione un Dojo</option>";
			        foreach ($dojo_options as $id=>$dojo) {
			          echo "<option value='". $id ."'>". $dojo ."</option>";    	
			        }
			        echo "</select>";
			        
			        echo "<select style='visibility:hidden' id=grado_proto_select disabled=true><>";
			        echo "<option value='' selected='selected'>Seleccione un Grado</option>";
			        foreach ($grado_options as $id=>$grado) {
			          echo "<option value='". $id ."'>". $grado ."</option>";    	
			        }
			        echo "</select>";
			        
			        
			        
			        echo "<div align='center'><table id='practicantes' border=1>
			              <tr>
			                <td>Cédula</td>
			                <td>Nombres</td>
			                <td>Apellidos</td>
			                <td>Dojo</td>
			                <td>Grado actual</td>
			                <td>Acción</td>
			              </tr>";
			        
			        while($row = $result3->fetch_assoc()){
			          //Imprimir listado de practicantes
			          
			          echo "
			            <tr>
			              
			              <input type=hidden name=id value=".$row['id'].">
			              <td><input type=text id=p_ci_".$row['id']." value='".$row['ci']."' disabled=true></td>
			              <td><input type=text id=p_nombre_".$row['id']." value='".$row['nombre']."' disabled=true></td>
			              <td><input type=text id=p_apellido_".$row['id']." value='".$row['apellido']."' disabled=true></td>
			              
			              <td><select id=p_dojo_".$row['id']." disabled=true>";
			              
			              foreach ($dojo_options as $id=>$dojo) {
			              	if ($id == $row['dojo_id']) {
			              	  $selected = " selected='selected'";	
			              	} else {
			              	  $selected = "";	
			              	}
			              	echo $id;
			                echo "<option value='". $id ."'". $selected .">". $dojo ."</option>";    	
			              }
			              
			          echo "</select></td>
			              
			              <td><select id=p_grado_actual_".$row['id']." disabled=true>";
			              
			              foreach ($grado_options as $id=>$grado) {
			              	if ($id == $row['grado_id']) {
			              	  $selected = " selected='selected'";	
			              	} else {
			              	  $selected = "";	
			              	}
			              	echo $id;
			                echo "<option value='". $id ."'". $selected .">". $grado ."</option>";    	
			              }
			              
			          echo "</select></td>
			              <td>
			                <input type=submit value='Editar' onclick='edit_practicante(".$row['id'].")'>
			                <input type=submit value='Guardar' onclick='guardar_practicante(".$row['id'].")'>
			                <input type=submit value='Borrar' onclick='del_practicante(".$row['id'].")'>
			              </td>
			            </tr>
			          ";
			            
			        }
			      	
			      }  
			      echo "</table></div><br>
			            <div align=center><input type=submit value='Agregar nuevo practicante' onclick='add_practicante()'>&nbsp;&nbsp;<input type=submit value='Subir listado en CSV (Excel)' onclick='add_bulk()'></div><br><br>
			      ";
			    	
			  }
			} else {
			  //Entrada inicial para hacer login	
			  echo $loginform;
			}  
		  
		  $con->close();
		?>
		
		
		
        
    </body>
</html>