<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Adjust this path if your vendor folder is inside the same folder
require __DIR__ . '/../vendor/autoload.php';
include_once('conn.php');

$msg = "";

// Initialize step
if(!isset($_SESSION['step'])) $_SESSION['step'] = 1;

// Step 1: Send OTP
if(isset($_POST['send_otp'])) {
    $email = $_POST['email'];
    $check_email = mysqli_query($connection,"SELECT email FROM form WHERE email='$email'");
    
    if(mysqli_num_rows($check_email) > 0) {
        $otp = rand(100000, 999999);  // 6-digit OTP
        $_SESSION['otp'] = $otp;
        $_SESSION['email'] = $email;
        $_SESSION['step'] = 2;

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = "adityaranacode@gmail.com"; // Gmail
            $mail->Password   = "nguwvzdtildinevg";         // App Password
            $mail->SMTPSecure = "tls";
            $mail->Port       = 587;

            $mail->setFrom("adityaranacode@gmail.com", "Tech Area");
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = "OTP for Password Reset";
            $mail->Body    = "<p>Hello!</p>
                              <p>Your OTP for password reset is: <b>$otp</b></p>
                              <p>If you did not request, ignore this email.</p>";

            $mail->send();
            $msg = "OTP sent to your email!";
        } catch (Exception $e) {
            $msg = "Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        $msg = "Email not found!";
    }
}

// Step 2: Verify OTP and reset password
if(isset($_POST['reset_pwd'])) {
    $user_otp = $_POST['otp'];
    $pwd = $_POST['pwd'];
    $cpwd = $_POST['cpwd'];

    if($user_otp == $_SESSION['otp']) {
        if($pwd == $cpwd) {
            $email = $_SESSION['email'];
            $pwd_hash = md5($pwd);
            $update = mysqli_query($connection, "UPDATE form SET password='$pwd_hash' WHERE email='$email'");
            
            if($update) {
                $msg = "Password updated successfully. <a href='login.php'>Click here to login</a>";
                session_unset(); // clear session
                $_SESSION['step'] = 1; // reset step
            } else {
                $msg = "Error updating password.";
            }
        } else {
            $msg = "Password and Confirm Password do not match.";
        }
    } else {
        $msg = "Invalid OTP!";
    }
}
?>

<html>
<head><title>Forgot / Reset Password</title></head>
<body>
<h3 align="center">Forgot / Reset Password</h3>

<?php if($_SESSION['step'] == 1) { ?>
<form method="post" align="center">
    <label>Email Address</label><br>
    <input type="email" name="email" placeholder="Enter Email" required><br><br>
    <input type="submit" name="send_otp" value="Send OTP"><br><br>
    <p style="color:red;"><?php if(!empty($msg)) echo $msg; ?></p>
</form>

<?php } elseif($_SESSION['step'] == 2) { ?>
<form method="post" align="center">
    <label>OTP</label><br>
    <input type="text" name="otp" placeholder="Enter OTP" required><br><br>

    <label>New Password</label><br>
    <input type="password" name="pwd" placeholder="Enter Password" required><br><br>

    <label>Confirm Password</label><br>
    <input type="password" name="cpwd" placeholder="Confirm Password" required><br><br>

    <input type="submit" name="reset_pwd" value="Reset Password"><br><br>
    <p style="color:red;"><?php if(!empty($msg)) echo $msg; ?></p>
</form>
<?php } ?>
</body>
</html>
