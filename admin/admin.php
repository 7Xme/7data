<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    ob_start(); // Start output buffering

    if (isset($_POST['save_make'])) {
        $make_id = $_POST['make_id'];
        $make_name = $_POST['make_name'];
        $logo_path = !empty($_FILES['logo_path']['name']) ? 'images/logos/' . basename($_FILES['logo_path']['name']) : null;
        if ($logo_path) {
            move_uploaded_file($_FILES['logo_path']['tmp_name'], $logo_path);
        }
        if ($make_id) {
            $stmt = $conn->prepare("UPDATE makes SET make_name = ?, logo_path = COALESCE(?, logo_path) WHERE make_id = ?");
            if ($stmt === false) {
                die('Prepare failed: ' . htmlspecialchars($conn->error));
            }
            $stmt->bind_param("ssi", $make_name, $logo_path, $make_id);
        } else {
            $stmt = $conn->prepare("INSERT INTO makes (make_name, logo_path) VALUES (?, ?)");
            if ($stmt === false) {
                die('Prepare failed: ' . htmlspecialchars($conn->error));
            }
            $stmt->bind_param("ss", $make_name, $logo_path);
        }
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['save_model'])) {
        $model_id = $_POST['model_id'];
        $make_id = $_POST['make_id'];
        $model_name = $_POST['model_name'];
        $image_path = !empty($_FILES['image_path']['name']) ? 'images/cars/' . basename($_FILES['image_path']['name']) : null;
        if ($image_path) {
            move_uploaded_file($_FILES['image_path']['tmp_name'], $image_path);
        }
        if ($model_id) {
            $stmt = $conn->prepare("UPDATE models SET make_id = ?, model_name = ?, image_path = COALESCE(?, image_path) WHERE model_id = ?");
            if ($stmt === false) {
                die('Prepare failed: ' . htmlspecialchars($conn->error));
            }
            $stmt->bind_param("issi", $make_id, $model_name, $image_path, $model_id);
        } else {
            $stmt = $conn->prepare("INSERT INTO models (make_id, model_name, image_path) VALUES (?, ?, ?)");
            if ($stmt === false) {
                die('Prepare failed: ' . htmlspecialchars($conn->error));
            }
            $stmt->bind_param("iss", $make_id, $model_name, $image_path);
        }
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['save_year'])) {
        $year_id = $_POST['year_id'];
        $model_id = $_POST['model_id'];
        $year = $_POST['year'];
        if ($year_id) {
            $stmt = $conn->prepare("UPDATE years SET model_id = ?, year = ? WHERE year_id = ?");
            if ($stmt === false) {
                die('Prepare failed: ' . htmlspecialchars($conn->error));
            }
            $stmt->bind_param("isi", $model_id, $year, $year_id);
        } else {
            $stmt = $conn->prepare("INSERT INTO years (model_id, year) VALUES (?, ?)");
            if ($stmt === false) {
                die('Prepare failed: ' . htmlspecialchars($conn->error));
            }
            $stmt->bind_param("is", $model_id, $year);
        }
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['save_user'])) {
        $user_id = $_POST['user_id'];
        $username = $_POST['username'];
        $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;
        $role = $_POST['role'];
        if ($user_id) {
            if ($password) {
                $stmt = $conn->prepare("UPDATE users SET username = ?, password = ?, role = ? WHERE user_id = ?");
                if ($stmt === false) {
                    die('Prepare failed: ' . htmlspecialchars($conn->error));
                }
                $stmt->bind_param("sssi", $username, $password, $role, $user_id);
            } else {
                $stmt = $conn->prepare("UPDATE users SET username = ?, role = ? WHERE user_id = ?");
                if ($stmt === false) {
                    die('Prepare failed: ' . htmlspecialchars($conn->error));
                }
                $stmt->bind_param("ssi", $username, $role, $user_id);
            }
        } else {
            $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
            if ($stmt === false) {
                die('Prepare failed: ' . htmlspecialchars($conn->error));
            }
            $stmt->bind_param("sss", $username, $password, $role);
        }
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['save_vehicle_data'])) {
        $data_id = $_POST['data_id'];
        $text = $_POST['text']; // Assuming 'model_id' corresponds to 'text'
        $year_id = $_POST['year_id'];
        $engine_code = $_POST['engine_code'];
        $engine_size = $_POST['engine_size'];
        $power = $_POST['power'];
        $torque = $_POST['torque'];
        $oil_capacity = $_POST['oil_capacity'];
        $html_page_path = $_POST['html_page_path'];
        $ecu_spec_image = !empty($_FILES['ecu_spec_image']['name']) ? 'images/ecu/' . basename($_FILES['ecu_spec_image']['name']) : null;
        if ($ecu_spec_image) {
            move_uploaded_file($_FILES['ecu_spec_image']['tmp_name'], $ecu_spec_image);
        }
        $ecu_spec_reference = $_POST['ecu_spec_reference'];
        $car_parts = $_POST['car_parts'];
        $car_parts_images = $_POST['car_parts_images'];
        $electrical_schema_image = !empty($_FILES['electrical_schema_image']['name']) ? 'images/schema/' . basename($_FILES['electrical_schema_image']['name']) : null;
        if ($electrical_schema_image) {
            move_uploaded_file($_FILES['electrical_schema_image']['tmp_name'], $electrical_schema_image);
        }
        $default_resolutions = $_POST['default_resolutions'];
        $default_images = $_POST['default_images'];
        if ($data_id) {
            $stmt = $conn->prepare("UPDATE vehicle_data SET text = ?, year_id = ?, engine_code = ?, engine_size = ?, power = ?, torque = ?, oil_capacity = ?, html_page_path = ?, ecu_spec_image = COALESCE(?, ecu_spec_image), ecu_spec_reference = ?, car_parts = ?, car_parts_images = ?, electrical_schema_image = COALESCE(?, electrical_schema_image), default_resolutions = ?, default_images = ? WHERE data_id = ?");
            if ($stmt === false) {
                die('Prepare failed: ' . htmlspecialchars($conn->error));
            }
            $stmt->bind_param("iisssssssssssssi", $text, $year_id, $engine_code, $engine_size, $power, $torque, $oil_capacity, $html_page_path, $ecu_spec_image, $ecu_spec_reference, $car_parts, $car_parts_images, $electrical_schema_image, $default_resolutions, $default_images, $data_id);
        } else {
            $stmt = $conn->prepare("INSERT INTO vehicle_data (text, year_id, engine_code, engine_size, power, torque, oil_capacity, html_page_path, ecu_spec_image, ecu_spec_reference, car_parts, car_parts_images, electrical_schema_image, default_resolutions, default_images) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            if ($stmt === false) {
                die('Prepare failed: ' . htmlspecialchars($conn->error));
            }
            $stmt->bind_param("iisssssssssssss", $text, $year_id, $engine_code, $engine_size, $power, $torque, $oil_capacity, $html_page_path, $ecu_spec_image, $ecu_spec_reference, $car_parts, $car_parts_images, $electrical_schema_image, $default_resolutions, $default_images);
        }
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['save_model_version'])) {
        $model_version_id = $_POST['model_version_id'];
        $model_id = $_POST['model_id'];
        $version = $_POST['version'];
        if ($model_version_id) {
            $stmt = $conn->prepare("UPDATE vehicle_data SET model_id = ?, version = ? WHERE model_version_id = ?");
            if ($stmt === false) {
                die('Prepare failed: ' . htmlspecialchars($conn->error));
            }
            $stmt->bind_param("isi", $model_id, $version, $model_version_id);
        } else {
            $stmt = $conn->prepare("INSERT INTO vehicle_data (model_id, version) VALUES (?, ?)");
            if ($stmt === false) {
                die('Prepare failed: ' . htmlspecialchars($conn->error));
            }
            $stmt->bind_param("is", $model_id, $version);
        }
        $stmt->execute();
        $stmt->close();
    }

    ob_end_clean(); // End output buffering and discard buffer contents
    header("Location: admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e0f7fa; /* Updated background color */
            color: #333;
            margin: 0;
            padding: 20px;
        }
        h1 {
            color: #00796b; /* Updated header color */
            text-align: center;
        }
        .logout-button {
            background-color: #ff4c4c;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            display: block;
            margin: 20px auto;
            text-align: center;
        }
        .logout-button:hover {
            background-color: #ff1c1c;
        }
        .menu {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 20px;
        }
        .menu a {
            text-decoration: none;
            color: #00796b; /* Updated link color */
            font-weight: bold;
        }
        .menu a:hover {
            text-decoration: underline;
        }
        .credits {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9em;
            color: #666;
        }
        .form-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #00796b; /* Updated border color */
            border-radius: 5px;
            margin-bottom: 20px;
        }
        label, input, select, textarea {
            display: block;
            width: 100%;
            margin-bottom: 10px;
        }
        input[type="submit"] {
            background-color: #00796b; /* Updated button color */
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #00695c; /* Darker shade on hover */
        }
    </style>
    <script>
        function updateYears() {
            var modelId = document.getElementById('model_id').value;
            var yearSelect = document.getElementById('year_id');
            yearSelect.innerHTML = '';

            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'get_years.php?model_id=' + modelId, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var years = JSON.parse(xhr.responseText);
                    years.forEach(function(year) {
                        var option = document.createElement('option');
                        option.value = year.year_id;
                        option.textContent = year.year;
                        yearSelect.appendChild(option);
                    });
                }
            };
            xhr.send();
        }
    </script>
</head>
<body>
    <div class="menu">
        <a href="index.php">Home</a>
        <a href="https://www.youtube.com" target="_blank">YouTube</a>
        <a href="https://wa.me" target="_blank">WhatsApp</a>
    </div>

    <form action="logout.php" method="post">
        <input type="submit" value="Logout" class="logout-button">
    </form>
    
    <h1>Admin Panel</h1>

    <!-- Add or Update Mark -->
    <div class="form-container">
        <h2>Add or Update Mark</h2>
        <form action="admin.php" method="post" enctype="multipart/form-data">
            <label for="mark_id">Mark ID (leave blank to add new):</label>
            <input type="text" id="mark_id" name="mark_id">
            
            <label for="mark_name">Mark Name:</label>
            <input type="text" id="mark_name" name="mark_name" required>
            
            <label for="mark_logo">Mark Logo Image:</label>
            <input type="file" id="mark_logo" name="mark_logo">
            
            <input type="submit" name="save_mark" value="Save Mark">
        </form>
    </div>

    <!-- Add or Update Model -->
    <div class="form-container">
        <h2>Add or Update Model</h2>
        <form action="admin.php" method="post" enctype="multipart/form-data">
            <label for="model_id">Model ID (leave blank to add new):</label>
            <input type="text" id="model_id" name="model_id">
            
            <label for="mark_id">Select Mark:</label>
            <select id="mark_id" name="mark_id" required>
                <?php
                $sql = "SELECT * FROM marks";
                $result = $conn->query($sql);
                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['mark_id'] . "'>" . $row['mark_name'] . "</option>";
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
            
            <input type="submit" name="save_model" value="Save Model">
        </form>
    </div>

    <!-- Add or Update Year -->
    <div class="form-container">
        <h2>Add or Update Year</h2>
        <form action="admin.php" method="post">
            <label for="year_id">Year ID (leave blank to add new):</label>
            <input type="text" id="year_id" name="year_id">
            
            <label for="model_id">Select Model:</label>
            <select id="model_id" name="model_id" required>
                <?php
                $sql = "SELECT * FROM models";
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
            
            <input type="submit" name="save_year" value="Save Year">
        </form>
    </div>

    <!-- Add or Update User -->
    <div class="form-container">
        <h2>Add or Update User</h2>
        <form action="admin.php" method="post">
            <label for="user_id">User ID (leave blank to add new):</label>
            <input type="text" id="user_id" name="user_id">
            
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password">
            
            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
            
            <input type="submit" name="save_user" value="Save User">
        </form>
    </div>

    <!-- Add or Update Vehicle Data -->
    <div class="form-container">
        <h2>Add or Update Vehicle Data</h2>
        <form action="admin.php" method="post" enctype="multipart/form-data">
            <label for="data_id">Data ID (leave blank to add new):</label>
            <input type="text" id="data_id" name="data_id">
            
            <label for="model_id">Select Model:</label>
            <select id="model_id" name="model_id" required onchange="updateYears()">
                <?php
                $sql = "SELECT * FROM models";
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
            
            <label for="year_id">Select Year:</label>
            <select id="year_id" name="year_id" required>
                <!-- Options will be populated by JavaScript -->
            </select>
            
            <label for="engine_code">Engine Code:</label>
            <input type="text" id="engine_code" name="engine_code">
            
            <label for="engine_size">Engine Size:</label>
            <input type="text" id="engine_size" name="engine_size">
            
            <label for="power">Power:</label>
            <input type="text" id="power" name="power">
            
            <label for="torque">Torque:</label>
            <input type="text" id="torque" name="torque">
            
            <label for="oil_capacity">Oil Capacity:</label>
            <input type="text" id="oil_capacity" name="oil_capacity">
            
            <label for="html_page_path">HTML Page Path:</label>
            <input type="text" id="html_page_path" name="html_page_path">
            
            <label for="ecu_spec_image">ECU Spec Image:</label>
            <input type="file" id="ecu_spec_image" name="ecu_spec_image">
            
            <label for="ecu_spec_reference">ECU Spec Reference:</label>
            <textarea id="ecu_spec_reference" name="ecu_spec_reference"></textarea>
            
            <label for="car_parts">Car Parts (JSON):</label>
            <textarea id="car_parts" name="car_parts"></textarea>
            
            <label for="car_parts_images">Car Parts Images (JSON):</label>
            <textarea id="car_parts_images" name="car_parts_images"></textarea>
            
            <label for="electrical_schema_image">Electrical Schema Image:</label>
            <input type="file" id="electrical_schema_image" name="electrical_schema_image">
            
            <label for="default_resolutions">Default Resolutions (JSON):</label>
            <textarea id="default_resolutions" name="default_resolutions"></textarea>
            
            <label for="default_images">Default Images (JSON):</label>
            <textarea id="default_images" name="default_images"></textarea>
            
            <input type="submit" name="save_vehicle_data" value="Save Vehicle Data">
        </form>
    </div>

    <!-- Add or Update Model Version -->
    <div class="form-container">
        <h2>Add or Update Model Version</h2>
        <form action="admin.php" method="post">
            <label for="model_version_id">Model Version ID (leave blank to add new):</label>
            <input type="text" id="model_version_id" name="model_version_id">
            
            <label for="model_id">Select Model:</label>
            <select id="model_id" name="model_id" required>
                <?php
                $sql = "SELECT * FROM models";
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
            
            <label for="version">Version:</label>
            <input type="text" id="version" name="version" required>
            
            <input type="submit" name="save_model_version" value="Save Model Version">
        </form>
    </div>

    <div class="credits">
        &copy; 2025 Your Company. All rights reserved.
    </div>
</body>
</html>
```

This code includes the functionality for managing model versions, along with the other features of the admin panel. You can replace the content of your `admin.php` file with this code.
