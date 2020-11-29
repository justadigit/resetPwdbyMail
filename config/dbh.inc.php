<?php 
  $conn = mysqli_connect("localhost","root",'',"resetPassword");

  if(mysqli_connect_errno()){
    echo "Connect Error: ". mysqli_connect_errno();
  }

?>