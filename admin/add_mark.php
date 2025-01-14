<?php
include '../db_connect.php'; // Adjusted the path to db_connect.php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mark_name = $_POST['mark_name'];
    $logo_path = !empty($_FILES['logo_path']['name']) ? 'images/logos/' . basename($_FILES['logo_path']['name']) : null;
    if ($logo_path) {
        // Ensure the directory exists
        if (!is_dir('images/logos')) {
            mkdir('images/logos', 0777, true);
        }
        move_uploaded_file($_FILES['logo_path']['tmp_name'], $logo_path);
    }
    $stmt = $conn->prepare("INSERT INTO makes (make_name, logo_path) VALUES (?, ?)"); // Corrected table name to 'makes'
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("ss", $mark_name, $logo_path);
    $stmt->execute();
    $stmt->close();
    header("Location: add_mark.php?success=1");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New Mark</title>
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
        label, input, textarea {
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
    <h1>Add New Mark</h1>
    <?php if (isset($_GET['success'])): ?>
        <p class="success-message">Mark added successfully!</p>
    <?php endif; ?>
    <div class="form-container">
        <form action="add_mark.php" method="post" enctype="multipart/form-data">
            <label for="mark_name">Mark Name:</label>
            <input type="text" id="mark_name" name="mark_name" required>
            
            <label for="logo_path">Mark Logo Image:</label>
            <input type="file" id="logo_path" name="logo_path">
            
            <input type="submit" value="Add Mark">
        </form>
    </div>
</body>
</html>
