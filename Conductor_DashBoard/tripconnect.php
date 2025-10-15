<?php

session_start();

if ($_SESSION['status'] != "Active") {
    header("location:../Login/dist/login.php");
}

require_once 'arangodb_connection.php';

use ArangoDBClient\Statement as ArangoStatement;
use ArangoDBClient\DocumentHandler as ArangoDocumentHandler;
use ArangoDBClient\Document as ArangoDocument;
use ArangoDBClient\CollectionHandler as ArangoCollectionHandler;

$documentHandler = new ArangoDocumentHandler($connection);
$collectionHandler = new ArangoCollectionHandler($connection);

// get the post records
$TripNumber = $_POST['TripNumber'];
$totalRevenue = $_POST['totalRevenue'];
$ticketsSold = $_POST['ticketsSold'];


// database insert SQL code
$tripResultDocument = new ArangoDocument();
$tripResultDocument->set('trip_no_result', (int)$TripNumber);
$tripResultDocument->set('revenue', (int)$totalRevenue);
$tripResultDocument->set('tickets_sold', (int)$ticketsSold);

try {
    $documentHandler->save('tripResult', $tripResultDocument);
    echo "<p style='color:#EA1C2C;margin:70px 0px 0px 0px;'>Recorded successfully.</p>";
} catch (ArangoDBClient\Exception $e) {
    echo "Error recording trip result: " . $e->getMessage();
}

$conid = $_SESSION['username'];
$query = 'FOR t IN tripIncharge FILTER t.Conductor_emp_id == @conid RETURN t.trip_no_incharge';
$statement = new ArangoStatement($connection, ['query' => $query, 'bindVars' => ['conid' => $conid], '_flat' => true]);
$cursor = $statement->execute();
$trip_nos_conductor = $cursor->getAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/x-icon" href="../Images/icon1.png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" href="../Login/dist/style.css">
    <link rel="stylesheet" type="text/css" href="tripDetails.css">
    <style>
        body {
            background: url("../Images/bus_huit.png") no-repeat center;
            background-size: cover;

        }
    </style>

    <title>Conductor Trip Details</title>
</head>

<body>
    <!-- <a class="button" href="../Login/dist/index.html">Log Out</a> -->
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
                    <a href="conductorDashboard.php" class="nav-link">HOME</a>
                </li>
                <li class="nav-item">
                    <a href="../about.html" class="nav-link">ABOUT</a>
                </li>
                <li class="nav-item">
                    <a href="../team.html" class="nav-link">TEAM</a>
                </li>


            </ul>
            <span class="nav-item ml-auto">
                <a class="nav-link" role="button" href="conductorDashboard.php">Go Back</a>
            </span>
            <span class="nav-item">
                <a class="nav-link" role="button" href="../Login/dist/logout.php">Logout</a>
            </span>


        </div>
    </nav>

    <div class="maindiv" id="maindiv" style="width: 25%;">

        <form action="tripconnect.php" method="post">
            <div class="title">
                <h2 class="text-center">Enter Trip Details</h2>
            </div>
            <br>
            <div class="info">

                <!-- Trip Number: <input type="number" placeholder="Trip Number" name="TripNumber"><br><br> -->
                <div class="form-group">
                    <label>Trip Number:</label>
                    <select class="form-control" name="TripNumber" placeholder="Trip Number" style="padding: 10px;
    margin: 10px 0;
    border-radius: 10px;
    box-shadow: 5px 5px 5px #aaaaaa;">
                        <?php
                        foreach ($trip_nos_conductor as $trip_no_incharge):
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
                    <label for="PhoneNumber">Total Revenue Generated:</label>
                    <input class="form-control" type="number" name="totalRevenue" placeholder="Revenue Generated">
                </div>

                <div class="form-group">
                    <label>Total Tickets Sold:</label>
                    <input class="form-control" type="number" name="ticketsSold" placeholder="Total Tickets Sold">
                </div>



            </div>

            <br>

            <button type="submit" href="/">Submit</button>
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
