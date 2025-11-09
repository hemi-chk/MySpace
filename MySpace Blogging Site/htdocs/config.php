<?php
// config.php - Database Configuration


define('DB_HOST', 'sql101.infinityfree.com'); 
define('DB_USER', 'if0_40310812'); 
define('DB_PASS', 'CssK4ax6MIuP'); 
define('DB_NAME', 'if0_40310812_myblogapp');
// Create database connection
function getDBConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    return $conn;
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('BASE_URL', 'http://yourwebsite.infinityfreeapp.com/');
?>