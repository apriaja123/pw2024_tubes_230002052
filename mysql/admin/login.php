<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'users_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $message = "Login successful!";
            header("Location: indeks.php");
            exit(); // Pastikan untuk keluar setelah melakukan redirect
        } else {
            $message = "Invalid password!";
        }
    } else {
        $message = "No user found with this email!";
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="main-container">
        <div class="form-container">
            <h3>Login now</h3>
            <form action="login.php" method="post">
                <input type="email" name="email" required placeholder="Enter email" class="box">
                <input type="password" name="password" required placeholder="Enter password" class="box">
                <input type="submit" name="submit" class="btn" value="Login Now">
                <p>tidak mempunyai account? <a href="register.php">register now</a></p>
            </form>
            <?php
            if (!empty($message)) {
                echo '<p class="message">' . $message . '</p>';
            }
            ?>
        </div>
    </div>
</body>
</html>
