<?php 
    include("../Model/databaseConnecton.php");
    session_start();

    if(isset($_POST["signUp"])){
        $name = $_POST["name"];
        $email = $_POST["exampleInputEmail1"];
        $password = $_POST["exampleInputPassword1"];
 
        $sql = "INSERT INTO `players`(`id`, `name`, `email`, `password`) VALUES (null,'".$name."','".$email."','".$password."');";
        
        mysqli_query($connection,$sql);

        header("Location: ../View/login.php");
    }
?>