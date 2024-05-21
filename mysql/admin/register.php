<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'users_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $message = "Email already exists!";
    } else {
        $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
        if ($conn->query($sql) === TRUE) {
            $message = "Registration successful! Please login.";
            header("Location: login.php");
            exit(); // Ensure to exit after the redirect
        } else {
            $message = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="main-container">
        <div class="form-container">
            <h3>Register now</h3>
            <form action="register.php" method="post">
                <input type="text" name="name" required placeholder="Enter name" class="box">
                <input type="email" name="email" required placeholder="Enter email" class="box">
                <input type="password" name="password" required placeholder="Enter password" class="box">
                <input type="submit" name="submit" class="btn" value="Register Now">
                <p>Already have an account? <a href="login.php">Login now</a></p>
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
