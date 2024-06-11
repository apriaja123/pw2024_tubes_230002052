<?php
include 'config.php';
session_start();

$message = []; // Mendefinisikan array message

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, md5($_POST['password']));

    $select = mysqli_query($conn, "SELECT * FROM user_form WHERE email ='$email' AND password ='$password'") or die('query failed');

    if (mysqli_num_rows($select) > 0) {
        $row = mysqli_fetch_assoc($select);
        $_SESSION['user_id'] = $row['id'];
        header('Location: indeks.php');
        exit();
    } else {
        $message[] = 'Email atau password salah';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/login.css">
    <style>
        .main-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f9f9f9;
            flex-direction: column;
            text-align: center;
        }
        .welcome-message {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 20px;
        }
        .form-container {
            background: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 500px;
            width: 100%;
        }
        .form-container h3 {
            margin-bottom: 20px;
            color: #333;
        }
        .form-container .box {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-container .btn {
            background: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1rem;
            transition: background 0.3s;
        }
        .form-container .btn:hover {
            background: #45a049;
        }
        .form-container p {
            margin-top: 20px;
            color: #333;
        }
        .form-container p a {
            color: #4CAF50;
            text-decoration: none;
        }
        .form-container p a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<?php
if (!empty($message)) {
    foreach ($message as $msg) {
        echo '<div class="message" onclick="this.remove();">' . htmlspecialchars($msg) . '</div>';
    }
}
?>
<div class="main-container">
    <div class="welcome-message">
        Selamat datang di Toko Obat Warung Online
    </div>
    <div class="form-container">
        <h3>Login Sekarang</h3>
        <form action="login.php" method="post">
            <input type="email" name="email" required placeholder="Masukkan email" class="box">
            <input type="password" name="password" required placeholder="Masukkan password" class="box">
            <input type="submit" name="submit" class="btn" value="Login Sekarang">
            <p>Belum punya akun? <a href="register.php">Daftar sekarang</a></p>
        </form>
    </div>
</div>
</body>
</html>
