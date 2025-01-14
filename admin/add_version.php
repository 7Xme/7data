<?php
include '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $year_id = $_POST['year_id'];
    $version_name = $_POST['version_name'];
    $stmt = $conn->prepare("INSERT INTO model_versions (year_id, version_name) VALUES (?, ?)");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("is", $year_id, $version_name);
    $stmt->execute();
    $stmt->close();
    header("Location: add_version.php?success=1");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New Version</title>
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
    <h1>Add New Version</h1>
    <?php if (isset($_GET['success'])): ?>
        <p class="success-message">Version added successfully!</p>
    <?php endif; ?>
    <div class="form-container">
        <form action="add_version.php" method="post">
            <label for="year_id">Select Year:</label>
            <select id="year_id" name="year_id" required>
                <?php
                $sql = "SELECT * FROM years ORDER BY year ASC";
                $result = $conn->query($sql);
                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['year_id'] . "'>" . $row['year'] . "</option>";
                    }
                } else {
                    echo "Error fetching years: " . htmlspecialchars($conn->error);
                }
                ?>
            </select>
            
            <label for="version_name">Version Name:</label>
            <input type="text" id="version_name" name="version_name" required>
            
            <input type="submit" value="Add Version">
        </form>
    </div>
</body>
</html>
