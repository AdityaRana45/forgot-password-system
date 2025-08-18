<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $conn = new mysqli("localhost", "root", "", "test");

    // check user
    $result = $conn->query("SELECT * FROM form WHERE email='$email'");
    if ($result->num_rows > 0) {
        $token = bin2hex(random_bytes(16)); // random token

        // token save
        $conn->query("UPDATE form SET reset_token='$token' WHERE email='$email'");

        // reset link
        $resetLink = "http://localhost/reset_password.php?token=" . $token;

        $subject = "Password Reset Link";
        $message = "Click here to reset your password: $resetLink";
        $headers = "From: adityaranacode@gmail.com";

        if (mail($email, $subject, $message, $headers)) {
            echo "Reset link sent to your email.";
        } else {
            echo "Mail sending failed!";
        }
    } else {
        echo "Email not found!";
    }
}
?>

<form method="POST">
    <h2>Forgot Password</h2>
    <input type="email" name="email" placeholder="Enter your email" required>
    <button type="submit">Send Reset Link</button>
</form>
