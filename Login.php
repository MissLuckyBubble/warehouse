<?php
require_once("DbHelper.php");
require_once("Header/Header.php");
$conn = new DbHelper();
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="Styles.php" media="screen" />
    <link rel="stylesheet" href="Header/Header-Style.php" media="screen">
</head>

<body>
    <?php

    function debug_to_console($data)
    {
        $output = $data;
        if (is_array($output))
            $output = implode(',', $output);

        echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
    }

   
    $username = $password = "";
    $Err = "";

    if(isset($_GET["session_expired"]))
    {
        $Err = "Login Session is Expired. Please Login Again!";
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST["username"];
        $password = $_POST["password"];

        if (empty($username) || empty($password)) {
            $Err = "All filds are requred!";
        }

        $id = $conn->findUser($username, $password);
        if ($id !== -1) {
            session_start();
            $_SESSION["username"] = $username;
            $_SESSION["id"] = $id;
            $_SESSION["Expire_Date"] = time() + (30 * 60);
            session_write_close();
            header("Location: index.php");
        } else {
            $Err = "Not valid username or password!";
        }
    }
    ?>

    <body>
        <form name="frmLogin" class="forms" method="post" action="Login.php">
            <table class="table">
                <tr class="tableheader">
                    <td align="center" colspan="2">Login</td>
                </tr>
                <?php
                if (isset($Err)) {
                ?>
                    <tr>
                        <td align="center" colspan="2">
                            <span class="message"><?php echo $Err; ?></span>
                        </td>
                    </tr>
                <?php
                }
                ?>
                <tr class="tablerow">
                    <td align="right">Username</td>
                    <td>
                        <input type="text" name="username" value=<?php echo $username ?>>
                    </td>
                </tr>
                <tr class="tablerow">
                    <td align="right">Password</td>
                    <td>
                        <input type="password" name="password" value=<?php echo $password ?>>
                    </td>
                </tr>
                <tr class="tableheader">
                    <td align="center" colspan="2">
                        <input type="submit" name="submit" value="Login">
                    </td>
                </tr>
                <tr align="right">
                    <td colspan="2">
                        Don't have an account? <a href="Register.php">Register</a>
                    </td>
                </tr>
            </table>
        </form>
    </body>

</html>