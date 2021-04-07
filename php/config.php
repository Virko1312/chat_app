<?php
    $conn = mysqli_connect("localhost", "root", "", "chat");
    if (!$conn) {
        echo "Database is not connected" . mysqli_connect_error();
    }
?>