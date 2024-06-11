<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f9f9f9;
        }
        .success-message {
            background: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 600px;
            width: 100%;
        }
        .success-message h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: #4CAF50;
        }
        .success-message p {
            font-size: 1.2rem;
            margin-bottom: 30px;
            color: #333;
        }
        .btn {
            background: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1rem;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #45a049;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="success-message">
        <h1 class="heading">Pembayaran Berhasil!</h1>
        <p>Terima kasih telah melakukan pembayaran. Pesanan Anda sedang diproses dan akan segera dikirim.</p>
        <a href="indeks.php" class="btn">Kembali ke Beranda</a>
    </div>
</div>

</body>
</html>
