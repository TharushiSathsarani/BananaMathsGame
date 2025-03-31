<?php
    include("../Model/databaseConnecton.php");
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
    <title>Leaderboard</title>
    <link rel="stylesheet" href="../Assest/css/style.css">
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

    <div class="score-container">
        <div class="scoreboard-outer">
            <div class="scoreboard-topic">
                <img src="../Assest/images/scoreboard.png" alt="">
            </div>

            <?php
                
                $sql = "SELECT p.name, s.score, s.date FROM score s JOIN players p ON s.player_id = p.id ORDER BY s.score DESC";
                $result = mysqli_query($connection, $sql);


                $positions = ['../Assest/images/firstplace.png', '../Assest/images/secondplace.png', '../Assest/images/thirdplace.png'];
                $positionIndex = 0;

                if(mysqli_num_rows($result) > 0)
                {
                    while($row = mysqli_fetch_array($result))
                    {
	        ?>
                        <div class="score-first">
                            <?php 
                                if ($positionIndex < count($positions)) { ?>
                                    <img src="<?php echo $positions[$positionIndex]; ?>" alt="">
                            <?php 
                                } 
                            ?>
                            <h1><?php echo $row["name"]?></h1>
                            <h1><?php echo $row["score"]?></h1>
                        </div>
            <?php
                    $positionIndex++;
				    }
		        }

	        ?>
        </div>

        <div class="score-button">
                <div class="return">
                   <img src="../Assest/images/retry.png" alt="" onclick="destroySession()">
                </div>

                <div class="home">
                    <img src="../Assest/images/home.png" alt="">
                </div>
        </div>
    </div>
</body>
</html>