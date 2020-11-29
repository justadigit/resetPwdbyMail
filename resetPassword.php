<?php 
  require("config/dbh.inc.php");
  if(!isset($_GET['code'])){
    exit("Someting Wrong!");
  }
  $code = $_GET['code'];
  $stmt = mysqli_stmt_init($conn);
  $getSql = "SELECT email FROM resetPasswords WHERE code=?;";

  if(!mysqli_stmt_prepare($stmt,$getSql)){
    exit("Something Wrong!");
  }
  mysqli_stmt_bind_param($stmt,"s",$code);
  mysqli_stmt_execute($stmt);
  
  $resultData = mysqli_stmt_get_result($stmt);
  if($row=mysqli_fetch_assoc($resultData)){
    $email = $row['email'];
  }else{
    exit("Error");
  }
  if(isset($_POST['password'])){
    $newPwd = $_POST['password'];
    $updateSql = "UPDATE users SET password=? WHERE email=?;";
    if(!mysqli_stmt_prepare($stmt,$updateSql)){
      exit("Something email!");
    }
    $hassPassword = password_hash($newPwd,PASSWORD_DEFAULT);
    mysqli_stmt_bind_param($stmt,"ss",$hassPassword,$email);
    $status = mysqli_stmt_execute($stmt);
    if($status!==false){
      $deleteSql = "DELETE FROM resetPasswords WHERE code=?";
      if(!mysqli_stmt_prepare($stmt,$deleteSql)){
        exit("Update Fail!");
      }
      mysqli_stmt_bind_param($stmt,"s",$code);
      $status = mysqli_stmt_execute($stmt);
      if($status!==false){
        echo "Update Successfully!";
        exit();
      }else{
        exit("SomeThing Wrong!");
      }

    }else{
      exit("SomeThing Wrong!");
    }
    
  }
  mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Pasword</title>
</head>

<body>
  <form action="" method="POST">
    <input type="password" name="password" placeholder="Enter New Password ..." autocomplete="off">
    <br><br>
    <button type="submit" name="submit">Update Password</button>
  </form>
</body>

</html>