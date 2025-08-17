<!DOCTYPE html>
<html>
<head>
    <title>Upload Multiple Images with Name</title>
</head>
<body>
    <h2>Upload Multiple Images with Name</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <label>Name:</label>
        <input type="text" name="name" required><br><br>

        <input type="file" name="images[]" multiple required><br><br>
        <button type="submit" name="upload">Upload</button>
    </form>
</body>
</html>

<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "test";

$conn = new mysqli($host, $user, $pass, $db);

if (isset($_POST['upload'])) {
    $name = $_POST['name'];
    $total = count($_FILES['images']['name']);
    $new_images = [];

    // upload new images
    for ($i = 0; $i < $total; $i++) {
        $filename = time() . "_" . $_FILES['images']['name'][$i]; 
        $tmp_name = $_FILES['images']['tmp_name'][$i];
        $target = "uploads/" . $filename;

        if (move_uploaded_file($tmp_name, $target)) {
            $new_images[] = $target;
        }
    }

    $images_str = implode(",", $new_images);

    // check if name already exists
    $check = $conn->query("SELECT * FROM users WHERE name='$name'");
    if ($check->num_rows > 0) {
        $row = $check->fetch_assoc();
        $old_images = $row['images'];

        // merge old + new
        if (!empty($old_images)) {
            $final_images = $old_images . "," . $images_str;
        } else {
            $final_images = $images_str;
        }

        // update record
        $conn->query("UPDATE users SET images='$final_images' WHERE name='$name'");
    } else {
        // insert new record
        $conn->query("INSERT INTO users (name, images) VALUES ('$name', '$images_str')");
    }

    echo "Images saved successfully! <a href='view.php'>View</a>";
}
?>
