<?php
$conn=mysqli_connect("localhost","root","","test");

if(!$conn){
    echo"connection failed".mysqli_connect_error();
}
else{
    echo"succcessfull";
}


?>