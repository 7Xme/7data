
<?php
// Include the database connection file
include 'db_connect.php';

if (isset($_GET['model_id']) && isset($_GET['version_name'])) {
    // Prepare and bind
    $stmt = $conn->prepare("SELECT * FROM vehicle_data WHERE model_id = ? AND version_name = ?");
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("is", $_GET['model_id'], $_GET['version_name']);

    // Execute the statement
    if ($stmt->execute()) {
        // Fetch data and display it
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            ?>
            <!DOCTYPE html>
            <html>
            <head>
                <title>Vehicle Data</title>
                <style>
                    body {
                        font-family: sans-serif;
                        margin: 0;
                        text-align: center;
                    }
                    .container {
                        width: 80%;
                        margin: auto;
                        overflow: hidden;
                    }
                    header {
                        background: #333;
                        color: #fff;
                        padding-top: 30px;
                        min-height: 70px;
                        border-bottom: #77aaff 3px solid;
                    }
                    header a {
                        color: #fff;
                        text-decoration: none;
                        text-transform: uppercase;
                        font-size: 16px;
                    }
                        .menu {
                        display: flex;
                justify-content: space-between; /* changed to space-between for a more balanced layout */
                gap: 20px;
                margin-bottom: 20px;
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
                    .left-aligned-image {
                        float: left;
                        margin-right: 10px;
                    }
                    /* Add more styles as needed */
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
                <div class="container">
                    <header>
                        <h1>Vehicle Data</h1>
                    </header>
                    <div class="content">
                        <?php
                        // Display car image
                        if (!empty($row['carimage'])) {
                            echo "<img src='" . $row['carimage'] . "' alt='Car Image' class='left-aligned-image'><br>";
                        }
                        // Display engine code with styles
                        echo "<p style='color: #FF5733; font-weight: bold;'>Engine Code: " . $row['engine_code'] . "</p>";
                        // Display engine size with styles
                        echo "<p style='color: #33FF57; font-style: italic;'>Engine Size: " . $row['engine_size'] . "</p>";
                        // Display power with styles
                        echo "<p style='color: #3357FF; text-decoration: underline;'>Power: " . $row['power'] . "</p>";
                        // Display torque with styles
                        echo "<p style='color: #FF33A1; font-family: Arial, sans-serif;'>Torque: " . $row['torque'] . "</p>";
                        // Display ECU image
                        
                        // Display car parts and images
                        $car_parts = explode(',', $row['car_parts']);
                        $car_parts_images = explode(',', $row['car_parts_images']);
                        if (count($car_parts) == count($car_parts_images)) {
                            for ($i = 0; $i < count($car_parts); $i++) {
                                echo "<p>--: " . $car_parts[$i] . "</p>";
                                echo "<img src='" . $car_parts_images[$i] . "' alt='Part Image'><br>";
                            }
                        }
                        // Display electrical schema image
                        if (!empty($row['electrical_schema_img'])) {
                            echo "<img src='" . $row['electrical_schema_img'] . "' alt='Electrical Schema Image'><br>";
                        }
                        // Embed HTML page
                        if (!empty($row['html_page_path'])) {
                            echo "<iframe src='" . $row['html_page_path'] . "' width='100%' height='600px'></iframe>";
                        }
                        ?>
                    </div>
                </div>
            </body>
            </html>
            <?php
        } else {
            echo "No data found for the specified model ID and version name.";
        }
    } else {
        echo "Error executing query.";
    }
    $stmt->close();
    $conn->close();
} else {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Vehicle Data</title>
        <style>
            body {
                font-family: sans-serif;
                margin: 0;
                text-align: center;
            }
            .container {
                width: 80%;
                margin: auto;
                overflow: hidden;
            }
            header {
                background: #333;
                color: #fff;
                padding-top: 30px;
                min-height: 70px;
                border-bottom: #77aaff 3px solid;
            }
            header a {
                color: #fff;
                text-decoration: none;
                text-transform: uppercase;
                font-size: 16px;
            }
            /* Add more styles as needed */
        </style>
    </head>
    <body>
        <div class="container">
            <header>
                <h1>Vehicle Data</h1>
            </header>
            <div class="content">
                <p>Invalid model ID or version name.</p>
            </div>
        </div>
    </body>
    </html>
    <?php
}
?>