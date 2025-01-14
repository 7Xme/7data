<?php include 'db_connect.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Years</title>
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
    <h1>Years</h1>

    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
        <form action="logout.php" method="post">
            <input type="submit" value="Logout" class="logout-button">
        </form>
    <?php endif; ?>

    <ul>
        <?php
        if (isset($_GET['model_id']) && is_numeric($_GET['model_id'])) {
            $model_id = intval($_GET['model_id']);
            $stmt = $conn->prepare("SELECT DISTINCT year FROM years WHERE text = ?");

            if ($stmt) { // Check if prepare was successful
                $stmt->bind_param("s", $model_id);
                $stmt->execute();
                $result = $stmt->get_result();
            
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<li><a href='model_versions.php?model_id=" . htmlspecialchars($model_id) . "&year=" . htmlspecialchars($row['year']) . "'>" . htmlspecialchars($row['year']) . "</a></li>";
                    }
                } else {
                    echo "<li>No years found for this model.</li>";
                }
                $stmt->close();
            } else {
                echo "<li>Error preparing statement.</li>";
            }
        } else {
            echo "<li>Invalid model ID.</li>";
        }
        ?>
    </ul>
    <div class="credits">
        &copy; 2025 Your Company. All rights reserved.
    </div>
</body>
</html>