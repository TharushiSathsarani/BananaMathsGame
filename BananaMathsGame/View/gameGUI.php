<?php
    include("../Model/databaseConnecton.php");
    session_start();
    if(!isset($_SESSION["username"])){
        header("location:login.php");
    }

    $hearts = ['../Assest/images/heart.png','../Assest/images/heart.png','../Assest/images/heart.png'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Snake Challenges</title>
    <link rel="stylesheet" href="../Assest/css/style.css">
</head>

<body id="fullscreenElement">
    <div class="full-screen">
        <img src="../Assest/images/fullscreen.png" alt="" onclick="Fullscreen()">
    </div>

    <div class="music">
        <img id="play" src="../Assest/images/sounds.png" alt="" onclick="pauseMusic()">
    </div>
    
    <div class="container-GUI">
        <div class="score">
            <div class="score-live">
                <h1>Score : <span id="score">0</span></h1>
            </div>
            <div class="level">
                <h1>Level : <span id="level">1</span></h1>
            </div>
            <div class="hearts">
                <?php
                    for ($i = 0; $i < count($hearts); $i++) {
                        echo '<img src="' . $hearts[$i] . '" alt="Heart" id="heart' . $i . '">';
                    }
                ?>
            </div>
            <div class="timer">
                <h1>Time : <span id="timer">30</span> s</h1>
            </div>
        </div>

        <div class="game">
            <img src="" alt="Questions" id="imgApi">
        </div>

        <div class="answer">
            <label for="answer">Answer : </label>
            <input type="text" style="width: 100px; font-size: 20px !important;" placeholder="Enter" id="answer">
            <button type="button" onclick="handleInput()">Submit</button>  /*compare*/
        </div>

        <div id="note"></div>
    </div>

    <script>
        let timeLeft;
        let score = 0;
        let numQuestions = 1;
        let over = 0;
        let heartsLeft = <?php echo count($hearts); ?>; 
        let level = 1;
        let maxQuestionsPerLevel;
        let solution;
        let timer;

        const musicSound = new Audio('../Assest/music/music.mp3');
        musicSound.pause();

        function pauseMusic() {
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

        function updateUI() {
            document.getElementById("score").textContent = score;
            document.getElementById("timer").textContent = timeLeft;
            document.getElementById("level").textContent = level;
        }

        function playSnakeGame(){
            sessionStorage.setItem('previousScore', score);
            window.location.href = "monkeyCatchGame.php";
        }

        function gameover(){
            if(<?php echo json_encode($_SESSION["chance"]); ?> == 0){
                <?php $_SESSION["chance"] = 1; ?> 
                window.location.href = "monkeyCatchGame.php";
            }

            const xhr = new XMLHttpRequest();
            const url = "../Controllers/save_score.php";

            xhr.open('POST', url, true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onreadystatechange = function(){
                if(xhr.readyState === XMLHttpRequest.DONE){
                    window.location.href = "gameover.php";
                }

                else{
                    console.error('Error saving score:', xhr.statusText);
                }
            };

            xhr.send('scorephp='+score);
        }

        function updateHearts() {
            const heartImages = document.querySelectorAll('.hearts img');
            for (let i = 0; i < heartImages.length; i++) {
                if (i < heartsLeft) {
                    heartImages[i].style.display = 'block';
                } else {
                    heartImages[i].style.display = 'none';
                }
            }
        }

        function handleTimeOut() {
            clearInterval(timer);
            alert("Time's up! Moving on to the next question.");
            numQuestions++;
            over++;
            updateUI();
            if(over >= 3){
                gameover();
            }
            if(heartsLeft > 0) {
                heartsLeft--;
                updateHearts();
            }
            fetchImage();
        }

        function handleInput() {
            let answer = document.getElementById("answer").value;
            if (answer == solution) {
                score++;
                document.getElementById("answer").value = "";
                updateUI();
                numQuestions++;
                if (numQuestions > maxQuestionsPerLevel) {
                    alert(`Great job! You've completed Level ${level}.`);
                    level++;
                    startLevel();
                    return;
                }
                let note = document.getElementById("note");
                note.innerHTML = "Correct Answer: " + solution;
                setTimeout(() => {
                    fetchImage();
                    note.innerHTML = ''; 
                }, 1000);
            } else {
                over++;
                if(over >= 3){
                    gameover();
                }
                if(heartsLeft > 0) {
                    heartsLeft--;
                    updateHearts();
                }
                alert("Incorrect!");
                document.getElementById("answer").value = "";
            }
        }

        function fetchImage() {
            fetch('https://marcconrad.com/uob/banana/api.php')   /* Breo */
                .then(response => response.json())
                .then(data => {
                    let imgApi = data.question;
                    solution = data.solution;
                    document.getElementById("imgApi").src = imgApi;
                    document.getElementById("note").innerHTML = 'Ready?';
                    clearInterval(timer);
                    timeLeft = timePerLevel;
                    timer = setInterval(() => {
                        timeLeft--;
                        document.getElementById("timer").textContent = timeLeft;
                        if (timeLeft <= 0) {
                            handleTimeOut();
                        }
                    }, 1000);
                })
                .catch(error => {
                    console.error('Error fetching image from the API:', error);
                });
        }

        function startLevel() {
            switch(level) {
                case 1:
                    timePerLevel = 30;
                    maxQuestionsPerLevel = 3;
                    heartsLeft = 3;
                    break;
                case 2:
                    timePerLevel = 20;
                    maxQuestionsPerLevel = 3;
                    heartsLeft = 3;
                    break;
                case 3:
                    timePerLevel = 15;
                    maxQuestionsPerLevel = 3;
                    heartsLeft = 3;
                    break;
                default:
                    alert("Congratulations! You have completed all levels.");
                    gameover();
                    return;
            }
            numQuestions = 1;
            over = 0;
            updateHearts();
            updateUI();
            fetchImage();
        }

        let timePerLevel;
        startLevel();
    </script>
</body>
</html>
