<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'arangodb_connection.php';
use ArangoDBClient\AQLFunctions;
use ArangoDBClient\Statement as ArangoStatement;

$columns = ['trip_no', 'bus_no', 'Source', 'Destination', 'TripDate', 'Driver_emp_id', 'Conductor_emp_id', 'scheduled_dept_time', 'scheduled_arrival_time'];
$fetchData = fetch_data($connection, $columns);

function fetch_data($connection, $columns)
{
    if (empty($connection)) {
        $msg = "Lỗi kết nối cơ sở dữ liệu ArangoDB";
    } elseif (empty($columns) || !is_array($columns)) {
        $msg = "Tên cột phải được định nghĩa trong mảng";
    } else {
        $query = "FOR b IN bus_details
                    FOR t IN trip_incharge
                        FILTER t.trip_no_incharge == b.trip_no
                        RETURN {
                            trip_no: b.trip_no,
                            bus_no: b.bus_no,
                            Source: b.Source,
                            Destination: b.Destination,
                            TripDate: b.TripDate,
                            Driver_emp_id: t.Driver_emp_id,
                            Conductor_emp_id: t.Conductor_emp_id,
                            scheduled_dept_time: t.scheduled_dept_time,
                            scheduled_arr_time: t.scheduled_arr_time
                        }";

        try {
            $statement = new ArangoStatement(
                $connection,
                [
                    "query" => $query,
                    "batchSize" => 1000,
                    "sanitize" => true,
                ]
            );
            $cursor = $statement->execute();
            $msg = $cursor->getAll();
            if (empty($msg)) {
                $msg = "Không tìm thấy dữ liệu";
            }
        } catch (ArangoException $e) {
            $msg = "ArangoDB Query Error: " . $e->getMessage();
        }
    }
    return $msg;
}
?>


<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Danh sách chuyến xe buýt</title>
    <link rel="icon" type="image/x-icon" href="../Images/icon1.png">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
        <a class="navbar-brand" href="#">
            <img src="../Images/icon1.png" width="45" height="35" class="d-inline-block align-middle" alt="">
            Xe Buýt TP.HCM
        </a>

        <button class="navbar-toggler" data-toggle="collapse" data-target="#navLinks" aria-label="Chuyển đổi menu">
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
                <li class="nav-item">
					<a href="timetable.php" class="nav-link">LỊCH TRÌNH</a>
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

    <div class="card" style="width:70%; margin: 80px auto;">
        <?php echo $deleteMsg ?? ''; ?>
        <div class="table-responsive bg-white">
            <table class="table table-hover table-bordered table-striped mb-0 text-center">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">STT</th>
                        <th scope="col">Mã chuyến</th>
                        <th scope="col">Số xe</th>
                        <th scope="col">Điểm đi</th>
                        <th scope="col">Điểm đến</th>
                        <th scope="col">Ngày khởi hành</th>
                        <th scope="col">Mã tài xế</th>
                        <th scope="col">Mã phụ xe</th>
                        <th scope="col">Giờ xuất bến</th>
                        <th scope="col">Giờ đến bến</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (is_array($fetchData)) {
                        $sn = 1;
                        foreach ($fetchData as $data) {
                    ?>
                    <tr>
                        <td><?php echo $sn; ?></td>
                        <td><?php echo $data['trip_no'] ?? ''; ?></td>
                        <td><?php echo $data['bus_no'] ?? ''; ?></td>
                        <td><?php echo $data['Source'] ?? ''; ?></td>
                        <td><?php echo $data['Destination'] ?? ''; ?></td>
                        <td><?php echo $data['TripDate'] ?? ''; ?></td>
                        <td><?php echo $data['Driver_emp_id'] ?? ''; ?></td>
                        <td><?php echo $data['Conductor_emp_id'] ?? ''; ?></td>
                        <td><?php echo $data['scheduled_dept_time'] ?? ''; ?></td>
                        <td><?php echo $data['scheduled_arr_time'] ?? ''; ?></td>
                    </tr>
                    <?php
                            $sn++;
                        }
                    } else { ?>
                    <tr>
                        <td colspan="10"><?php echo $fetchData; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
