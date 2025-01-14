<?php include 'db_connect.php'; include 'functions.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>الشركات المصنعة</title>
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
        .makes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .make-item {
            background-color: #ffffff;
            border: 1px solid #1e90ff;
            border-radius: 5px;
            padding: 10px;
            text-align: center;
            transition: background-color 0.3s;
        }
        .make-item:hover {
            background-color: #e0f7ff; /* Light blue on hover */
        }
        .make-item a {
            text-decoration: none;
            color: #1e90ff;
            font-weight: bold;
        }
        .make-item a:hover {
            text-decoration: underline;
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
<div class="header-menu">
    <a href="index.php">Home</a>
    <a href="obd_code_lookup.php">Obd code</a>
    <a href="ecu_model_info.php">Ecu PIN</a>
    <a href="pinout_airbag.php">Airbag PIN</a>
    <a href="fuse_box.php">Fuse Box</a>
    <a href="wiring">wiring diagram</a>
</div>

    <audio id="pageLoadSound" src="snd/mixkit-car-start-ignition-1559.wav" preload="auto"></audio>
    <script>
        window.onload = function() {
            document.getElementById('pageLoadSound').play();
        };
    </script>
<!-- Header Menu -->



    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
        <form action="logout.php" method="post">
            <input type="submit" value="Logout" class="logout-button">
        </form>
    <?php endif; ?>

    <h1>الشركات المصنعة</h1>

    <div class="makes-grid">
        <?php
        $sql = "SELECT * FROM makes";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<div class='make-item'>";
                displayImage($row["logo_path"], $row["make_name"] . " Logo", 100);
                echo "<a href='models.php?make_id=" . htmlspecialchars($row["make_id"]) . "'>" . htmlspecialchars($row["make_name"]) . "</a>";
                echo "</div>";
            }
        } else { echo "لا توجد نتائج"; }
        $conn->close();
        ?>
    </div>

    <div class="credits">
        &copy; 2025 Your Company. All rights reserved.
    </div>
</body>
</html>