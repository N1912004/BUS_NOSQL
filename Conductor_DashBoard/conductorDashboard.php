<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if ($_SESSION['status'] != "Active") {
    header("location:../Login/dist/login.php");
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>BẢNG ĐIỀU KHIỂN DẪN</title>
    <link rel="icon" type="image/x-icon" href="../Images/icon1.png">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css"
        integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <link rel="stylesheet" href="../Login/dist/style.css">


    <style>
        html,
        body {
            min-height: 100%;
        }

        body,
        div,
        form,
        input,
        select,
        p {
            padding: 0;
            margin: 0;
            outline: none;
            font-family: Roboto, Arial, sans-serif;
            font-size: 16px;
            color: #eee;
        }

        body {
            background: url("https://storage.googleapis.com/blogvxr-uploads/2024/07/ben-xe-an-suong.jpg") no-repeat center;
            background-size: cover;

        }

        h1,
        h2 {
            text-transform: uppercase;
            font-weight: 400;
        }

        h2 {
            margin: 0 0 0 8px;
        }

        .main-block {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100%;
            padding: 25px;
            background: rgba(0, 0, 0, 0.5);
        }

        .left-part,
        form {
            padding: 25px;
        }

        .left-part {
            text-align: center;
        }


        form {
            background: rgba(0, 0, 0, 0.7);
        }

        .title {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .info {
            display: flex;
            flex-direction: column;
        }

        input,
        select {
            padding: 5px;
            margin-bottom: 30px;
            background: transparent;
            border: none;
            border-bottom: 1px solid #eee;
        }

        input::placeholder {
            color: #eee;
        }

        option:focus {
            border: none;
        }

        option {
            background: black;
            border: none;
        }

        .checkbox input {
            margin: 0 10px 0 0;
            vertical-align: middle;
        }

        .checkbox a {
            color: #26a9e0;
        }

        .checkbox a:hover {
            color: #85d6de;
        }

        .btn-item,
        button {
            padding: 10px 5px;
            margin-top: 20px;
            border-radius: 5px;
            border: none;
            background: #26a9e0;
            text-decoration: none;
            font-size: 15px;
            font-weight: 400;
            color: #fff;
        }

        .btn-item {
            display: inline-block;
            margin: 20px 5px 0;
        }

        button {
            width: 100%;
        }

        button:hover,
        .btn-item:hover {
            background: #85d6de;
        }

        @media (min-width: 568px) {

            html,
            body {
                height: 100%;
            }

            .main-block {
                flex-direction: row;
                height: calc(100% - 50px);
            }

            .left-part,
            form {
                flex: 1;
                height: auto;
            }
        }
    </style>


</head>

<body>

    <nav id="mainNavbar" class="navbar navbar-light navbar-expand-md py-1 px-2 fixed-top"
        style="background-color: #0cb2f9;">
        <a class="navbar-brand" href="#">
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

            <span class="nav-item">
                <a class="nav-link" role="button" href="../Login/dist/logout.php">Đăng xuất</a>
            </span>

        </div>
    </nav>

    <div class="main-block" style="width: 100%; margin: 0 auto; height: 100%;">
        <div>
            <img src="https://cdn-icons-png.flaticon.com/512/2798/2798177.png">

            <h3>ID người lơ xe:
                <?php echo $_SESSION['username'] ?>
            </h3>
            <h4> <!-- PHP CODE TO PRINT WELCOME STATMENT -->
<?php
require_once 'arangodb_connection.php';

use ArangoDBClient\Statement as ArangoStatement;
use ArangoDBClient\DocumentHandler as ArangoDocumentHandler;
use ArangoDBClient\CollectionHandler as ArangoCollectionHandler;

$documentHandler = new ArangoDocumentHandler($connection);
$collectionHandler = new ArangoCollectionHandler($connection);

$conID = $_SESSION['username'];
$query = 'FOR b IN busDetails FILTER b.trip_no IN (FOR t IN tripIncharge FILTER t.Conductor_emp_id == @conID RETURN t.trip_no_incharge) SORT b.TripDate DESC LIMIT 1 RETURN { trip_no: b.trip_no, bus_no: b.bus_no, TripDate: b.TripDate }';
$statement = new ArangoStatement($connection, [
    'query' => $query,
    'bindVars' => ['conID' => $conID]
]);

$fetchData = [];
try {
    $cursor = $statement->execute();
    $fetchData = $cursor->getAll();
} catch (ArangoDBClient\Exception $e) {
    error_log("ArangoDB Error: " . $e->getMessage());
}

if (empty($fetchData)) {
?>Chào mừng! Chưa có chuyến đi nào được chỉ định!
<?php
} else {
    $data = $fetchData[0]; // Get the latest trip
?>
Welcome! Latest Trip ID:(for
<?php echo $data['TripDate'] ?? ''; ?>):
<?php echo $data['trip_no'] ?? ''; ?><br>
Bus number:
<?php echo $data['bus_no'] ?? ''; ?>
<?php
}
?>

            </h4>
        </div>

        <div class="left-part">

            <h1>BẢNG ĐIỀU KHIỂN DẪN</h1>
            <br><br><br><br>

            <h3>Nhập chi tiết</h3>

            <div class="">

                <a class="btn btn-item btn-block" style="width: 50%;" href="passengerDetailsForm.php">NHẬP THÔNG TIN HÀNH KHÁCH</a>
                <br>
                <a class="btn btn-item btn-block" style="width: 50%;" href="tripDetailsForm.php">NHẬP CHI TIẾT CHUYẾN ĐI</a>
            </div>

            <br><br><br>
            <h3>Lịch trình của bạn</h3>
            <div class="">
                <a class="btn btn-item btn-block" style="width: 25%;" href="ConTripView.php">Chuyến đi được giao</a>
            </div>


        </div>

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
<!-- $conID = $_SESSION['username'];
            $result = $conn->query("SELECT `bus_details`.`trip_no`,`bus_details`.`bus_no` AS `bus_no`, `bus_details`.`Source` AS `Source`,`bus_details`.`Destination` AS `Destination`,`bus_details`.`TripDate` AS `TripDate`,`trip_incharge`.`Driver_emp_id` AS `Driver_emp_id`,`trip_incharge`.`scheduled_dept_time` AS `scheduled_dept_time`,`trip_incharge`.`scheduled_arr_time` AS `scheduled_arr_time`
FROM (`bus_details` join `trip_incharge` on(`trip_incharge`.`trip_no_incharge` = `bus_details`.`trip_no`))
WHERE `Conductor_emp_id`='$conID'"); //take code from specificRevenue.php-->
