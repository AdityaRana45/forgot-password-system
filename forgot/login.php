<?php
session_start();
include_once('conn.php');

$msg = "";

if(isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = md5($_POST['password']); // MD5 hash

    $check = mysqli_query($connection, "SELECT * FROM form WHERE email='$email' AND password='$password'");

    if(mysqli_num_rows($check) > 0) {
        $_SESSION['user'] = $email;
        $msg = "Login successful! Welcome $email";
        // You can redirect to a dashboard here:
        // header("Location: dashboard.php");
    } else {
        $msg = "Invalid Email or Password!";
    }
}
?>

<html>
<head>
    <title>Login</title>
</head>
<body>
<h3 align="center">Login</h3>

<form method="post" align="center">
    <label>Email</label><br>
    <input type="email" name="email" placeholder="Enter Email" required><br><br>

    <label>Password</label><br>
    <input type="password" name="password" placeholder="Enter Password" required><br><br>

    <input type="submit" name="login" value="Login"><br><br>

    <p style="color:red;"><?php if(!empty($msg)) echo $msg; ?></p>
</form>
</body>
</html>
