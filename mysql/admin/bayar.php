<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['submit_payment'])) {
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);
    $bank = isset($_POST['bank']) ? mysqli_real_escape_string($conn, $_POST['bank']) : null;
    $ewallet = isset($_POST['ewallet']) ? mysqli_real_escape_string($conn, $_POST['ewallet']) : null;
    $grand_total = mysqli_real_escape_string($conn, $_POST['grand_total']);

    // Simpan detail pembayaran dalam tabel 'payments'
    $query = "INSERT INTO payments (user_id, amount, method, bank, ewallet) VALUES ('$user_id', '$grand_total', '$payment_method', '$bank', '$ewallet')";
    mysqli_query($conn, $query) or die('Query gagal: ' . mysqli_error($conn));

    // Hapus keranjang setelah pembayaran sukses
    mysqli_query($conn, "DELETE FROM pesanan WHERE user_id='$user_id'") or die('Query gagal: ' . mysqli_error($conn));

    $message[] = 'Pembayaran berhasil!';
    header('Location: sukses.php');
    exit();
}

$grand_total = 0;
$pesanan_query = mysqli_query($conn, "SELECT * FROM pesanan WHERE user_id='$user_id'") or die('Query gagal: ' . mysqli_error($conn));
if (mysqli_num_rows($pesanan_query) > 0) {
    while ($fetch_pesanan = mysqli_fetch_assoc($pesanan_query)) {
        $grand_total += $fetch_pesanan['price'] * $fetch_pesanan['quantity'];
    }
} else {
    header('Location: indeks.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bayar</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .payment-form {
            background: #f7f7f7;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }
        .payment-method label {
            display: block;
            margin-bottom: 10px;
        }
        .sub-options {
            margin-left: 20px;
            margin-bottom: 10px;
        }
        .sub-options label {
            display: block;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="payment-form">
        <h1 class="heading">Pilih Metode Pembayaran</h1>

        <?php
        if (!empty($message)) {
            foreach ($message as $msg) {
                echo '<div class="message" onclick="this.remove();">' . htmlspecialchars($msg) . '</div>';
            }
        }
        ?>

        <form action="" method="post">
            <div class="payment-method">
                <label>
                    <input type="radio" name="payment_method" value="Transfer Bank" onclick="showOptions('bank-options')" required>
                    Transfer Bank
                </label>
                <div id="bank-options" class="sub-options" style="display:none;">
                    <label>
                        <input type="radio" name="bank" value="BSI">
                        BSI
                    </label>
                    <label>
                        <input type="radio" name="bank" value="Mandiri">
                        Mandiri
                    </label>
                    <label>
                        <input type="radio" name="bank" value="BCA">
                        BCA
                    </label>
                </div>

                <label>
                    <input type="radio" name="payment_method" value="E-Wallet" onclick="showOptions('ewallet-options')" required>
                    E-Wallet
                </label>
                <div id="ewallet-options" class="sub-options" style="display:none;">
                    <label>
                        <input type="radio" name="ewallet" value="DANA">
                        DANA
                    </label>
                    <label>
                        <input type="radio" name="ewallet" value="Gopay">
                        Gopay
                    </label>
                    <label>
                        <input type="radio" name="ewallet" value="OVO">
                        OVO
                    </label>
                </div>

                <label>
                    <input type="radio" name="payment_method" value="Kartu Kredit" onclick="hideOptions()" required>
                    Kartu Kredit
                </label>
                <label>
                    <input type="radio" name="payment_method" value="COD (Bayar di Tempat)" onclick="hideOptions()" required>
                    COD (Bayar di Tempat)
                </label>
            </div>

            <h3>Total yang harus dibayar: $<?php echo number_format($grand_total, 2); ?></h3>

            <input type="hidden" name="grand_total" value="<?php echo htmlspecialchars($grand_total); ?>">
            <input type="submit" name="submit_payment" value="Bayar Sekarang" class="btn">
        </form>
    </div>
</div>

<script>
function showOptions(id) {
    document.getElementById('bank-options').style.display = 'none';
    document.getElementById('ewallet-options').style.display = 'none';
    if (id === 'bank-options') {
        document.getElementById('bank-options').style.display = 'block';
    } else if (id === 'ewallet-options') {
        document.getElementById('ewallet-options').style.display = 'block';
    }
}

function hideOptions() {
    document.getElementById('bank-options').style.display = 'none';
    document.getElementById('ewallet-options').style.display = 'none';
}
</script>

</body>
</html>
