<?php 
    include("../Model/databaseConnecton.php");
    session_start();

    if(isset($_POST["login"])){
        $email = $_POST["exampleInputEmail1"];
        $password = $_POST["exampleInputPassword11"];

        $sql = "Select * From `players` Where `email` = '".$email."' and `password` = '".$password."'";

        $result = mysqli_query($connection,$sql);

        if(mysqli_num_rows($result) > 0){
            $query = "SELECT id, name, email FROM `players` WHERE `email` = '$email' AND `password` = '$password'";
            $result2 = $connection->query($query);

            if(mysqli_num_rows($result2) > 0){

                $row = mysqli_fetch_assoc($result2);

                $id = $row["id"];
                $name = $row["name"];
                $_SESSION["id"] = $id;
                $_SESSION["name"] = $name;
                $_SESSION["username"] = $email;
                $_SESSION["chance"] = 0;
                header("Location: ../View/lodingpage.php");
                exit();
            }

            else{
                echo "No result found";
            }
        }
        else{
            header("Location: ../View/register.php");
        }
        
    }
?>