<?php
include 'db_connect.php';

if (isset($_GET['model_id'])) {
    $model_id = $_GET['model_id'];

    // Use INNER JOIN for a more efficient and correct query
    $stmt = $conn->prepare("SELECT y.year_id, y.year
                            FROM years y
                            INNER JOIN vehicle_data vd ON y.year_id = vd.year_id
                            WHERE vd.model_id = ?
                            GROUP BY y.year"); // Group by year to avoid duplicates

    if ($stmt === false) {
        // Handle prepare error
        error_log("Prepare failed: " . $conn->error); // Log the error
        http_response_code(500); // Set HTTP status code to 500 (Internal Server Error)
        echo json_encode(["error" => "Database prepare error"]);
        exit;
    }

    $stmt->bind_param("i", $model_id);

    if (!$stmt->execute()) {
        // Handle execute error
        error_log("Execute failed: " . $stmt->error);
        http_response_code(500);
        echo json_encode(["error" => "Database execute error"]);
        $stmt->close();
        $conn->close();
        exit;
    }

    $result = $stmt->get_result();

    if ($result === false) {
        // Handle get_result error
        error_log("get_result failed: " . $stmt->error);
        http_response_code(500);
        echo json_encode(["error" => "Database result error"]);
        $stmt->close();
        $conn->close();
        exit;
    }

    $years = array();
    while ($row = $result->fetch_assoc()) {
        $years[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($years);

    $stmt->close();
    $conn->close();
} else {
    // Handle missing model_id
    http_response_code(400); // Set HTTP status code to 400 (Bad Request)
    echo json_encode(["error" => "Missing model_id parameter"]);
}
?>