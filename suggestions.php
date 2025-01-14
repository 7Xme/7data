<?php
include 'db_connect.php';

$query = isset($_GET['query']) ? $conn->real_escape_string($_GET['query']) : '';

$sql = "SELECT model_name FROM ecu_models WHERE model_name LIKE ? LIMIT 10";
$stmt = $conn->prepare($sql);
$search = '%' . $query . '%';
$stmt->bind_param("s", $search);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    echo '<div onclick="selectSuggestion(\'' . htmlspecialchars($row["model_name"]) . '\')">' . htmlspecialchars($row["model_name"]) . '</div>';
}

$stmt->close();
$conn->close();
?>
