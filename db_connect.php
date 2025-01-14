<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "autodata1";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("فشل الاتصال: " . $conn->connect_error);
}

// Debugging: Check if session variables are set
if (!isset($_SESSION['loggedin'])) {
    error_log("User not logged in. Redirecting to login page.");
}

// Redirect to login page if not logged in
if (!isset($_SESSION['loggedin']) && basename($_SERVER['PHP_SELF']) != 'login.php') {
    header("Location: login.php");
    exit();
}

// Restrict access to admin page
if (basename($_SERVER['PHP_SELF']) == 'admin.php' && $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}
?>