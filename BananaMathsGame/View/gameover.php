<?php
    session_start();
    if(!isset($_SESSION["username"])){
        header("location:login.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Assest/css/style.css">
    <title>Snake Challenges</title>
    <script>

        const musicSound = new Audio('../Assest/music/music.mp3');

        musicSound.pause();

        function pauseMusic(){
            musicSound.play();

            var image = document.getElementById('play');
            if (image.src.match("../Assest/images/sounds.png")) {
                
                image.src = "../Assest/images/pauseImg.png";
                image.alt = "";
            } else {
                musicSound.pause();
                image.src = "../Assest/images/sounds.png";
                image.alt = "";
            }
        }

        function Fullscreen() {
            var elem = document.getElementById("fullscreenElement");

            if (!document.fullscreenElement) {
                if (elem.requestFullscreen) {
                    elem.requestFullscreen();
                } else if (elem.webkitRequestFullscreen) { 
                    elem.webkitRequestFullscreen();
                } else if (elem.msRequestFullscreen) { 
                    elem.msRequestFullscreen();
                }
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.webkitExitFullscreen) {
                    document.webkitExitFullscreen();
                } else if (document.msExitFullscreen) { 
                    document.msExitFullscreen();
                }
            }
        }

        function destroySession(){
            var xhr = new XMLHttpRequest();

            xhr.open('GET', '../Controllers/reset_session.php', true);

            xhr.send();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    console.log('Session value reset successfully!');
                    window.location.href = "gameGUI.php";
                }
            };
        }
    </script>
</head>

<body id="fullscreenElement">
    <div class="full-screen">
        <img src="../Assest/images/fullscreen.png" alt="" onclick="Fullscreen()">
    </div>

    <div class="music">
        <img id="play" src="../Assest/images/sounds.png" alt="" onclick="pauseMusic()">
    </div>

    <div class="container-over">
        <div class="gameover-wrap">
            <div class="gemeover-img">
                <img src="../Assest/images/pixel-game-over-text-image-for-assets-vector-38187365-removebg-preview.png" alt="">
            </div>

            <div class="over-button">
                <div class="return">
                   <img src="../Assest/images/retry.png" alt="" onclick="destroySession()">
                </div>

                <div class="home">
                    <img src="../Assest/images/home.png" alt="">
                </div>

                <div class="quit">
                    <a href="scoreboard.php"><img src="../Assest/images/nextd.png" alt=""></a>
                </div>
            </div>
        </div>
    </div>

    <script src="../Assest/js/script.js"></script>
</body>
</html>