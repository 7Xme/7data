<?php
// Include the database connection file
include 'db_connect.php';

$modelInfo = '';
$markeOptions = '';
$modelOptions = '';

if (isset($_GET['marke']) && !empty($_GET['marke'])) {
    $marke = $conn->real_escape_string($_GET['marke']);
    $sql = "SELECT model_id, model_name FROM ecu_models WHERE marke = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("s", $marke);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $selected = (isset($_GET['model_id']) && $_GET['model_id'] == $row["model_id"]) ? 'selected' : '';
            $modelOptions .= '<option value="' . htmlspecialchars($row["model_id"]) . '" ' . $selected . '>' . htmlspecialchars($row["model_name"]) . '</option>';
        }
        $stmt->close();
    }
}

if (isset($_GET['model_id']) && !empty($_GET['model_id'])) {
    $model_id = $conn->real_escape_string($_GET['model_id']);
    $sql = "SELECT * FROM ecu_models WHERE model_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $model_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $model_name = htmlspecialchars($row['model_name']);
            $description = isset($row['model_description']) ? htmlspecialchars($row['model_description']) : 'No description available';
            $image_path = isset($row['image_path']) ? htmlspecialchars($row['image_path']) : '';
            $modelInfo = "Model: $model_name<br>Description: $description";
            if ($image_path) {
                $modelInfo .= "<br><img src='$image_path' alt='Model Image'>";
            }
        } else {
            $modelInfo = 'No information available for the selected model.';
        }
        $stmt->close();
    } else {
        $modelInfo = 'Error preparing statement: ' . htmlspecialchars($conn->error);
    }
}

$sql = "SELECT DISTINCT marke FROM ecu_models";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $selected = (isset($_GET['marke']) && $_GET['marke'] == $row["marke"]) ? 'selected' : '';
    $markeOptions .= '<option value="' . htmlspecialchars($row["marke"]) . '" ' . $selected . '>' . htmlspecialchars($row["marke"]) . '</option>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ECU Model Info</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            margin-bottom: 20px;
        }

        select {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .model-info {
            margin-top: 20px;
        }

        .placeholder {
            background: #f9f9f9;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .placeholder img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 10px 0;
        }

        .credits {
            text-align: center;
            margin-top: 20px;
            color: #777;
        }
        
        .header-menu {
            background-color: #007BFF;
            padding: 10px;
            text-align: center;
        }
        .header-menu a {
            color: white;
            margin: 0 15px;
            text-decoration: none;
            font-weight: bold;
        }
        .header-menu a:hover {
            text-decoration: underline;
        }
                .credits {
                    text-align: center;
                    margin-top: 20px;
                    font-size: 0.9em;
                    color: #666;
                }
    </style>
</head>
<body>
<!-- Header Menu -->
<div class="header-menu">
    <a href="index.php">Home</a>
    <a href="obd_code_lookup.php">Obd code</a>
    <a href="ecu_model_info.php">Ecu PIN</a>
    <a href="pinout_airbag.php">Airbag PIN</a>
    <a href="fuse_box.php">Fuse Box</a>
</div>


    <div class="container">
        <h1>Select ECU Model</h1>
        <form method="GET" action="">
            <select name="marke" onchange="this.form.submit()">
                <option value="">Select a marke</option>
                <?php echo $markeOptions; ?>
            </select>
            <select name="model_id" onchange="this.form.submit()">
                <option value="">Select a model</option>
                <?php echo $modelOptions; ?>
            </select>
        </form>
        <div class="model-info">
            <div id="placeholder" class="placeholder">
                <?php echo $modelInfo; ?>
            </div>
        </div>
    </div>
    <div class="credits">
        &copy; 2023 Your Company. All rights reserved.
    </div>
</body>
</html>