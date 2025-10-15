<?php
//HELPS US SEARCH MILAGE ON ENTERING A BUS NUMBER
require_once 'arangodb_connection.php';

use ArangoDBClient\Statement as ArangoStatement;
use ArangoDBClient\DocumentHandler as ArangoDocumentHandler;
use ArangoDBClient\CollectionHandler as ArangoCollectionHandler;

$documentHandler = new ArangoDocumentHandler($connection);
$collectionHandler = new ArangoCollectionHandler($connection);

$busno = $_GET['BusNumber'];

$aqlQuery = 'FOR b IN busDetails FILTER b.bus_no == @busno FOR trd IN tripRealDetails FILTER trd.trip_no_real == b.trip_no COLLECT AGGREGATE mileage = AVG(trd.km_count / trd.fuel) RETURN { bus_no: b.bus_no, MILAGE: mileage }';
$statement = new ArangoStatement($connection, [
    'query' => $aqlQuery,
    'bindVars' => ['busno' => (int)$busno]
]);

try {
    $cursor = $statement->execute();
    $fetchData = $cursor->getAll();
    if (empty($fetchData)) {
        $fetchData = "No Data Found";
    }
} catch (ArangoDBClient\Exception $e) {
    $fetchData = "ArangoDB Error: " . $e->getMessage();
}
?>


<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Specific Bus Revenue</title>
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
        <a class="nav-link" role="button" href="milage.php">Go Back</a>
      </span>
      <span class="nav-item">
        <a class="nav-link" role="button" href="../Login/dist/logout.php">Logout</a>
      </span>


    </div>
  </nav>


  <div class="container" id="maindiv">
    <div>
      <div class="col-sm-25">

        <form align="center" action="milageBusNo.php" method="get">
          <div class="info">
            <br>
            Search by Bus Number: <input class="form-control" type="number" placeholder="Bus Number"
              name="BusNumber"><br>
            <input class="btn btn-primary btn-lg btn-block" type="submit">
          </div>


          <br>


          <?php echo $deleteMsg ?? ''; ?>
          <div class="table-responsive">
            <table class="table table-hover table-bordered table-striped mb-0" style="text-align: center;"">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Bus Number</th>
                  <th>Mileage</th>
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
                    <?php $busNum = $data['bus_no'] ?? '';
                    if (!$busNum) {
                      echo "<script>alert('incorrect bus number')</script>";
                      echo "bus number not found";
                    } // if empty value fetched from database, echos bus no not found
                    else {
                      echo "$busNum";
                    } ?>
                  </td>
                  <td>
                    <?php if (!$busNum) {
                      echo "bus number not found";
                    } else {
                      $MILAGE = $data['MILAGE'] ?? '';
                      echo $data['MILAGE'] ?? '';
                    } ?>
                  </td>
                </tr>
                <?php
                    $sn++;
                  }
                } else { ?>
                <tr>
                  <td colspan=" 8">
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
