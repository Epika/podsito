<?php
$host = 'localhost';
$dbname = 'u871379819_podsito'; // Remove whitespace, use existing DB
$username = 'u871379819_epika';
$password = 'ASDasd457!';

try {
    $conn = new mysqli($host, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    // Create visits table
    $sql1 = "CREATE TABLE IF NOT EXISTS visits (
        id INT AUTO_INCREMENT PRIMARY KEY,
        page VARCHAR(50),
        timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
        ip_address VARCHAR(45)
    )";
    
    // Create audio_plays table
    $sql2 = "CREATE TABLE IF NOT EXISTS audio_plays (
        id INT AUTO_INCREMENT PRIMARY KEY,
        audio_name VARCHAR(255),
        category VARCHAR(50),
        timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
        ip_address VARCHAR(45)
    )";
    
    // Create ad_clicks table
    $sql3 = "CREATE TABLE IF NOT EXISTS ad_clicks (
        id INT AUTO_INCREMENT PRIMARY KEY,
        ad_name VARCHAR(255),
        category VARCHAR(50),
        timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
        ip_address VARCHAR(45)
    )";
    
    // Execute each query separately
    $conn->query($sql1);
    $conn->query($sql2);
    $conn->query($sql3);

} catch (Exception $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>