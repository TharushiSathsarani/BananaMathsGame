<?php
include("../Model/databaseConnecton.php");
session_start();

if (!isset($_SESSION["username"])) {
    header("location: ../View/login.php");
    exit();
}

$id = $_SESSION["id"];
$name = $_SESSION["name"];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['scorephp'])) {
        $score = $_POST['scorephp'];
        echo "Score: " . $score;

        $sql = "INSERT INTO `score` (`player_id`, `score`) VALUES (?, ?)";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("ii", $id, $score);

        if ($stmt->execute()) {
            echo "Score saved successfully!";
            http_response_code(200);
        } else {
            echo "Error saving score: " . $stmt->error;
            http_response_code(500);
        }

        $stmt->close();
        exit();
    } else {
        http_response_code(400);
        echo "Score not set";
        exit();
    }
} else {
    http_response_code(405);
    echo "Method not allowed";
    exit();
}
