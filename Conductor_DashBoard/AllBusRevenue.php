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

$tableName = "revenueperbus"; // ArangoDB collection/view name
$columns = ['bus_no', 'revenue', 'tickets_sold'];

$aqlQuery = 'FOR r IN revenueperbus RETURN { bus_no: r.bus_no, revenue: r.revenue, tickets_sold: r.tickets_sold }';
$statement = new ArangoStatement($connection, ['query' => $aqlQuery]);

try {
    $cursor = $statement->execute();
    $fetchData = $cursor->getAll();
    if (empty($fetchData)) {
        $fetchData = "No Data Found";
    }
} catch (ArangoDBClient\Exception $e) {
    $fetchData = "ArangoDB Error: " . $e->getMessage();
}

// For the chart data, we need to fetch it separately
$dataPoints = array();
$aqlChartQuery = 'FOR r IN revenueperbus RETURN { x: r.bus_no, y: r.revenue }';
$chartStatement = new ArangoStatement($connection, ['query' => $aqlChartQuery]);

try {
    $chartCursor = $chartStatement->execute();
    $chartResult = $chartCursor->getAll();
    foreach ($chartResult as $row) {
        array_push($dataPoints, array("x" => $row['x'], "y" => $row['y']));
    }
} catch (ArangoDBClient\Exception $e) {
    // Handle chart data error
    error_log("ArangoDB Chart Data Error: " . $e->getMessage());
}
?>


<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Revenue Generated</title>
  <link rel="icon" type="image/x-icon" href="../Images/favicon.ico">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="../Login/dist/style.css">
  <style>
    body {
      background: url("../Images/bg-dark.jpg") no-repeat center;
      background-size: cover;

    }

    .graph {
      display: flex;
      margin: auto;
      padding: 10px;
      align-items: center;
    }

    .chartContainer {
      display: flex;
      justify-content: center;
      vertical-align: middle;
    }
  </style>
</head>

<body>
  <nav id="mainNavbar" class="navbar navbar-light navbar-expand-md py-1 px-2 fixed-top"
    style="background-color: #0cb2f9;">
    <a class="navbar-brand" href="#">
      <img src="../Images/icon.png" width="45" height="35" class="d-inline-block align-middle" alt="">
      BUS MANAGEMENT SYSTEM
    </a>

    <button class="navbar-toggler" data-toggle="collapse" data-target="#navLinks" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-between" id="navLinks">


      <ul class="navbar-nav">
        <li class="nav-item">
          <a href="AdminDashboard.php" class="nav-link">HOME</a>
        </li>
        <li class="nav-item">
          <a href="../about.html" class="nav-link">ABOUT</a>
        </li>
        <li class="nav-item">
          <a href="../team.html" class="nav-link">TEAM</a>
        </li>


      </ul>
      <span class="nav-item ml-auto">
        <a class="nav-link" role="button" href="AdminDashboard.php">Go Back</a>
      </span>
      <span class="nav-item">
        <a class="nav-link" role="button" href="../Login/dist/logout.php">Logout</a>
      </span>


    </div>
  </nav>

  <div class="" style="height: 70%; width: 70%;">
    <div class="row">
      <div class="col-sm">
        <!-- Pie chart code starts here -->
        <div class='graph'>

          <?php
          // $dataPoints is already populated by the ArangoDB query above
          ?>
          <script>
            window.onload = function () {

              var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                exportEnabled: true,
                theme: "light2", // "light1", "light2", "dark1", "dark2"

                title: {
                  text: "Revenue"
                },
                axisY: {
                  title: "INR"
                },
                axisX: {
                  title: "Trip Number"
                },
                data: [{
                  indexLabel: "{x}",
                  legendText: "{x}",
                  showInLegend: true,
                  type: "pie", //change type to bar, line, area, pie, etc  
                  dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
                }]
              });
            chart.render();
              
            }

          </script>

          <div id="chartContainer" style="height: 100%; width: 100%;"></div> <!--   container for graphs -->

          <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

        </div>
      </div>

      <div class="container col-sm">
        <div class="" id="maindiv">
          <div>
            <div class="col-sm-25">

              <form name="myform" align="center" action="SpecificRevenue.php" method="get">
                <div class="form-group">
                  <br>
                  Search by Bus Number: <input class="form-control" type="number" placeholder="Bus Number"
                    name="BusNumber"><br>
                  <input class="btn btn-primary btn-lg btn-block" type="submit">
                </div>
              </form>
              <?php echo $deleteMsg ?? ''; ?>
              <div class="table-responsive">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Bus Number</th>
                      <th>Revenue Generated</th>
                      <th>Tickets Sold</th>
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
                        <?php echo $data['bus_no'] ?? ''; ?>
                      </td>
                      <td>
                        <?php echo $data['revenue'] ?? ''; ?>
                      </td>
                      <td>
                        <?php echo $data['tickets_sold'] ?? ''; ?>
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
          </div>
        </div>
      </div>
    </div>
  </div>



  <!-- Pie chart code ends here -->
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
