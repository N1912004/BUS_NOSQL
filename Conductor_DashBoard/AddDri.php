<?php
session_start();

if ($_SESSION['status'] != "Active") {
    header("location:../Login/dist/login.php");
}

require_once 'arangodb_connection.php';

use ArangoDBClient\Document as ArangoDocument;
use ArangoDBClient\DocumentHandler as ArangoDocumentHandler;

$documentHandler = new ArangoDocumentHandler($connection);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Thêm thông tài xế</title>
    <link rel="icon" type="image/x-icon" href="../Images/favicon.ico">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="tripDetails.css">
    <link rel="stylesheet" type="text/css" href="../Login/dist/style.css">
    <style>
        body {
            background: url("../Images/bg-dark.jpg") no-repeat center;
            background-size: cover;

        }
    </style>
</head>

<body>
    <nav id="mainNavbar" class="navbar navbar-light navbar-expand-md py-1 px-2 fixed-top"
        style="background-color: #0cb2f9;">
        <a class="navbar-brand" href="conductorDashboard.php">
            <img src="../Images/icon.png" width="45" height="35" class="d-inline-block align-middle" alt="">
            Xe Buýt TP.HCM
        </a>

        <button class="navbar-toggler" data-toggle="collapse" data-target="#navLinks" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-between" id="navLinks">


            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="AdminDashboard.php" class="nav-link">TRANG CHỦ</a>
                </li>
                <li class="nav-item">
                    <a href="../about.html" class="nav-link">GIỚI THIỆU</a>
                </li>
                <li class="nav-item">
                    <a href="../team.html" class="nav-link">NHÓM PHÁT TRIỂN</a>
                </li>


            </ul>
            <span class="nav-item ml-auto">
                <a class="nav-link" role="button" href="AdminDashboard.php">Quay lại</a>
            </span>
            <span class="nav-item">
                <a class="nav-link" role="button" href="../Login/dist/logout.php">Đăng xuất</a>
            </span>


        </div>
    </nav>

    <div class="maindiv" id="maindiv" style="width: 30%;">

        <form action="AddDri.php" method="post">
            <div class="title">

                <h2 class="text-center">Thêm thông tin xác thực của tài xế</h2><br>
            </div>

            <div class="form-group">
                <div class="form-group">
                    <label>ID: </label>
                    <input class="form-control" type="text" placeholder="ID tài xế" name="did" maxlength="4">
                </div>
                <div class="form-group">
                    <label>Mật khẩu: </label>
                    <input class="form-control" type="text" placeholder="Mật khẩu" name="pwd">
                </div>
                <br>
                <button class="btn-item btn-block" style="width: 100%;" name='sub' type="submit">Submit</button>
            </div>
        </form>
    </div>

     <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
        integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
        integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
        crossorigin="anonymous"></script>

    <script>
        $(function () {
            $(document).scroll(function () {
                var $nav = $("#mainNavbar");
                $nav.toggleClass("scrolled", $(this).scrollTop() > $nav.height());
            });
        });
    </script>
</body>

<?php

if (isset($_POST['sub'])) {
    $pwd = $_POST['pwd'];
    $did = $_POST['did'];

    $loginDriverDocument = new ArangoDocument();
    $loginDriverDocument->set('_key', $did); // Using did as _key
    $loginDriverDocument->set('user_name', $did);
    $loginDriverDocument->set('password', $pwd);

    try {
        $documentHandler->save('loginDriver', $loginDriverDocument);
        if ($pwd != '') {
            echo "<script>alert('Thêm thành công tài xế!')</script>";
        }
    } catch (ArangoDBClient\Exception $e) {
        echo "<script>alert('Lỗi khi thêm tài xế: " . $e->getMessage() . "')</script>";
    }
}
?>

</html>
