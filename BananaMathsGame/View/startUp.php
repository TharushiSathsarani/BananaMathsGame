<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Bangers&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../Assest/css/style.css">
    <title>Let's Start</title>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
    // Initialize music object
    const musicSound = new Audio('../Assest/music/music.mp3');
    musicSound.loop = true; // Music loops continuously

    function pauseMusic() {
        let image = document.getElementById('play');

        if (musicSound.paused) {
            musicSound.play();
            image.src = "../Assest/images/pauseImg.png";
        } else {
            musicSound.pause();
            image.src = "../Assest/images/sounds.png";
        }
    }

    function Fullscreen() {
        let elem = document.getElementById("fullscreenElement");

        if (!document.fullscreenElement) {
            if (elem.requestFullscreen) {
                elem.requestFullscreen();
            } else if (elem.webkitRequestFullscreen) { /* Safari */
                elem.webkitRequestFullscreen();
            } else if (elem.msRequestFullscreen) { /* IE11 */
                elem.msRequestFullscreen();
            }
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.webkitExitFullscreen) { /* Safari */
                document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) { /* IE11 */
                document.msExitFullscreen();
            }
        }
    }

    // Attach functions to click events
    document.getElementById("play").addEventListener("click", pauseMusic);
    document.querySelector(".full-screen img").addEventListener("click", Fullscreen);
});

    </script>

</head>
<body id="fullscreenElement">
    <div class="full-screen">
        <img src="../Assest/images/fullscreen.png" alt="" onclick="Fullscreen()">
    </div>

    <div class="music">
        <img id="play" src="../Assest/images/sounds.png" alt="" onclick="pauseMusic()">
    </div>

    <div class="start-up ">
        <div class="monkey">
        </div>

        <div class="wish ">
            
            <h1>BEST OF LUCK</h1>
        </div>
        

        <a href="login.php"><img  src="../Assest/images/pngtree-play-button-of-graphic-user-interface-for-2d-and-3d-games-png-image_3783741-removebg-preview.png" alt=""></a>
    
    </div>

    <script src="../Assest/js/index.js"></script>
</body>
</html>