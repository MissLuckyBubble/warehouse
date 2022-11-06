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
    <title>Products</title>
    <link rel="stylesheet" href="Products-Style.php" media="screen" />
    <link rel="stylesheet" href="../Header/Header-Style.php" media="screen">
</head>

<body>
    <?php

    $title = $category = $code = "";
    $items_per_page = 5;
    $page=1;
    session_start();
    if (
        isset($_SESSION["search_title"]) ||
        isset($_SESSION["search_category"]) ||
        isset($_SESSION["search_code"]) ||
        isset($_SESSION["items_per_page"]))
    {
        $title = $_SESSION["search_title"];
        $category = $_SESSION["search_category"];
        $code = $_SESSION["search_code"];
        $items_per_page = $_SESSION["items_per_page"];
    }
    session_write_close();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        session_start();
        $title = $_POST["title"];
        $_SESSION["search_title"] = $title;
        $category = $_POST["category"];
        $_SESSION["search_category"] = $category;
        $code = $_POST["code"];
        $_SESSION["search_code"] = $code;
        $items_per_page = $_POST["items_per_page"];
        $_SESSION["items_per_page"] = $items_per_page;
        session_write_close();
    }

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $result = $conn->deleteProduct($id);
            if ($result) {
                header('location: http://localhost/sklad/Products');
            }
        }

        if(isset($_GET['page'])){
            $page = $_GET['page'];
        }
    }

    $rows = $conn->getAllProducts($title, $category, $code, $page, $items_per_page);

    ?>
    <form action="index.php" method="POST" name="search">

        <input class="input" type="text" name="title" value="<?php echo $title; ?>" placeholder="Enter product title" />
        <select class="input" name="category">
            <option <?php if ($category === "") echo "selected" ?> value="">All</option>
            <option <?php if ($category === "Groceries") echo "selected" ?> value="Groceries">Groceries</option>
            <option <?php if ($category === "Office materials") echo "selected" ?> value="Office materials">Office materials</option>
            <option <?php if ($category === "Building materials") echo "selected" ?> value="Building materials">Building materials</option>
        </select>
        <input class="input" type="text" name="code" value="<?php echo $code; ?>" placeholder="Enter product code" />
        Items Per Page:
        <select style="margin:10px" name="items_per_page">
            <option <?php if ($items_per_page == 1) echo "selected" ?> value=1>1</option>
            <option <?php if ($items_per_page == 3) echo "selected" ?> value=3>3</option>
            <option <?php if ($items_per_page == 5) echo "selected" ?> value=5>5</option>
            <option <?php if ($items_per_page == 10) echo "selected" ?> value=10>10</option>
        </select>
        <input class="input" type="submit" name="" value="Search" />

    </form>
    <div class="Conteiner">
        <?php
        if ($rows)
            foreach ($rows as $r) {
        ?>
            <div class="item">
                <div class="card">
                    <img src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($r['image']); ?>" title="<?php echo $r["title"] ?>" style="width:100%; max-height:300px;">
                    <h1><?php echo $r["title"] ?></h1>
                    <h2>Category: <?php echo $r["category"] ?></h2>
                    <p class="price">Purchase price: <?php echo $r["purchase_price"] ?>$</p>
                    <p class="price">Sale price: <?php echo $r["sale_price"] ?>$</p>
                    <p><?php echo $r["description"] ?></p>
                    <p>Code:<?php echo $r["code"] ?> <br />
                        Count: <?php echo $r["count"] ?> </p>
                    <p>
                        <a class="button" href="http://localhost/sklad/Products/Create.php?id=<?php echo $r['id'] ?>">Edit</a>
                        <a class="button" onClick="javascript: return confirm('Are you sure you want to delete this Product whit code: <?php echo $r["code"]; ?> ');" href="index.php?id=<?php echo $r['id'] ?>">Delete</a>
                    </p>

                </div>
            </div>
        <?php
            }

        else {
        ?>
            <img style="padding-left: 300px;" src="https://brightyourfutures.in/assets/website/image/oops.png">
        <?php
        }
        ?>
    </div>
    <div class="table">

        <?php
            $number_of_results = $conn->getTotalNumberOfProducts($title, $category, $code);
            $number_of_pages = ceil($number_of_results / $items_per_page);
        for ($page = 1; $page <= $number_of_pages; $page++) {
        ?>
            <a class="pager" href="http://localhost/sklad/Products/?page=<?php echo $page ?>"> <?php echo $page ?> </a>
        <?php
        }
        ?>
    </div>
</body>

</html>