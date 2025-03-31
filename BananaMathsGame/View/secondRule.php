<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Assest/css/style.css">
    <title>Snake Challenges Rules</title>

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
    </script>
    
</head>
<body id="fullscreenElement">
    <div class="full-screen">
        <img src="../Assest/images/fullscreen.png" alt="" onclick="Fullscreen()">
    </div>
    
    <div class="music">
        <img id="play" src="../Assest/images/sounds.png" alt="" onclick="pauseMusic()">
    </div>

    <div class="container-rule2">
        <div class="rule1-box1 right">
            <h1>Game Rules</h1>

            <div class="rule1-inside hidden">
                <h1>02</h1>
                <p>When you level up, your timer will be decrease. So you need to think fast.</p>
            </div>

            <a href="thirdRule.php"><img src="../Assest/images/banananext.png" alt=""></a>
        </div>
    </div>

    <script src="../Assest/js/script.js"></script>
</body>
</html>