<?php
  require_once("C:/wamp64/www/sklad/Errorhandler.php");
  set_error_handler("errorHandler");
?>

<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="Header-Style.php" media="screen">
</head>

<body>
  <div class='container'>

    <ul>
      <li><a href="http://localhost/sklad">Home</a></li>
      <li><a href="http://localhost/sklad/Products">Products</a></li>
      <li><a href="http://localhost/sklad/Products/Create.php">Create</a></li>
      <?php
        session_start();
        session_write_close();
        if(isset($_SESSION["username"])){
      ?>
      <li style="float:right"><a href="http://localhost/sklad/Profile">Profile</a></li>
      <?php
        }else {
      ?>
      <li style="float:right"><a href="Login.php">Login</a></li> 
      <?php 
        }
      ?>
    </ul>
</body>

</html>