<?php

  $con = new mysqli("localhost","root","","jkr");
  mysqli_set_charset($con,"utf8");
            
  if ($con->connect_error){
    die( "Failed to connect to MySQL: " . $con->connect_error);
  }
  
  if (isset($_POST['id'])) {
    $sql = "DELETE FROM practicante 
            WHERE id=?";
            
    $the_query = $con->prepare($sql);
    $the_query->bind_param("i", $_POST['id']);  
    $the_query->execute();
    if ($the_query->error) {
      die($the_query->error);	
    } else {
      echo $_POST['id'];
    }
  } 
  
  $con->close();

?>