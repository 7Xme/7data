<?php
include 'db_connect.php';

function getHWID() {
    return md5($_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hwid = getHWID();

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (empty($user['hwid'])) {
            $stmt = $conn->prepare("UPDATE users SET hwid = ? WHERE user_id = ?");
            $stmt->bind_param("si", $hwid, $user['user_id']);
            $stmt->execute();
        } elseif ($user['hwid'] !== $hwid) {
            $error = "You are not allowed to log in from this device.";
        } else {
            $_SESSION['loggedin'] = true;
            $_SESSION['role'] = $user['role'];
            header("Location: index.php");
            exit();
        }
    } else {
        $error = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .menu {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 20px;
        }
        .menu a {
            text-decoration: none;
            color: #1e90ff;
            font-weight: bold;
        }
        .menu a:hover {
            text-decoration: underline;
        }
        form {
            max-width: 300px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #1e90ff;
            border-radius: 5px;
        }
        label, input {
            display: block;
            width: 100%;
            margin-bottom: 10px;
        }
        input[type="submit"] {
            background-color: #1e90ff;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #1c86ee;
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
        <a href="https://www.youtube.com" target="_blank">YouTube</a>
        <a href="https://wa.me" target="_blank">WhatsApp</a>
    </div>

    <h1>Login</h1>
    <form action="login.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        
        <input type="submit" value="Login">
        <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
    </form>

    <div class="credits">
        &copy; 2025 Your Company. All rights reserved.
    </div>
</body>
</html>