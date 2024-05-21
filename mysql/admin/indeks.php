<?php

include 'config.php';

session_start();
$user_id = $_SESSION['user_id']; // Perbaikan typo

if (!isset($user_id)) {
    header('location:login.php');
    exit(); // Tambahkan exit setelah header
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>toko obat</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
<?php
if (isset($message)) {
    foreach ($message as $message) {
        echo '<div class="message" onclick="this.remove();">' . $message . '</div>'; // Perbaikan typo
    }
}

?>

<div class="container">
    <div class="user-profile">

        <?php 
        
        $select_user = mysqli_query($conn, "SELECT * FROM user_form WHERE id='$user_id'") or die('query failed');
        if (mysqli_num_rows($select_user) > 0) {
            $fetch_user = mysqli_fetch_assoc($select_user);
        };
        
        ?>

        <p>username: <span><?php echo $fetch_user['name']; ?></span></p>
        <p>email: <span><?php echo $fetch_user['email']; ?></span></p>

    </div>
</div>

</body>
</html>
