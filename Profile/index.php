<?php
require_once("../DbHelper.php");
require_once("../Header/Header.php");
require_once("../Auth.php");
$conn = new DbHelper();
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="Profile-Style.php" media="screen" />
    <link rel="stylesheet" href="../Header/Header-Style.php" media="screen">
</head>

<body>
    <?php
    session_start();
    $session_id = $_SESSION["id"];
    session_write_close();
    $user = $conn->findUserById($session_id);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (isset($_POST['edit'])) {
        } else {
            session_start();
            unset($_SESSION["username"]);
            unset($_SESSION["id"]);
            session_write_close();
            header("Location: ../Login.php");
        }
    }


    ?>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <div class="card">
        <img src="https://previews.123rf.com/images/jemastock/jemastock1912/jemastock191217991/135321494-warehouse-worker-faceless-avatar-profile-logistics-job-concept-vector-illustration.jpg" alt="John" style="width:100%">
        <h1>Username: <?php echo $user["username"] ?></h1>
        <p class="title">Email: <?php echo $user["email"] ?> </p>
        <p>Telephone: <?php echo $user["telephone"] ?></p>
        <a href="#"><i class="fa fa-dribbble"></i></a>
        <a href="#"><i class="fa fa-twitter"></i></a>
        <a href="#"><i class="fa fa-linkedin"></i></a>
        <a href="#"><i class="fa fa-facebook"></i></a>
        <form action="index.php" method="post">
            <p> <input type="submit" name="edit" value="Edit" /> </p>
            <p> <input type="submit" name="logout" value="Logout" /> </p>
        </form>
    </div>

</body>

</html>