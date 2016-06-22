<?php

  $con = new mysqli("localhost","root","","jkr");
  mysqli_set_charset($con,"utf8");
            
  if ($con->connect_error){
    die( "Failed to connect to MySQL: " . $con->connect_error);
  }
  
  if (isset($_POST['id'])) {
    $sql = "UPDATE practicante 
            SET ci = ?,
            nombre=?,
            apellido=?,
            dojo_id=?,
            grado_actual_id=? 
            WHERE id=?";
            
    $the_query = $con->prepare($sql);
    $the_query->bind_param("sssiii", $_POST['ci'],$_POST['nombre'],$_POST['apellido'],$_POST['dojo_id'],$_POST['grado_actual_id'],$_POST['id']);  
  } else {
    $sql = "INSERT INTO practicante 
            SET ci = ?,
            nombre=?,
            apellido=?,
            dojo_id=?,
            grado_actual_id=?";	
    $the_query = $con->prepare($sql);
    $the_query->bind_param("sssii", $_POST['ci'],$_POST['nombre'],$_POST['apellido'],$_POST['dojo_id'],$_POST['grado_actual_id']);        
  }
  
  $the_query->execute();
  if ($the_query->error) {
    die($the_query->error);	
  } else {
    echo $con->insert_id;;	
  }
  $con->close();

?>