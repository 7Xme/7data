<?php
include '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $make_id = $_POST['make_id'];
    $model_name = $_POST['model_name'];
    $image_path = !empty($_FILES['image_path']['name']) ? 'images/models/' . basename($_FILES['image_path']['name']) : null;
    if ($image_path) {
        // Ensure the directory exists
        if (!is_dir('images/models')) {
            mkdir('images/models', 0777, true);
        }
        move_uploaded_file($_FILES['image_path']['tmp_name'], $image_path);
    }
    $stmt = $conn->prepare("INSERT INTO models (make_id, model_name, image_path) VALUES (?, ?, ?)");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("iss", $make_id, $model_name, $image_path);
    $stmt->execute();
    $stmt->close();
    header("Location: add_model.php?success=1");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New Model</title>
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
    <h1>Add New Model</h1>
    <?php if (isset($_GET['success'])): ?>
        <p class="success-message">Model added successfully!</p>
    <?php endif; ?>
    <div class="form-container">
        <form action="add_model.php" method="post" enctype="multipart/form-data">
            <label for="make_id">Select Mark:</label>
            <select id="make_id" name="make_id" required>
                <?php
                $sql = "SELECT * FROM makes";
                $result = $conn->query($sql);
                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['make_id'] . "'>" . $row['make_name'] . "</option>";
                    }
                } else {
                    echo "Error fetching marks: " . htmlspecialchars($conn->error);
                }
                ?>
            </select>
            
            <label for="model_name">Model Name:</label>
            <input type="text" id="model_name" name="model_name" required>
            
            <label for="image_path">Model Image:</label>
            <input type="file" id="image_path" name="image_path">
            
            <input type="submit" value="Add Model">
        </form>
    </div>
</body>
</html>
