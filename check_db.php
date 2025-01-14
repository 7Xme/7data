<?php
include 'db_connect.php';

// Check if the obd_codes table exists
$result = $conn->query("SHOW TABLES LIKE 'obd_codes'");
if ($result->num_rows > 0) {
    echo "Table 'obd_codes' exists.<br>";

    // Describe the table to check its structure
    $result = $conn->query("DESCRIBE obd_codes");
    if ($result) {
        echo "Columns in 'obd_codes':<br>";
        while ($row = $result->fetch_assoc()) {
            echo $row['Field'] . " - " . $row['Type'] . "<br>";
        }
    } else {
        echo "Error describing table: " . $conn->error;
    }
} else {
    echo "Table 'obd_codes' does not exist.";
}

$conn->close();
?>
