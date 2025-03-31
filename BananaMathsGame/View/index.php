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
    </script>
</head>
<body id="fullscreenElement">
    <div class="full-screen">
        <img src="../Assest/images/fullscreen.png" alt="" onclick="Fullscreen()">
    </div>
    <div class="music">
        <img id="play" src="../Assest/images/sounds.png" alt="" onclick="pauseMusic()">
    </div>
    <div class="main">
    <div class="board-container">
            <img src="image/mokeyindex.png" alt="" class="monkey-board">
            <h2 class="game-title">MONKEY <br> MATHS</h2>
        

        <h1 class="hidden">CHALLENGES</h1>

        <a href="firstRule.php"><img class="right" src="../Assest/images/banana.png" alt=""></a>
        </div>
    </div>

    <script src="../Assest/js/script.js"></script>
</body>
</html>