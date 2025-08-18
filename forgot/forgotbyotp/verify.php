<?php
session_start();

// Database connection
$conn = mysqli_connect("localhost", "root", "", "test");
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

if (!isset($_SESSION['email'])) {
    header("Location: forgot.php");
    exit();
}
$email = $_SESSION['email'];

// Step 1: Verify OTP
if (isset($_POST['verify_otp'])) {
    $otp = $_POST['otp'];

    $result = mysqli_query($conn, "SELECT * FROM form WHERE email='$email' AND otp='$otp'");
    if (mysqli_num_rows($result) > 0) {
        $_SESSION['otp_verified'] = true;
        echo "<script>alert('✅ OTP Verified! Please set new password.');</script>";
    } else {
        echo "❌ Invalid OTP!";
    }
}

// Step 2: Reset Password
if (isset($_POST['reset_password'])) {
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if ($password === $confirm) {
        $hash_pass = md5($password); // Tumne bola MD5 me chahiye

        mysqli_query($conn, "UPDATE form SET password='$hash_pass', otp=NULL WHERE email='$email'");

        session_destroy();
        echo "✅ Password Reset Successfully! <a href='login.php'>Login</a>";
        exit();
    } else {
        echo "❌ Passwords do not match!";
    }
}
?>

<h2>Verify OTP</h2>
<form method="POST">
    <input type="text" name="otp" placeholder="Enter OTP" required>
    <button type="submit" name="verify_otp">Verify</button>
</form>

<?php if (isset($_SESSION['otp_verified']) && $_SESSION['otp_verified'] === true): ?>
    <h2>Reset Password</h2>
    <form method="POST">
        <input type="password" name="password" placeholder="New Password" required><br><br>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required><br><br>
        <button type="submit" name="reset_password">Reset Password</button>
    </form>
<?php endif; ?>
