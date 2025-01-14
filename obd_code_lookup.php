<?php
include 'db_connect.php'; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $obd_code = $_POST['obd_code'];

    // Prepare and execute the SQL query
    $stmt = $conn->prepare("SELECT description, moreinfo, imgpath FROM obd_codes WHERE code = ?");
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("s", $obd_code);
    $stmt->execute();
    $stmt->bind_result($description, $moreinfo, $imgpath);
    $stmt->fetch();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>OBD Code Lookup</title>
    <style>
        
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0; /* Light gray background */
            color: #333;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center; /* Center content */
        }
        h1 {
            color: #1e90ff; /* Dodger blue */
            text-align: center;
        }
        
        .results {
            text-align: center;
            margin-top: 20px;
        }
        .results img {
            max-width: 300px;
        }
        form {
            display: flex;
            flex-direction: column;
            align-items: center; /* Center the form elements */
        }
        input[type="text"] {
            width: 300px; /* Increase width */
            height: 40px; /* Increase height */
            padding: 10px; /* Add padding */
            font-size: 16px; /* Increase font size */
            border: 2px solid #1e90ff; /* Border color */
            border-radius: 5px; /* Rounded corners */
            margin-bottom: 10px; /* Space below the input */
        }
        button {
            background-color: #1e90ff; /* Button color */
            color: white; /* Text color */
            padding: 10px 20px; /* Padding */
            border: none; /* Remove border */
            border-radius: 5px; /* Rounded corners */
            font-size: 16px; /* Increase font size */
            cursor: pointer; /* Pointer cursor on hover */
        }
        button:hover {
            background-color: #007acc; /* Darker blue on hover */
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
    <img src="OBD/LOGO.png" alt="OBD Logo" style="max-width: 100%; height: auto; margin-bottom: 20px;">
    <h1>OBD Code Lookup</h1>
    <form method="POST" action="">
        <input type="text" name="obd_code" placeholder="Enter OBD Code" required>
        <button type="submit">Lookup</button>
    </form>

    <?php if (isset($description)): ?>
        <div class="results">
            <h2>Results:</h2>
            <p><strong>Description:</strong> <?php echo $description; ?></p>
            <p><strong>More Info:</strong> <?php echo $moreinfo; ?></p>
            <img src="<?php echo $imgpath; ?>" alt="Image">
        </div>
    <?php endif; ?>
</body>
</html>
