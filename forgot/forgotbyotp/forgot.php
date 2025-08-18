<?php
session_start();

// Database connection
$conn = mysqli_connect("localhost", "root", "", "test");
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

if (isset($_POST['submit'])) {
    $email = $_POST['email'];

    // Check if email exists
    $check = mysqli_query($conn, "SELECT * FROM form WHERE email='$email'");
    if (mysqli_num_rows($check) > 0) {

        $otp = rand(100000, 999999);

        // Store OTP in DB
        mysqli_query($conn, "UPDATE form SET otp='$otp' WHERE email='$email'");

        // Send OTP via email
        $subject = "Your OTP Code";
        $message = "Your OTP for password reset is: $otp";
        $headers = "From: adityaranacode@gmail.com";
        mail($email, $subject, $message, $headers);

        $_SESSION['email'] = $email;
        header("Location: verify.php");
        exit();
    } else {
        echo "❌ Email not found!";
    }
}
?>

<form method="POST">
    <h2>Forgot Password</h2>
    <input type="email" name="email" placeholder="Enter your email" required>
    <button type="submit" name="submit">Send OTP</button>
</form>
