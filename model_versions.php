<?php
include 'db_connect.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Model Versions</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff; /* Light blue background */
            color: #333;
            margin: 0;
            padding: 20px;
        }
        h1 {
            color: #1e90ff; /* Dodger blue */
            text-align: center;
        }
        ul {
            list-style-type: none;
            padding: 0;
            max-width: 600px;
            margin: 0 auto;
        }
        li {
            background-color: #ffffff;
            border: 1px solid #1e90ff;
            border-radius: 5px;
            margin-bottom: 10px;
            padding: 10px;
            display: flex;
            align-items: center;
            transition: background-color 0.3s;
        }
        li:hover {
            background-color: #e0f7ff; /* Light blue on hover */
        }
        a {
            text-decoration: none;
            color: #1e90ff;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
        .menu {
                        display: flex;
                justify-content: space-between; /* changed to space-between for a more balanced layout */
                gap: 10px;
                margin-bottom: 10px;
                padding: 10px; /* added some padding for a bit of breathing room */
                background-color: #f7f7f7; /* added a light gray background color */
                border-bottom: 1px solid #ddd; /* added a subtle border at the bottom */
            }

            .menu a {
            text-decoration: none;
                color: #1e90ff;
            font-weight: bold;
            transition: color 0.2s ease; /* added a smooth transition effect on hover */
                }

                .menu a:hover {
                text-decoration: underline;
                color: #007bff; /* changed the hover color to a slightly darker blue */
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
    <div class="menu">
        <a href="index.php">Home</a>
        <a href="obd_code_lookup.php">Obd code</a>
        <a href="ecu_model_info.php">Ecu PIN</a>
        <a href="pinout_airbag.php">Airbag PIN</a>
        <a href="https://www.youtube.com" target="_blank">YouTube</a>
        <a href="https://wa.me" target="_blank">WhatsApp</a>
    </div>
    <h1>Model Versions</h1>
    <ul>
        <?php
        
        if (isset($_GET['model_id']) && is_numeric($_GET['model_id']) && isset($_GET['year'])) {
            $model_id = intval($_GET['model_id']);
            $year = $_GET['year']; // Keep year as a string
            $stmt = $conn->prepare("SELECT version_name_2, version_name_3, version_name_4, version_name_5, version_name_6, version_name_7, version_name_8, version_name_9, version_name_10, version_name_11, version_name_12, version_name_13, version_name_14, version_name_15, version_name_16, version_name_17, version_name_18, version_name_19, version_name_20 FROM model_versions WHERE model_id = ? AND year = ?");
            $stmt->bind_param("is", $model_id, $year); // Bind year as a string
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                for ($i = 2; $i <= 20; $i++) {
                    $version_name = $row["version_name_$i"];
                    if (!empty($version_name)) {
                        echo "<li><a href='vehicle_data.php?model_id=$model_id&version_name=" . urlencode($version_name) . "'>" . htmlspecialchars($version_name) . "</a></li>";
                    }
                }
            } else {
                echo "<li>No versions found for this model and year.</li>";
            }
            $stmt->close();
        } else {
            echo "<li>Invalid model ID or year.</li>";
        }
        ?>
        </ul>
        <div class="credits">
            &copy; 2025 Your Company. All rights reserved.
        </div>
        </body>
        </html>