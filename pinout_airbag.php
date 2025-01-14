<?php
// Include the database connection file
include 'db_connect.php';

$modelInfo = '';

if (isset($_GET['model_id']) && !empty($_GET['model_id'])) {
    $model_id = $conn->real_escape_string($_GET['model_id']);
    $sql = "SELECT * FROM pinout_airbag WHERE model_id = ?";
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

if (isset($_GET['query'])) {
    $query = $conn->real_escape_string($_GET['query']);
    $sql = "SELECT model_id, model_name FROM pinout_airbag WHERE model_name LIKE ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $likeQuery = "%$query%";
        $stmt->bind_param("s", $likeQuery);
        $stmt->execute();
        $result = $stmt->get_result();
        $suggestions = [];
        while ($row = $result->fetch_assoc()) {
            $suggestions[] = ['model_id' => $row['model_id'], 'model_name' => $row['model_name']];
        }
        echo json_encode($suggestions);
        $stmt->close();
        exit;
    } else {
        echo json_encode([]);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pinout Airbag</title>
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

        select, input[type="text"] {
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

        .suggestions {
            border: 1px solid #ccc;
            border-top: none;
            max-height: 150px;
            overflow-y: auto;
            position: absolute;
            width: calc(100% - 22px);
            z-index: 1000;
            background: #fff;
        }

        .suggestions div {
            padding: 10px;
            cursor: pointer;
        }

        .suggestions div:hover {
            background: #f0f0f0;
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
    <script>
        function filterModels() {
            var input, filter, select, options, i;
            input = document.getElementById("modelSearch");
            filter = input.value.toUpperCase();
            select = document.getElementById("modelSelect");
            options = select.getElementsByTagName("option");
            for (i = 0; i < options.length; i++) {
                if (options[i].text.toUpperCase().indexOf(filter) > -1) {
                    options[i].style.display = "";
                } else {
                    options[i].style.display = "none";
                }
            }
        }

        function showSuggestions() {
            var input = document.getElementById("modelSearch");
            var query = input.value;
            if (query.length > 0) {
                var xhr = new XMLHttpRequest();
                xhr.open("GET", "pinout_airbag.php?query=" + query, true);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        var suggestions = JSON.parse(xhr.responseText);
                        var suggestionsBox = document.getElementById("suggestions");
                        suggestionsBox.innerHTML = "";
                        suggestions.forEach(function(suggestion) {
                            var div = document.createElement("div");
                            div.textContent = suggestion.model_name;
                            div.onclick = function() {
                                document.getElementById("modelSearch").value = suggestion.model_name;
                                document.getElementById("modelSelect").value = suggestion.model_id;
                                document.getElementById("suggestions").innerHTML = "";
                                document.forms[0].submit();
                            };
                            suggestionsBox.appendChild(div);
                        });
                    }
                };
                xhr.send();
            } else {
                document.getElementById("suggestions").innerHTML = "";
            }
        }
    </script>
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
        <h1>Select Model</h1>
        <form method="GET" action="">
            <input type="text" id="modelSearch" onkeyup="showSuggestions()" placeholder="Search for models..">
            <div id="suggestions" class="suggestions"></div>
            <select name="model_id" id="modelSelect" onchange="this.form.submit()">
                <option value="">Select a model</option>
                <?php
                $sql = "SELECT model_id, model_name FROM pinout_airbag";
                $stmt = $conn->prepare($sql);
                if ($stmt) {
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while ($row = $result->fetch_assoc()) {
                        $selected = (isset($_GET['model_id']) && $_GET['model_id'] == $row["model_id"]) ? 'selected' : '';
                        echo '<option value="' . htmlspecialchars($row["model_id"]) . '" ' . $selected . '>' . htmlspecialchars($row["model_name"]) . '</option>';
                    }
                    $stmt->close();
                } else {
                    echo '<option value="">Error preparing statement: ' . htmlspecialchars($conn->error) . '</option>';
                }
                ?>
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