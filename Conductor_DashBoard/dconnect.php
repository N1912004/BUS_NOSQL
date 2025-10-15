<?php
session_start();

if ($_SESSION['status'] != "Active") {
    header("location:../Login/dist/login.php");
}



// database connection code
<?php
require_once 'arangodb_connection.php';

use ArangoDBClient\Statement as ArangoStatement;
use ArangoDBClient\DocumentHandler as ArangoDocumentHandler;
use ArangoDBClient\Document as ArangoDocument;
use ArangoDBClient\CollectionHandler as ArangoCollectionHandler;

$documentHandler = new ArangoDocumentHandler($connection);
$collectionHandler = new ArangoCollectionHandler($connection);

// Get all the trip_no from busDetails collection
$query = 'FOR b IN busDetails RETURN b.trip_no';
$statement = new ArangoStatement($connection, ['query' => $query, '_flat' => true]);
$cursor = $statement->execute();
$trip_nos_all = $cursor->getAll();


// get the post records
$TripNumber = $_POST['TripNumber'];
$fuelConsumption = $_POST['fuelConsumption'];
$actualArrivalTime = $_POST['actualArrivalTime'];
$actualDepTime = $_POST['actualDepTime'];
$kmCount = $_POST['kmCount'];

// database insert SQL code
$tripRealDetailsDocument = new ArangoDocument();
$tripRealDetailsDocument->set('trip_no_real', (int)$TripNumber);
$tripRealDetailsDocument->set('fuel', (int)$fuelConsumption);
$tripRealDetailsDocument->set('arrival_time', $actualArrivalTime);
$tripRealDetailsDocument->set('departure_time', $actualDepTime);
$tripRealDetailsDocument->set('km_count', (int)$kmCount);

try {
    $documentHandler->save('tripRealDetails', $tripRealDetailsDocument);
    echo "<p style='color:#EA1C2C;margin:70px 0px 0px 0px;'>Recorded successfully.</p>";
} catch (ArangoDBClient\Exception $e) {
    echo "Error recording trip details: " . $e->getMessage();
}

$did = $_SESSION['username'];
$query = 'FOR t IN tripIncharge FILTER t.Driver_emp_id == @did RETURN t.trip_no_incharge';
$statement = new ArangoStatement($connection, ['query' => $query, 'bindVars' => ['did' => $did], '_flat' => true]);
$cursor = $statement->execute();
$trip_nos_driver = $cursor->getAll();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Trip Details</title>
    <link rel="icon" type="image/x-icon" href="../Images/favicon.ico">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="driverDetails.css">
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
        <a class="navbar-brand" href="Driver_Dashboard.php">
            <img src="../Images/icon.png" width="45" height="35" class="d-inline-block align-middle" alt="">
            BUS MANAGEMENT SYSTEM
        </a>

        <button class="navbar-toggler" data-toggle="collapse" data-target="#navLinks" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-between" id="navLinks">


            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="Driver_Dashboard.php" class="nav-link">HOME</a>
                </li>
                <li class="nav-item">
                    <a href="../about.html" class="nav-link">ABOUT</a>
                </li>
                <li class="nav-item">
                    <a href="../team.html" class="nav-link">TEAM</a>
                </li>


            </ul>
            <span class="nav-item ml-auto">
                <a class="nav-link" role="button" href="Driver_Dashboard.php">Go Back</a>
            </span>
            <span class="nav-item">
                <a class="nav-link" role="button" href="../Login/dist/logout.php">Logout</a>
            </span>


        </div>
    </nav>

    <div class="maindiv" id="maindiv" style="width: 30%; padding:2%;">

        <form action="dconnect.php" method="post">
            <div id="title" class="title">

                <h2 class="text-center">Enter Trip Details</h2>
            </div>
            <br>
            <div id="info" class="info">

                <div class="form-group">
                    <label>Trip Number:</label>
                    <select class="form-control" name="TripNumber" placeholder="Trip Number" required>
                        <?php
                        // use a while loop to fetch data
                        // from the $all_categories variable
                        // and individually display as an option
                        <?php
                        foreach ($trip_nos_driver as $trip_no_incharge):
                        ?>
                        <option value="<?php echo $trip_no_incharge; ?>">
                            <?php echo $trip_no_incharge; ?>
                        </option>
                        <?php
                        endforeach;
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label> Fuel consumed: </label>
                    <input class="form-control" type="number" name="fuelConsumption" placeholder="Fuel consumed"
                        required>
                </div>

                <div class="form-group">
                    <label> Actual Arrival Time: </label>
                    <input class="form-control" type="time" name="actualArrivalTime" placeholder="Actual Arrival Time"
                        required>
                </div>

                <div class="form-group">
                    <label>Actual Departure Time: </label>
                    <input type="time" class="form-control" name="actualDepTime" placeholder="Actual Departure Time"
                        required />
                </div>
                <div class="form-group">
                    <label> Kilometer Count: </label>
                    <input name="kmCount" class="form-control" placeholder="Kilometer count" type="number" required />
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
