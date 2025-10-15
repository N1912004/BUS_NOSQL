<?php
require_once '../Conductor_DashBoard/arangodb_connection.php';

use ArangoDBClient\Statement as ArangoStatement;
use ArangoDBClient\DocumentHandler as ArangoDocumentHandler;
use ArangoDBClient\Document as ArangoDocument;
use ArangoDBClient\CollectionHandler as ArangoCollectionHandler;

$documentHandler = new ArangoDocumentHandler($connection);
$collectionHandler = new ArangoCollectionHandler($connection);

//generating trip id
$query = 'FOR b IN busDetails SORT b.trip_no DESC LIMIT 1 RETURN b.trip_no';
$statement = new ArangoStatement($connection, ['query' => $query, '_flat' => true]);
$cursor = $statement->execute();
$lastTripNo = $cursor->current();

$tripNO = ($lastTripNo !== null) ? $lastTripNo + 1 : 1;

echo $tripNO;
?>
