<?php
$session_user = "";
$session_expire = "";
session_start();
if(isset($_SESSION["id"])){
$session_user_id = $_SESSION["id"];
$session_expire = $_SESSION['Expire_Date'];
$session_user = $_SESSION["username"];
}
session_write_close();
if (!$session_user) {
    header("Location: http://localhost/sklad/Login.php");
}else if($session_expire !== ""){
    $now = time();
    
    if ($now > $session_expire) {
        echo "Expired";
        session_start();
        session_destroy();
        header("Location: http://localhost/sklad/Login.php?session_expired=1");
    }
}
