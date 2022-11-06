<?php
require_once("DbHelper.php");
require_once("Header/Header.php");
require_once("Auth.php");
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warehouse</title>
    <link rel="stylesheet" href="Styles.php" media="screen" />
    <link rel="stylesheet" href="Header/Header-Style.php" media="screen">
</head>

<body>
    <h1 align="center">Hello, <?php echo $session_user ?>!</h1>

    <h2 align="center"> You are logged till <?php
                                            // here i echo the expire date + 2 * 60 * 60 because the browser time is different from my pc time
                                            echo date("Y-m-d H:i:s", $session_expire + 2 * 60 * 60)
                                            ?></h2>
</body>

</html>