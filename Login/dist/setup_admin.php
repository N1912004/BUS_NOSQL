<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin Setup - Hệ thống xe buýt TP.HCM</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #0cb2f9;
        }
        .success {
            color: #28a745;
            font-size: 18px;
        }
        .info {
            color: #17a2b8;
            font-size: 16px;
        }
        .error {
            color: #dc3545;
            font-size: 16px;
        }
        .credentials {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Admin Account Setup</h1>
        <hr>
        
        <?php
        /**
         * Script to create admin account in ArangoDB
         * Run this file once to set up the admin account
         */

        require_once '../../Conductor_DashBoard/arangodb_connection.php';

        use ArangoDBClient\Collection as ArangoCollection;
        use ArangoDBClient\CollectionHandler as ArangoCollectionHandler;
        use ArangoDBClient\Document as ArangoDocument;
        use ArangoDBClient\DocumentHandler as ArangoDocumentHandler;
        use ArangoDBClient\Exception as ArangoException;

        try {
            $collectionHandler = new ArangoCollectionHandler($connection);
            $documentHandler = new ArangoDocumentHandler($connection);
            
            // Create loginAdmin collection if it doesn't exist
            $collectionName = 'loginAdmin';
            
            try {
                $collection = $collectionHandler->get($collectionName);
                echo "<p class='info'>✅ Collection '$collectionName' already exists</p>";
            } catch (ArangoException $e) {
                // Collection doesn't exist, create it
                $collection = new ArangoCollection($collectionName);
                $collectionHandler->create($collection);
                echo "<p class='success'>✅ Collection '$collectionName' created successfully</p>";
            }
            
            // Check if admin user already exists
            $query = 'FOR u IN loginAdmin FILTER u.user_name == @username RETURN u';
            $statement = new ArangoDBClient\Statement($connection, [
                'query' => $query,
                'bindVars' => [
                    'username' => 'admin'
                ]
            ]);
            $cursor = $statement->execute();
            
            if ($cursor->count() > 0) {
                echo "<p class='info'>ℹ️ Admin user already exists</p>";
                echo "<div class='credentials'>";
                echo "<strong>Current admin credentials:</strong><br>";
                echo "Username: <strong>admin</strong><br>";
                echo "Password: <strong>admin123</strong>";
                echo "</div>";
            } else {
                // Create admin user
                $adminDocument = new ArangoDocument();
                $adminDocument->set('user_name', 'admin');
                $adminDocument->set('password', 'admin123');
                $adminDocument->set('role', 'admin');
                $adminDocument->set('created_at', date('Y-m-d H:i:s'));
                
                $documentHandler->save($collectionName, $adminDocument);
                
                echo "<p class='success'>✅ Admin account created successfully!</p>";
                echo "<div class='credentials'>";
                echo "<strong>Admin credentials:</strong><br>";
                echo "Username: <strong>admin</strong><br>";
                echo "Password: <strong>admin123</strong>";
                echo "</div>";
            }
            
            echo "<p class='success'>✅ Database connection is working properly!</p>";
            echo "<br><a href='adminlogin.php' style='padding: 10px 20px; background-color: #0cb2f9; color: white; text-decoration: none; border-radius: 5px;'>Go to Admin Login</a>";
            
        } catch (ArangoException $e) {
            echo "<p class='error'>❌ Error: " . $e->getMessage() . "</p>";
            echo "<p>Please make sure ArangoDB is running on tcp://127.0.0.1:8529</p>";
        }
        ?>
    </div>
</body>
</html>
