<?php
    session_start();
    if(!isset($_SESSION["username"])){
        header("location:login.php");
    }

    $previousScore = isset($_SESSION['previousScore']) ? $_SESSION['previousScore'] : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collect Point</title>
    <link rel="stylesheet" href="../Assest/css/game.css">

    <script>
        function returnToMainGame(){
            let previousScore = sessionStorage.getItem('previousScore');

            window.location.href = 'gameGUI.php?previousScore=' + score;
        }
    </script>
</head>
<body>
    <div class="snake-body">
        <div id="scoreBox">Score: 0</div>
        <div id="hiscoreBox">HiScore: 0</div>
        <div id="board"></div>

        <div id="buttonsSnake" class="score-button">

            <div class="home">
                <a href="startUp.php"><img src="../Assest/images/home.png" alt=""></a>
            </div>

            <div class="quit">
                <a href="./scoreboard.php"><img src="../Assest/images/nextd.png" alt=""></a>
            </div>
        </div>

        <div id="nextbutton" class="playagain">
            <div class="quit">
                <img src="../Assest/images/nextd.png" alt="" onclick="returnToMainGame()">
            </div>
        </div>

        <div id="snakemessage" class="message">
            <h1 id="snakemsg">Game Over</h1>
            <p id="snakeP">You must get score more than 5</p>
        </div>
    </div>
</body>
<script src="../Assest/js/index.js"></script>
</html>
/* Github */
