<?php include 'db_connect.php'; include 'functions.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>موديلات السيارات</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff; /* Light blue background */
            color: #333;
            margin: 0;
            padding: 20px;
            position: relative;
        }
        h1 {
            color: #1e90ff; /* Dodger blue */
            text-align: center;
        }
        .logout-button {
            background-color: #ff4c4c;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            position: absolute;
            top: 20px;
            right: 20px;
        }
        .logout-button:hover {
            background-color: #ff1c1c;
        }
        .models-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .model-item {
            background-color: #ffffff;
            border: 1px solid #1e90ff;
            border-radius: 5px;
            padding: 10px;
            text-align: center;
            transition: background-color 0.3s;
        }
        .model-item:hover {
            background-color: #e0f7ff; /* Light blue on hover */
        }
        .model-item a {
            text-decoration: none;
            color: #1e90ff;
            font-weight: bold;
        }
        .model-item a:hover {
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
        <a href="pinout_airbag.php">Airbag PIN</a>
        <a href="ecu_model_info.php">Ecu PIN</a>
        <a href="https://www.youtube.com" target="_blank">YouTube</a>
        <a href="https://wa.me" target="_blank">WhatsApp</a>
    </div>

    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
        <form action="logout.php" method="post">
            <input type="submit" value="Logout" class="logout-button">
        </form>
    <?php endif; ?>

    <h1>موديلات السيارات</h1>

    <div class="models-grid">
        <?php
        if (isset($_GET['make_id']) && is_numeric($_GET['make_id'])) {
            $make_id = intval($_GET['make_id']);
            $stmt = $conn->prepare("SELECT * FROM models WHERE make_id = ?");
            $stmt->bind_param("i", $make_id);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                echo "<div class='model-item'>";
                displayImage($row["image_path"], $row["model_name"] . " Image", 100);
                echo "<a href='years.php?model_id=" . htmlspecialchars($row['model_id']) . "'>" . htmlspecialchars($row['model_name']) . "</a>";
                echo "</div>";
            }
            $stmt->close();
        } else {
            echo "<p>Invalid make ID.</p>";
        }
        $conn->close();
        ?>
    </div>

    <div class="credits">
        &copy; 2025 Your Company. All rights reserved.
    </div>
</body>
</html>


    