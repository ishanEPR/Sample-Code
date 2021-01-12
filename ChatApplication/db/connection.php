<?php
    function getConnection() {
        $dbhost = "localhost";
        $dbuser = "root";
        $dbpass = "toor";
        $db = "chat_app_db";
       // Create connection
        $conn = mysqli_connect($dbhost, $dbuser, $dbpass,$db);
        // Check connection
        if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
        }
        echo "Connected to database successfully!\n";
        return $conn;
    }
     
    function CloseCon($conn) {
        $conn -> close();
    } 
?>