<?php
// Database connection test for HackaZone
$servername = "db";
$username = "user";
$password = "password";
$dbname = "testdb";

echo "<html><head><title>Database Test - HackaZone</title>";
echo "<style>
    body { 
        font-family: 'Courier New', monospace; 
        background: #0f0f23; 
        color: #00ff00; 
        padding: 20px; 
    }
    .container { 
        max-width: 600px; 
        margin: 0 auto; 
        border: 2px solid #00ff00; 
        padding: 20px; 
        border-radius: 10px; 
        background: rgba(0,0,0,0.7); 
    }
    .success { color: #00ff00; }
    .error { color: #ff0000; }
    .info { color: #00ccff; }
    a { color: #00ccff; text-decoration: none; }
    a:hover { color: #00ff00; }
</style></head><body>";

echo "<div class='container'>";
echo "<h1>HackaZone Database Test</h1>";

try {
    // Create connection
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p class='success'>✓ Database connection successful!</p>";
    echo "<p class='info'>Server: $servername</p>";
    echo "<p class='info'>Database: $dbname</p>";
    echo "<p class='info'>User: $username</p>";
    
    // Test query
    $stmt = $pdo->query("SELECT VERSION() as version, NOW() as current_time");
    $result = $stmt->fetch();
    
    echo "<h3>Server Information:</h3>";
    echo "<p class='info'>MySQL Version: " . $result['version'] . "</p>";
    echo "<p class='info'>Current Time: " . $result['current_time'] . "</p>";
    
    // Check if we can create a test table
    $pdo->exec("CREATE TABLE IF NOT EXISTS test_table (
        id INT AUTO_INCREMENT PRIMARY KEY,
        test_data VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    
    // Insert test data
    $stmt = $pdo->prepare("INSERT INTO test_table (test_data) VALUES (?)");
    $stmt->execute(["HackaZone test entry - " . date('Y-m-d H:i:s')]);
    
    // Read test data
    $stmt = $pdo->query("SELECT * FROM test_table ORDER BY id DESC LIMIT 5");
    $rows = $stmt->fetchAll();
    
    echo "<h3>Recent Test Entries:</h3>";
    echo "<table border='1' style='border-color: #00ff00; color: #00ff00;'>";
    echo "<tr><th>ID</th><th>Test Data</th><th>Created At</th></tr>";
    foreach ($rows as $row) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . htmlspecialchars($row['test_data']) . "</td>";
        echo "<td>" . $row['created_at'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<p class='success'>✓ Database read/write operations successful!</p>";
    
} catch(PDOException $e) {
    echo "<p class='error'>✗ Connection failed: " . $e->getMessage() . "</p>";
}

echo "<br><a href='index.html'>← Back to HackaZone Home</a>";
echo "</div></body></html>";
?>