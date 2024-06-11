<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit();
}

$message = []; // Mendefinisikan array message

if (isset($_POST['add_to_pesanan'])) {
    $produk_name = mysqli_real_escape_string($conn, $_POST['produk_name']);
    $produk_price = mysqli_real_escape_string($conn, $_POST['produk_price']);
    $produk_image = mysqli_real_escape_string($conn, $_POST['produk_image']);
    $produk_quantity = mysqli_real_escape_string($conn, $_POST['produk_quantity']);

    $select_pesanan = mysqli_query($conn, "SELECT * FROM pesanan WHERE name='$produk_name' AND user_id='$user_id'") or die('Query gagal: ' . mysqli_error($conn));

    if (mysqli_num_rows($select_pesanan) > 0) {
        $message[] = 'Produk sudah ditambahkan ke pesanan!';
    } else {
        mysqli_query($conn, "INSERT INTO pesanan (user_id, name, price, image, quantity) VALUES ('$user_id', '$produk_name', '$produk_price', '$produk_image', '$produk_quantity')") or die('Query gagal: ' . mysqli_error($conn));
        $message[] = 'Produk ditambahkan ke pesanan!';
    }
}

if (isset($_POST['update_pesanan'])) {
    $pesanan_id = mysqli_real_escape_string($conn, $_POST['pesanan_id']);
    $pesanan_quantity = mysqli_real_escape_string($conn, $_POST['pesanan_quantity']);
    mysqli_query($conn, "UPDATE pesanan SET quantity='$pesanan_quantity' WHERE id='$pesanan_id'") or die('Query gagal: ' . mysqli_error($conn));
    $message[] = 'Pesanan diperbarui!';
}

if (isset($_GET['remove'])) {
    $remove_id = mysqli_real_escape_string($conn, $_GET['remove']);
    mysqli_query($conn, "DELETE FROM pesanan WHERE id='$remove_id'") or die('Query gagal: ' . mysqli_error($conn));
    header('Location: indeks.php');
    exit();
}

if (isset($_GET['delete_all'])) {
    mysqli_query($conn, "DELETE FROM pesanan WHERE user_id='$user_id'") or die('Query gagal: ' . mysqli_error($conn));
    header('Location: indeks.php');
    exit();
}

// Tambahkan logika untuk proses pembayaran
if (isset($_POST['bayar'])) {
    header('Location: bayar.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Obat</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php
if (!empty($message)) {
    foreach ($message as $msg) {
        echo '<div class="message" onclick="this.remove();">' . htmlspecialchars($msg) . '</div>';
    }
}
?>

<div class="container">
    <div class="user-profile">
        <?php 
        $select_user = mysqli_query($conn, "SELECT * FROM user_form WHERE id='$user_id'") or die('Query gagal: ' . mysqli_error($conn));
        if (mysqli_num_rows($select_user) > 0) {
            $fetch_user = mysqli_fetch_assoc($select_user);
        }
        ?>
        <p>Username: <span><?php echo htmlspecialchars($fetch_user['name']); ?></span></p>
        <p>Email: <span><?php echo htmlspecialchars($fetch_user['email']); ?></span></p>

        <div class="flex">
            <a href="login.php" class="btn">Login</a>
            <a href="register.php" class="btn">Register</a>
            <a href="indeks.php?logout=true" onclick="return confirm('Anda yakin ingin logout?')" class="delete-btn">Logout</a>
        </div>

        <div>
            <div class="produk">
                <h1 class="heading">Produk obat warung online</h1>
                <div class="box-container">
                    <?php
                    $select_produk = mysqli_query($conn, "SELECT * FROM produk") or die('Query gagal: ' . mysqli_error($conn));
                    if (mysqli_num_rows($select_produk) > 0) {
                        while ($fetch_produk = mysqli_fetch_assoc($select_produk)) {
                    ?>
                           <form method="post" class="box" action="">
                                <div class="price">$<?php echo htmlspecialchars($fetch_produk['price']); ?></div>
                                <img src="<?php echo htmlspecialchars($fetch_produk['image']); ?>" alt="">
                                <div class="name"><?php echo htmlspecialchars($fetch_produk['name']); ?></div>
                                <input type="number" min="1" name="produk_quantity" value="1">
                                <input type="hidden" name="produk_image" value="<?php echo htmlspecialchars($fetch_produk['image']); ?>">
                                <input type="hidden" name="produk_name" value="<?php echo htmlspecialchars($fetch_produk['name']); ?>">
                                <input type="hidden" name="produk_price" value="<?php echo htmlspecialchars($fetch_produk['price']); ?>">
                                <input type="submit" value="Tambahkan ke Keranjang" name="add_to_pesanan" class="btn">
                            </form>
                    <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="pesanan">
            <h1 class="heading">Keranjang Belanja</h1>
            <table style="width: 100%; border-collapse: collapse;">
                <thead style="background-color: black; color: white;">
                    <tr>
                        <th>Gambar</th>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Total Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $grand_total = 0;
                    $pesanan_query = mysqli_query($conn, "SELECT * FROM pesanan WHERE user_id='$user_id'") or die('Query gagal: ' . mysqli_error($conn));
                    if (mysqli_num_rows($pesanan_query) > 0) {
                        while ($fetch_pesanan = mysqli_fetch_assoc($pesanan_query)) {
                            $sub_total = $fetch_pesanan['price'] * $fetch_pesanan['quantity'];
                    ?>
                            <tr>
                                <td><img src="<?php echo htmlspecialchars($fetch_pesanan['image']); ?>" height="80" alt=""></td>
                                <td><?php echo htmlspecialchars($fetch_pesanan['name']); ?></td>
                                <td>$<?php echo htmlspecialchars($fetch_pesanan['price']); ?></td>
                                <td>
                                    <form action="" method="post">
                                        <input type="hidden" name="pesanan_id" value="<?php echo htmlspecialchars($fetch_pesanan['id']); ?>">
                                        <input type="number" min="1" name="pesanan_quantity" value="<?php echo htmlspecialchars($fetch_pesanan['quantity']); ?>">
                                        <input type="submit" name="update_pesanan" value="Update" class="option-btn">
                                    </form>
                                </td>
                                <td>$<?php echo number_format($sub_total); ?></td>
                                <td><a href="indeks.php?remove=<?php echo $fetch_pesanan['id']; ?>" class="delete-btn" onclick="return confirm('Hapus item dari pesanan?');">Remove</a></td>
                            </tr>
                    <?php
                            $grand_total += $sub_total;
                        }
                    }
                    ?>
                    <tr class="table-bottom">
                        <td colspan="4">Grand Total:</td>
                        <td>$<?php echo number_format($grand_total); ?></td>
                        <td>
                            <a href="indeks.php?delete_all" onclick="return confirm('Hapus semua dari pesanan?');" class="delete-btn">Delete All</a>
                            <form action="" method="post" style="display:inline;">
                                <input type="submit" name="bayar" value="Bayar" class="btn">
                            </form>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
