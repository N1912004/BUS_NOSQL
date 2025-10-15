<?php
require_once 'arangodb_connection.php';
if (!$connection) {
    die('Connection failed!');
}

use ArangoDBClient\Statement as ArangoStatement;
use ArangoDBClient\DocumentHandler as ArangoDocumentHandler;
use ArangoDBClient\CollectionHandler as ArangoCollectionHandler;

$documentHandler = new ArangoDocumentHandler($connection);
$collectionHandler = new ArangoCollectionHandler($connection);

// Get all the trip_no from busDetails collection
$query = 'FOR b IN busDetails RETURN b.trip_no';
$statement = new ArangoStatement($connection, ['query' => $query, '_flat' => true]);
$cursor = $statement->execute();
$trip_nos = $cursor->getAll();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Passenger Details</title>
    <link rel="icon" type="image/x-icon" href="../Images/icon1.png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="driverDetails.css">
    <link rel="stylesheet" type="text/css" href="../Login/dist/style.css">
    <style>
        body {
            background: url("../Images/bus_huit.png") no-repeat center;
            background-size: cover;

        }
    </style>
</head>

<body>

    <nav id="mainNavbar" class="navbar navbar-light navbar-expand-md py-1 px-2 fixed-top"
        style="background-color: #0cb2f9;">
        <a class="navbar-brand" href="conductorDashboard.php">
            <img src="../Images/icon1.png" width="45" height="35" class="d-inline-block align-middle" alt="">
            Xe Buýt TP.HCM
        </a>

        <button class="navbar-toggler" data-toggle="collapse" data-target="#navLinks" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-between" id="navLinks">


            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="conductorDashboard.php" class="nav-link">TRANG CHỦ</a>
                </li>
                <li class="nav-item">
                    <a href="../about.html" class="nav-link">GIỚI THIỆU</a>
                </li>
                <li class="nav-item">
                    <a href="../team.html" class="nav-link">NHÓM PHÁT TRIỂN</a>
                </li>


            </ul>
            <span class="nav-item ml-auto">
                <a class="nav-link" role="button" href="conductorDashboard.php">Quay lại</a>
            </span>
            <span class="nav-item">
                <a class="nav-link" role="button" href="../Login/dist/logout.php">Đăng xuất</a>
            </span>


        </div>
    </nav>

    <div class="maindiv" id="maindiv" style="width: 30%; padding:2%;">
        <form action="connect.php" method="post">
            <div class="title">
                <h2 class="text-center">Nhập thông tin hành khách</h2>
            </div>
            <br>
            <div class="info">
                <!--<input type="date" placeholder="Trip Date" name="date">!-->
                <!-- Trip Number: <input type="number" placeholder="Trip Number" name="TripNumber"><br><br> -->
                <div class="form-group">
                    <label>Số chuyến đi:</label>
                    <select class="form-control" name="TripNumber" placeholder="Số chuyến đi" required>
                        <?php
                        foreach ($trip_nos as $trip_no):
                        ?>
                        <option value="<?php echo $trip_no; ?>">
                            <?php echo $trip_no; ?>
                        </option>
                        <?php
                        endforeach;
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="PhoneNumber">Số điện thoại</label>
                    <input class="form-control" type="number" id="tel" name="PhoneNumber" placeholder="ví dụ: 0905123456"
                        required>
                </div>

                <div class="form-group">
                    <label>Chọn Nguồn Trạm xe buýt: </label>
                    <input class="form-control" type="text" name="sourceChoice" placeholder="Ví dụ: Bến xe Tân Phú" required>
                    <!-- <select name="sourceChoice" placeholder="Source bus stop">
                        <option value="first">Vasco</option>
                        <option value="second" selected>Verna</option>
                        <option value="third">Margao</option>
                    </select><br><br> -->
                </div>
                <div class="form-group">
                    <label>Chọn điểm dừng xe buýt đích: </label>
                    <input class="form-control" type="text" name="destinationChoice" placeholder="Ví dụ: Bến xe Miền Tây"
                        required>
                    <!-- <select name="destinationChoice" placeholder="Destination bus stop">
                    <option value="first" selected>Vasco</option>
                    <option value="second">Verna</option>
                    <option value="third">Margao</option>
                </select><br><br> -->
                </div>
                <div class="form-group">
                    <label>Giá vé:  </label>
                    <input class="form-control" placeholder="Ví dụ: 3.000 VNĐ" name="Ticketprice" type="number">
                </div>
            </div>



            <button type="submit">Submit</button>
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

</html>
