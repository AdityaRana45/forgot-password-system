<?php
$conn = new mysqli("localhost", "root", "", "test");

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // check token
    $result = $conn->query("SELECT * FROM form WHERE reset_token='$token'");
    if ($result->num_rows > 0) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $newPass = $_POST['password'];
            $confirmPass = $_POST['confirm_password'];

            if ($newPass === $confirmPass) {
                // md5 hashing
                $hashPass = md5($newPass);

                // update password
                $conn->query("UPDATE form SET password='$hashPass', reset_token=NULL WHERE reset_token='$token'");
                echo "✅ Password reset successful. <a href='login.php'>Login</a>";
            } else {
                echo "❌ Passwords do not match!";
            }
        }
        ?>
        <form method="POST">
            <h2>Reset Password</h2>
            <input type="password" name="password" placeholder="Enter new password" required><br><br>
            <input type="password" name="confirm_password" placeholder="Confirm new password" required><br><br>
            <button type="submit">Reset Password</button>
        </form>
        <?php
    } else {
        echo "❌ Invalid or used token!";
    }
} else {
    echo "❌ No token provided!";
}
?>
