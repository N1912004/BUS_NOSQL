<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// MySQL connection
$mysql_hostName = "127.0.0.1";
$mysql_userName = "root";
$mysql_password = "";
$mysql_databaseName = "bus_HCM"; // Assuming this is the MySQL database name

$mysql_conn = new mysqli($mysql_hostName, $mysql_userName, $mysql_password, $mysql_databaseName, 3001);

if ($mysql_conn->connect_error) {
    die("MySQL Connection failed: " . $mysql_conn->connect_error);
} else {
    echo "Connected to MySQL successfully.<br>";
}

// ArangoDB connection
require_once 'Conductor_DashBoard/arangodb_connection.php';
use ArangoDBClient\Collection as ArangoCollection;
use ArangoDBClient\Document as ArangoDocument;
use ArangoDBClient\CollectionHandler as ArangoCollectionHandler;
use ArangoDBClient\Exception as ArangoException;

if (isset($connection)) {
    echo "Connected to ArangoDB successfully.<br>";
} else {
    die("ArangoDB Connection failed.");
}

$collectionHandler = new ArangoCollectionHandler($connection);

// Helper function to create collection if it doesn't exist
function createCollectionIfNotExists($collectionHandler, $collectionName) {
    if (!$collectionHandler->has($collectionName)) {
        $collection = new ArangoCollection($collectionName);
        $collectionHandler->create($collection);
        echo "Collection '{$collectionName}' created in ArangoDB.<br>";
    }
}

// --- Migration for 'bus_details' table ---
echo "Migrating 'bus_details' table...<br>";
$mysql_query = "SELECT bus_no, trip_no, Source, Destination, TripDate FROM bus_details";
$mysql_result = $mysql_conn->query($mysql_query);

if ($mysql_result->num_rows > 0) {
    createCollectionIfNotExists($collectionHandler, 'bus_details');
    while ($row = $mysql_result->fetch_assoc()) {
        $document = new ArangoDocument();
        $document->set('bus_no', (int)$row['bus_no']);
        $document->set('trip_no', (int)$row['trip_no']);
        $document->set('Source', $row['Source']);
        $document->set('Destination', $row['Destination']);
        $document->set('TripDate', $row['TripDate']);
        try {
            $collectionHandler->save('bus_details', $document);
            // echo "Inserted bus_details document: " . json_encode($document->getAll()) . "<br>";
        } catch (ArangoException $e) {
            echo "Error inserting bus_details document: " . $e->getMessage() . "<br>";
        }
    }
    echo "Finished migrating 'bus_details'.<br>";
} else {
    echo "No data found in 'bus_details' table.<br>";
}

// --- Migration for 'login' table ---
echo "Migrating 'login' table...<br>";
$mysql_query = "SELECT user_name, password FROM login";
$mysql_result = $mysql_conn->query($mysql_query);

if ($mysql_result->num_rows > 0) {
    createCollectionIfNotExists($collectionHandler, 'login');
    while ($row = $mysql_result->fetch_assoc()) {
        $document = new ArangoDocument();
        $document->set('user_name', $row['user_name']);
        $document->set('password', $row['password']);
        try {
            $collectionHandler->save('login', $document);
        } catch (ArangoException $e) {
            echo "Error inserting login document: " . $e->getMessage() . "<br>";
        }
    }
    echo "Finished migrating 'login'.<br>";
} else {
    echo "No data found in 'login' table.<br>";
}

// --- Migration for 'login_admin' table ---
echo "Migrating 'login_admin' table...<br>";
$mysql_query = "SELECT user_name, password FROM login_admin";
$mysql_result = $mysql_conn->query($mysql_query);

if ($mysql_result->num_rows > 0) {
    createCollectionIfNotExists($collectionHandler, 'login_admin');
    while ($row = $mysql_result->fetch_assoc()) {
        $document = new ArangoDocument();
        $document->set('user_name', $row['user_name']);
        $document->set('password', $row['password']);
        try {
            $collectionHandler->save('login_admin', $document);
        } catch (ArangoException $e) {
            echo "Error inserting login_admin document: " . $e->getMessage() . "<br>";
        }
    }
    echo "Finished migrating 'login_admin'.<br>";
} else {
    echo "No data found in 'login_admin' table.<br>";
}

// --- Migration for 'login_driver' table ---
echo "Migrating 'login_driver' table...<br>";
$mysql_query = "SELECT user_name, password FROM login_driver";
$mysql_result = $mysql_conn->query($mysql_query);

if ($mysql_result->num_rows > 0) {
    createCollectionIfNotExists($collectionHandler, 'login_driver');
    while ($row = $mysql_result->fetch_assoc()) {
        $document = new ArangoDocument();
        $document->set('user_name', $row['user_name']);
        $document->set('password', $row['password']);
        try {
            $collectionHandler->save('login_driver', $document);
        } catch (ArangoException $e) {
            echo "Error inserting login_driver document: " . $e->getMessage() . "<br>";
        }
    }
    echo "Finished migrating 'login_driver'.<br>";
} else {
    echo "No data found in 'login_driver' table.<br>";
}

// --- Migration for 'lossmaking' table ---
echo "Migrating 'lossmaking' table...<br>";
$mysql_query = "SELECT Trip_no, revenue, tickets_sold FROM lossmaking";
$mysql_result = $mysql_conn->query($mysql_query);

if ($mysql_result->num_rows > 0) {
    createCollectionIfNotExists($collectionHandler, 'lossmaking');
    while ($row = $mysql_result->fetch_assoc()) {
        $document = new ArangoDocument();
        $document->set('Trip_no', (int)$row['Trip_no']);
        $document->set('revenue', (int)$row['revenue']);
        $document->set('tickets_sold', (int)$row['tickets_sold']);
        try {
            $collectionHandler->save('lossmaking', $document);
        } catch (ArangoException $e) {
            echo "Error inserting lossmaking document: " . $e->getMessage() . "<br>";
        }
    }
    echo "Finished migrating 'lossmaking'.<br>";
} else {
    echo "No data found in 'lossmaking' table.<br>";
}

// --- Migration for 'passenger' table ---
echo "Migrating 'passenger' table...<br>";
$mysql_query = "SELECT phone_no, ticket_id, ticket_price, Passenger_source, trip_no_passenger, Passenger_destination FROM passenger";
$mysql_result = $mysql_conn->query($mysql_query);

if ($mysql_result->num_rows > 0) {
    createCollectionIfNotExists($collectionHandler, 'passenger');
    while ($row = $mysql_result->fetch_assoc()) {
        $document = new ArangoDocument();
        $document->set('phone_no', $row['phone_no']);
        $document->set('ticket_id', (int)$row['ticket_id']);
        $document->set('ticket_price', (int)$row['ticket_price']);
        $document->set('Passenger_source', $row['Passenger_source']);
        $document->set('trip_no_passenger', (int)$row['trip_no_passenger']);
        $document->set('Passenger_destination', $row['Passenger_destination']);
        try {
            $collectionHandler->save('passenger', $document);
        } catch (ArangoException $e) {
            echo "Error inserting passenger document: " . $e->getMessage() . "<br>";
        }
    }
    echo "Finished migrating 'passenger'.<br>";
} else {
    echo "No data found in 'passenger' table.<br>";
}

// --- Migration for 'quicktrips' table ---
echo "Migrating 'quicktrips' table...<br>";
$mysql_query = "SELECT Trip_no, fuel, arrival_time, departure_time, km_count FROM quicktrips";
$mysql_result = $mysql_conn->query($mysql_query);

if ($mysql_result->num_rows > 0) {
    createCollectionIfNotExists($collectionHandler, 'quicktrips');
    while ($row = $mysql_result->fetch_assoc()) {
        $document = new ArangoDocument();
        $document->set('Trip_no', (int)$row['Trip_no']);
        $document->set('fuel', (int)$row['fuel']);
        $document->set('arrival_time', $row['arrival_time']);
        $document->set('departure_time', $row['departure_time']);
        $document->set('km_count', (int)$row['km_count']);
        try {
            $collectionHandler->save('quicktrips', $document);
        } catch (ArangoException $e) {
            echo "Error inserting quicktrips document: " . $e->getMessage() . "<br>";
        }
    }
    echo "Finished migrating 'quicktrips'.<br>";
} else {
    echo "No data found in 'quicktrips' table.<br>";
}

// --- Migration for 'trip_incharge' table ---
echo "Migrating 'trip_incharge' table...<br>";
$mysql_query = "SELECT trip_no_incharge, Driver_emp_id, Conductor_emp_id, scheduled_dept_time, scheduled_arr_time FROM trip_incharge";
$mysql_result = $mysql_conn->query($mysql_query);

if ($mysql_result->num_rows > 0) {
    createCollectionIfNotExists($collectionHandler, 'trip_incharge');
    while ($row = $mysql_result->fetch_assoc()) {
        $document = new ArangoDocument();
        $document->set('trip_no_incharge', (int)$row['trip_no_incharge']);
        $document->set('Driver_emp_id', $row['Driver_emp_id']);
        $document->set('Conductor_emp_id', $row['Conductor_emp_id']);
        $document->set('scheduled_dept_time', $row['scheduled_dept_time']);
        $document->set('scheduled_arr_time', $row['scheduled_arr_time']);
        try {
            $collectionHandler->save('trip_incharge', $document);
        } catch (ArangoException $e) {
            echo "Error inserting trip_incharge document: " . $e->getMessage() . "<br>";
        }
    }
    echo "Finished migrating 'trip_incharge'.<br>";
} else {
    echo "No data found in 'trip_incharge' table.<br>";
}

// --- Migration for 'trip_real_details' table ---
echo "Migrating 'trip_real_details' table...<br>";
$mysql_query = "SELECT trip_no_real, fuel, arrival_time, departure_time, km_count FROM trip_real_details";
$mysql_result = $mysql_conn->query($mysql_query);

if ($mysql_result->num_rows > 0) {
    createCollectionIfNotExists($collectionHandler, 'trip_real_details');
    while ($row = $mysql_result->fetch_assoc()) {
        $document = new ArangoDocument();
        $document->set('trip_no_real', (int)$row['trip_no_real']);
        $document->set('fuel', (int)$row['fuel']);
        $document->set('arrival_time', $row['arrival_time']);
        $document->set('departure_time', $row['departure_time']);
        $document->set('km_count', (int)$row['km_count']);
        try {
            $collectionHandler->save('trip_real_details', $document);
        } catch (ArangoException $e) {
            echo "Error inserting trip_real_details document: " . $e->getMessage() . "<br>";
        }
    }
    echo "Finished migrating 'trip_real_details'.<br>";
} else {
    echo "No data found in 'trip_real_details' table.<br>";
}

// --- Migration for 'trip_result' table ---
echo "Migrating 'trip_result' table...<br>";
$mysql_query = "SELECT trip_no_result, revenue, tickets_sold FROM trip_result";
$mysql_result = $mysql_conn->query($mysql_query);

if ($mysql_result->num_rows > 0) {
    createCollectionIfNotExists($collectionHandler, 'trip_result');
    while ($row = $mysql_result->fetch_assoc()) {
        $document = new ArangoDocument();
        $document->set('trip_no_result', (int)$row['trip_no_result']);
        $document->set('revenue', (int)$row['revenue']);
        $document->set('tickets_sold', (int)$row['tickets_sold']);
        try {
            $collectionHandler->save('trip_result', $document);
        } catch (ArangoException $e) {
            echo "Error inserting trip_result document: " . $e->getMessage() . "<br>";
        }
    }
    echo "Finished migrating 'trip_result'.<br>";
} else {
    echo "No data found in 'trip_result' table.<br>";
}


// Close MySQL connection
$mysql_conn->close();
echo "MySQL connection closed.<br>";

?>
