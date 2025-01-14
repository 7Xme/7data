<?php
// Database connection
include 'db_connect.php';
?>

<!-- Header Menu -->
<div class="header-menu">
    <a href="index.php">Home</a>
    <a href="obd_code_lookup.php">Obd code</a>
    <a href="ecu_model_info.php">Ecu PIN</a>
    <a href="pinout_airbag.php">Airbag PIN</a>
    <a href="fuse_box.php">Fuse Box</a>
</div>


<style>
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
.brands {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
    margin-bottom: 20px;
}
.brand {
    margin: 10px;
    cursor: pointer;
    text-align: center;
    border: 2px solid #007BFF;
    border-radius: 10px;
    padding: 10px;
    transition: transform 0.2s;
}
.brand:hover {
    transform: scale(1.05);
}
.brand img {
    width: 100px;
    height: auto;
}
.model-details {
    border: 1px solid #007BFF;
    border-radius: 10px;
    padding: 20px;
    background-color: #f0f8ff;
    margin-top: 20px;
}
.descriptions {
    margin-bottom: 20px;
}
.images img {
    width: 150px;
    height: auto;
    margin-right: 10px;
    border: 1px solid #007BFF;
    border-radius: 5px;
}
</style>

<?php
// Fetch unique brands
$query = "SELECT DISTINCT marke_name, marke_image FROM car_fuse_box";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    echo "<h1>Car Brands</h1>";
    echo "<div class='brands'>";

    // Display brands
    while ($row = $result->fetch_assoc()) {
        echo "<div class='brand' onclick='fetchModels(\"" . $row['marke_name'] . "\")'>";
        echo "<img src='" . $row['marke_image'] . "' alt='" . $row['marke_name'] . "' />";
        echo "<p>" . $row['marke_name'] . "</p>";
        echo "</div>";
    }
    echo "</div>";
} else {
    echo "No brands found.";
}

// Fetch models for the selected brand
if (isset($_GET['brand'])) {
    $brand = $_GET['brand'];
    $modelQuery = "SELECT model_name FROM car_fuse_box WHERE marke_name = '$brand'";
    $modelResult = $conn->query($modelQuery);

    if ($modelResult->num_rows > 0) {
        echo "<h2>Models for " . $brand . "</h2>";
        echo "<ul>";
        while ($modelRow = $modelResult->fetch_assoc()) {
            echo "<li onclick='fetchModelDetails(\"" . $modelRow['model_name'] . "\")'>" . $modelRow['model_name'] . "</li>";
        }
        echo "</ul>";
    } else {
        echo "No models found for this brand.";
    }
}

// Fetch details for the selected model
if (isset($_GET['model'])) {
    $model = $_GET['model'];
    $detailQuery = "SELECT * FROM car_fuse_box WHERE model_name = '$model'";
    $detailResult = $conn->query($detailQuery);

    if ($detailResult->num_rows > 0) {
        $detailRow = $detailResult->fetch_assoc();
        echo "<div class='model-details'>";
        echo "<h2>" . $detailRow['model_name'] . "</h2>";
        echo "<div class='descriptions'>";
        echo "<p><strong>Fuse Box Location:</strong> " . $detailRow['description1'] . "</p>";
        echo "<img src='" . $detailRow['image_path1'] . "' alt='" . $detailRow['model_name'] . "' />";
        echo "<p><strong>Fuse box diagram Type 1:</strong> " . $detailRow['description2'] . "</p>";
        echo "<img src='" . $detailRow['image_path2'] . "' alt='" . $detailRow['model_name'] . " - Image 2' />";
        echo "<p><strong>Fuse box diagram Type 2:</strong> " . $detailRow['description3'] . "</p>";
        echo "<img src='" . $detailRow['image_path3'] . "' alt='" . $detailRow['model_name'] . " - Image 3' />";
        echo "</div>";
        echo "<div class='images'>";
        
        
        
        echo "</div>";
        echo "</div>";
    } else {
        echo "No details found for this model.";
    }
}

$conn->close();
?>

<script>
function fetchModels(brand) {
    window.location.href = 'fuse_box.php?brand=' + brand;
}

function fetchModelDetails(model) {
    window.location.href = 'fuse_box.php?model=' + model;
}
</script>

<div class="credits">
        &copy; 2025 Your Company. All rights reserved.
    </div>