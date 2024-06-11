<?php
include 'config.php';

$message = []; // Define the message array

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, md5($_POST['password']));

    $select = mysqli_query($conn, "SELECT * FROM user_form WHERE email ='$email' AND password ='$password'")
    or die('query failed');

    if (mysqli_num_rows($select) > 0) {
        $message[] = 'User already exists';
    } else {
        mysqli_query($conn, "INSERT INTO user_form (name, email, password) VALUES ('$name', '$email', '$password')") 
        or die('query failed');

        $message[] = 'Registration successful';

        header('location:login.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="css/register.css">
</head>
<body>

<?php
if (!empty($message)) {
    foreach ($message as $msg) {
        echo '<div class="message" onclick="this.remove();">' . $msg . '</div>';
    }
}
?>
    <div class="main-container">
        <div class="form-container">
            <h3>Register now</h3>
            <form action="register.php" method="post">
                <input type="text" name="name" required placeholder="Enter name" class="box">
                <input type="email" name="email" required placeholder="Enter email" class="box">
                <input type="password" name="password" required placeholder="Enter password" class="box">
                <input type="submit" name="submit" class="btn" value="Register Now">
                <p>sudah mempunyai akun? <a href="login.php">Login now</a></p>
            </form>
        </div>
    </div>
</body>
</html>
