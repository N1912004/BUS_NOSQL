<?php

session_start();

if ($_SESSION['status'] != "Active") {
    header("location:../Login/dist/login.php");
}

require_once 'arangodb_connection.php';

use ArangoDBClient\Statement as ArangoStatement;
use ArangoDBClient\DocumentHandler as ArangoDocumentHandler;
use ArangoDBClient\CollectionHandler as ArangoCollectionHandler;

$documentHandler = new ArangoDocumentHandler($connection);
$collectionHandler = new ArangoCollectionHandler($connection);

$columns = ['trip_no', 'bus_no', 'Source', 'Destination', 'TripDate', 'Driver_emp_id', 'scheduled_dept_time', 'scheduled_arr_time'];
$fetchData = fetch_data($connection, $columns);

function fetch_data($connection, $columns)
{
    $msg = "";
    if (empty($connection)) {
        $msg = "Database connection error";
    } elseif (empty($columns) || !is_array($columns)) {
        $msg = "columns Name must be defined in an indexed array";
    } else {
        $conID = $_SESSION['username'];
        $query = 'FOR b IN busDetails FILTER b.trip_no IN (FOR t IN tripIncharge FILTER t.Conductor_emp_id == @conID RETURN t.trip_no_incharge) LET ti = (FOR t_incharge IN tripIncharge FILTER t_incharge.trip_no_incharge == b.trip_no RETURN t_incharge) RETURN { trip_no: b.trip_no, bus_no: b.bus_no, Source: b.Source, Destination: b.Destination, TripDate: b.TripDate, Driver_emp_id: ti[0].Driver_emp_id, scheduled_dept_time: ti[0].scheduled_dept_time, scheduled_arr_time: ti[0].scheduled_arr_time }';
        
        $statement = new ArangoStatement($connection, [
            'query' => $query,
            'bindVars' => ['conID' => $conID]
        ]);

        try {
            $cursor = $statement->execute();
            $result = $cursor->getAll();
            if (!empty($result)) {
                $msg = $result;
            } else {
                $msg = "No Data Found";
            }
        } catch (ArangoDBClient\Exception $e) {
            $msg = "ArangoDB Error: " . $e->getMessage();
        }
    }
    return $msg;
}
?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Assigned Trips</title>
    <link rel="icon" type="image/x-icon" href="../Images/icon1.png">
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <link rel="stylesheet" type="text/css" href="../Login/dist/style.css">
    <style>
        body {
            background: url("../Images/bg-dark.jpg") no-repeat center;
            background-size: cover;
                      
        }
    </style>
</head>

<body>
    <nav id="mainNavbar" class="navbar navbar-light navbar-expand-md py-1 px-2 fixed-top" style="background-color: #0cb2f9;">
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
			<span class="nav-item ml-auto">
                <a class="nav-link" role="button" href="conductorDashboard.php">Quay lại</a>
			</span>
			<span class="nav-item">
				<a class="nav-link" role="button" href="../Login/dist/logout.php">Đăng xuất</a>
			</span>
            
			
        </div>
    </nav>
    
    <div class="card" style="width:70%">
        <!-- 'trip_no', 'bus_no','Source','Destination','TripDate','Driver_emp_id','scheduled_dept_time', 'scheduled_arrival_time' -->

        <?php echo $deleteMsg ?? ''; ?>
        <div class="table-responsive bg-white" >
            <table class="table table-hover table-bordered table-striped mb-0" style="text-align: center;">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Số chuyến đi</th>
                        <th scope="col">Số xe buýt</th>
                        <th scope="col">Nguồn</th>
                        <th scope="col">Điểm đến</th>
                        <th scope="col">Ngày đi</th>
                        <th scope="col">ID tài xế</th>
                        <th scope="col">Giờ khởi hành</th>
                        <th scope="col">Giờ đến</th>
                </thead>
                <tbody>
                    <?php
                    if (is_array($fetchData)) {
                        $sn = 1;
                        foreach ($fetchData as $data) {
                    ?>
                    <tr>
                        <td>
                            <?php echo $sn; ?>
                        </td>
                        <td>
                            <?php echo $data['trip_no'] ?? ''; ?>
                        </td>
                        <td>
                            <?php echo $data['bus_no'] ?? ''; ?>
                        </td>
                        <td>
                            <?php echo $data['Source'] ?? ''; ?>
                        </td>
                        <td>
                            <?php echo $data['Destination'] ?? ''; ?>
                        </td>
                        <td>
                            <?php echo $data['TripDate'] ?? ''; ?>
                        </td>
                        <td>
                            <?php echo $data['Driver_emp_id'] ?? ''; ?>
                        </td>
                        <td>
                            <?php echo $data['scheduled_dept_time'] ?? ''; ?>
                        </td>
                        <td>
                            <?php echo $data['scheduled_arr_time'] ?? ''; ?>
                        </td>
                    </tr>
                    <?php
                            $sn++;
                        }
                    } else { ?>
                    <tr>
                        <td colspan="8">
                            <?php echo $fetchData; ?>
                        </td>
                    <tr>
                        <?php
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
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
