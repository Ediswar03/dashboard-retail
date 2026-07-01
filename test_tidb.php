<?php
$host = 'gateway01.ap-southeast-1.prod.aws.tidbcloud.com';
$port = '4000';
$user = '3pbs8mg2L1b6hrE.root';
$pass = 'ys6Dfbsfx2R4lvr5';
$db = 'test';

try {
    $options = [
        PDO::MYSQL_ATTR_SSL_CA => __DIR__ . '/cacert.pem',
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ];
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4", $user, $pass, $options);
    
    echo "Connected successfully to TiDB!\n";
    
    // Create retail_transactions table if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS dashboard_retail");
    echo "Database dashboard_retail created or exists.\n";
    
    $pdo->exec("USE dashboard_retail");
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS retail_transactions (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            InvoiceNo VARCHAR(50),
            StockCode VARCHAR(50),
            Description TEXT,
            Quantity INT,
            InvoiceDate DATETIME,
            UnitPrice DECIMAL(10,2),
            CustomerID VARCHAR(50),
            Country VARCHAR(100),
            created_at TIMESTAMP NULL,
            updated_at TIMESTAMP NULL
        )
    ");
    echo "Table retail_transactions created.\n";

} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
