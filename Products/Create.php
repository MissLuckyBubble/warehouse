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
    <title>Create Product</title>
    <link rel="stylesheet" href="../Styles.php" media="screen" />
    <link rel="stylesheet" href="../Header/Header-Style.php" media="screen">
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

    $title = $description = $category = $code = "";
    $sale_price = $purchase_price = $count = 0;
    $imgContent = "";
    $ErrCount = $errCode = $errImage = "";
    $Err = 0;

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $product = $conn->getProductByID($id);
           
            $title = $product["title"];
            $description  = $product["description"];
            $sale_price = $product["sale_price"];
            $purchase_price = $product["purchase_price"];
            $count = $product["count"];
            $category = $product["category"];
            $code = $product["code"];
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $title = $_POST["title"];
        $description  = $_POST["description"];
        $sale_price = $_POST["sale_price"] ?? 0;
        $purchase_price = $_POST["purchase_price"] ?? 0;
        $count = $_POST["count"] ?? 0;
        $category = $_POST["category"];
        $code = $_POST["code"];

        if ($count < 0) {
            $ErrCount = "Please eneter valid number!";
            $Err++;
        }

        if (isset($_POST["id"])) {
            $id = $_POST["id"];
            if ($conn->checkProductCodeWithId($code,$id)) {
                $errCode = "This code already existing!";
                $Err++;
            }
        } else if ($conn->checkProductCode($code)) {
            $errCode = "This code already existing!";
            $Err++;
        }

        if (!empty($_FILES["image"]["name"])) {
            $fileName = basename($_FILES["image"]["name"]);
            $fileType = pathinfo($fileName, PATHINFO_EXTENSION);

            $allowTypes = array('jpeg', 'jpg', 'png');
            if (in_array($fileType, $allowTypes)) {
                $image = $_FILES["image"]["tmp_name"];
                $imgContent = file_get_contents($image);
            } else {
                $errImage = "Sorry, only JPEG, JPG & PNG files are allowed";
                $Err++;
            }
        } else {
            $errImage = "Please select an image file to upload.";
            $Err++;
        }

        if ($Err == 0) {
            if (isset($_POST["id"])) {
                if ($conn->putProduct($_POST["id"], $title, $description, $sale_price, $purchase_price, $count, $category, $code, $imgContent)) {
                    header("Location: index.php");
                }
            } else if ($conn->postProduct($title, $description, $sale_price, $purchase_price, $count, $category, $code, $imgContent)) {
                header("Location: index.php");
            }
        }
    }
    ?>

    <body>
        <form name="create" class="forms" method="post" action="Create.php" enctype="multipart/form-data">
            <table class="table">
                <tr class="tableheader">
                    <?php if (isset($id)) { ?>
                        <td align="center" colspan="2">Edit Product</td>
                        <input type="hidden" name="id" value=<?php echo $id; ?>>
                    <?php } else { ?>
                        <td align="center" colspan="2">Create Product</td>
                    <?php } ?>
                </tr>
                <?php
                if ($Err !== 0) {
                ?>
                    <tr>
                        <td align="center" colspan="5">
                            <span class="message"><?php echo $Err . "invalid filds, fix them"; ?></span>
                        </td>
                    </tr>
                <?php
                }
                ?>
                <tr class="tablerow">
                    <td align="right">Title</td>
                    <td>
                        <input type="text" name="title" maxlength="50" required value="<?php echo $title ?>">
                    </td>
                </tr>
                <tr class="tablerow">
                    <td align="right">Description</td>
                    <td>
                        <textarea name="description" maxlength="2000" rows="5" cols="20"><?php echo $description ?></textarea>

                    </td>
                </tr>
                <tr class="tablerow">
                    <td align="right">Sale Price</td>
                    <td>
                        <input type="number" name="sale_price" step="0.01" required value="<?php echo $sale_price ?>">
                    </td>
                </tr>
                <tr class="tablerow">
                    <td align="right">Purchase Price</td>
                    <td>
                        <input type="number" name="purchase_price" step="0.01" required value="<?php echo $purchase_price ?>">
                    </td>
                </tr>
                <tr class="tablerow">
                    <td align="right">Count</td>
                    <td>
                        <input type="number" name="count" min="0" requred value="<?php echo $count ?>">
                        <span class="message"> <?php echo $ErrCount ?> </span>
                    </td>
                </tr>
                <tr class="tablerow">
                    <td align="right">Category</td>
                    <td>
                        <select name="category">
                            <option value="Groceries">Groceries</option>
                            <option value="Office materials">Office materials</option>
                            <option value="Building materials">Building materials</option>
                        </select>
                    </td>
                </tr>
                <tr class="tablerow">
                    <td align="right">Code</td>
                    <td>
                        <input type="text" name="code" maxlength="20" required value="<?php echo $code ?>">
                        <span class="message"> <?php echo $errCode ?> </span>
                    </td>
                </tr>
                <tr class="tablerow">
                    <td align="right">Image</td>
                    <td>
                        <input type="file" name="image" required value="<?php $imgContent ?>">
                        <span class="message"> <?php echo $errImage ?> </span>
                    </td>
                </tr>
                <tr class="tableheader">
                    <td align="center" colspan="2"><input type="submit" name="submit" value="Submit"></td>
                </tr>
                <tr align="right">
                    <td colspan="2">
                        <a href="/sklad/Products">Back to Products</a>
                    </td>
                </tr>
            </table>
        </form>
    </body>

</html>