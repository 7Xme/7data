<?php
include '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $model_id = $_POST['model_id'];
    $year = $_POST['year'];
    $stmt = $conn->prepare("INSERT INTO years (model_id, year) VALUES (?, ?)");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("is", $model_id, $year);
    $stmt->execute();
    $stmt->close();
    header("Location: add_year.php?success=1");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New Year</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e0f7fa;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        h1 {
            color: #00796b;
            text-align: center;
        }
        .form-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #00796b;
            border-radius: 5px;
        }
        label, input, select {
            display: block;
            width: 100%;
            margin-bottom: 10px;
        }
        input[type="submit"] {
            background-color: #00796b;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #00695c;
        }
        .success-message {
            color: green;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Add New Year</h1>
    <?php if (isset($_GET['success'])): ?>
        <p class="success-message">Year added successfully!</p>
    <?php endif; ?>
    <div class="form-container">
        <form action="add_year.php" method="post">
            <label for="model_id">Select Model:</label>
            <select id="model_id" name="model_id" required>
                <?php
                $sql = "SELECT * FROM models ORDER BY model_name ASC";
                $result = $conn->query($sql);
                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['model_id'] . "'>" . $row['model_name'] . "</option>";
                    }
                } else {
                    echo "Error fetching models: " . htmlspecialchars($conn->error);
                }
                ?>
            </select>
            
            <label for="year">Year:</label>
            <input type="text" id="year" name="year" required>
            
            <input type="submit" value="Add Year">
        </form>
    </div>
</body>
</html>
