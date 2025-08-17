<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "test";

$conn = new mysqli($host, $user, $pass, $db);

$result = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Data</title>
</head>
<body>
    <h2>Users with Images</h2>
    <?php while ($row = $result->fetch_assoc()) { ?>
        <h3><?php echo $row['name']; ?></h3>
        <?php
        $images = explode(",", $row['images']); // convert back to array
        foreach ($images as $img) {
            echo "<img src='$img' width='150' style='margin:5px;'>";
        }
        ?>
        <hr>
    <?php } ?>
</body>
</html>
