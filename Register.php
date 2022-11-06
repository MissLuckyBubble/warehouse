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
    <title>Register</title>
    <link rel="stylesheet" href="Styles.php"  media="screen"/>
    <link rel="stylesheet" href="Header/Header-Style.php" media="screen">
</head>

<body>
    <?php

    function debug_to_console($data) {
        $output = $data;
        if (is_array($output))
            $output = implode(',', $output);
    
        echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
    }
    $username = $email = $password = $telephone = "";
    $Errusername = $Erremail = $ErrPass = $Err = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST["username"];
        $email    = $_POST["email"];
        $password = $_POST["password"];
        $telephone = $_POST["telephone"];

        if (empty($username) || empty($email) || empty($password)) {
            $Err = "Filds whit * are required!";
            $Errusername = "*";
            $Erremail = "*";
            $ErrPass = "*";
        }
        
        if(strlen($username)<5 || strlen($username)>15){
            $Errusername = "Username should be longer than 5 and less than 15 characters";
        }else if(preg_match('/([\s]+)|([\d]+)|([\@]+)/', $username, $output_array)) {
            $Errusername = "Can not contain digit, whitespaces or @";
        }else $Errusername = "";
    
        if(strlen($password)<6 || strlen($password)>20){
            $ErrPass = "Password should be longer than 6 and less than 20 characters";
        }else 
            if(!preg_match('/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!~@$%^&*-]).{6,20}\S+$/',$password,$output))  {
            $ErrPass = "Shoudl countain atleast 1 lowercase, uperrcase, digit, specialcharacter, no whitespaces";
        }else $ErrPass = "";

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $Erremail = "Invalid email format";
        }else $Erremail = "";

        if( $Errusername === $Erremail && $Erremail === $ErrPass  && $ErrPass === ""){
            $Err = "";
            $hashPass = password_hash($password, PASSWORD_DEFAULT);
            echo $hashPass;
            $conn->postUser($username,$hashPass,$email,$telephone);
            header("Location: Login.php");
        }
    }
    ?>

        <form name="frmRegistration" class="forms" method="post" action="Register.php">
            <table class="table">
                <tr class="tableheader">
                    <td align="center" colspan="2">Register</td>
                </tr>
                <?php
                if (isset($Err)) {
                ?>
                    <tr>
                        <td align="center" colspan="5">
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
                        <span class="message"> <?php echo $Errusername ?> </span>
                    </td>
                </tr>
                <tr class="tablerow">
                    <td align="right">Password</td>
                    <td>
                        <input type="password" name="password" value=<?php echo $password ?>>
                        <span class="message"> <?php echo $ErrPass ?> </span>
                    </td>
                </tr>
                <tr class="tablerow">
                    <td align="right">Email</td>
                    <td>
                        <input type="email" name="email" value=<?php echo $email ?>>
                        <span class="message"> <?php echo $Erremail ?> </span>
                    </td>
                </tr>
                <tr class="tablerow">
                    <td align="right">Phone №</td>
                    <td>
                        <input type="telephone" name="telephone" value=<?php echo $telephone ?>>
                    </td>
                </tr>
                <tr class="tableheader">
                    <td align="center" colspan="2"><input type="submit" name="submit" value="Submit"></td>
                </tr>
                <tr align="right">
                    <td colspan="2">
                    Already have account? <a href="Login.php">Login</a>
                    </td>
                </tr>
            </table>
        </form>
    </body>

</html>