<?php
// Database connection 
$host = '127.0.0.1';
$port = '3308';
$dbname = 'realestate';
$username = 'root';
$password = '';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
