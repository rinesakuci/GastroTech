<?php
    $server = "localhost";
    $user = "root";
    $password = "";
    $db = "gastro";

    $con = mysqli_connect($server,$user,$password,$db);

    if($con)
    {
    }
    else
    {
        echo mysqli_error($con);
    }
    
?>