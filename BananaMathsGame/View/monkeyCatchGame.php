<?php
session_start();

// High Score Handling
$highScoreFile = "highscore.txt";
$previousScore = file_exists($highScoreFile) ? file_get_contents($highScoreFile) : 0;

// Handle AJAX Request for Saving Score
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['score'])) {
    $newScore = intval($_POST['score']);   
    if ($newScore > intval($previousScore)) {
        file_put_contents($highScoreFile, $newScore);
        $previousScore = $newScore;
    }
    echo json_encode(["message" => "Score saved!", "score" => $previousScore]);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monkey Catch Game</title>
    <style>
        body {
    text-align: center;
    font-family: Arial, sans-serif;
    background-image: url('../Assest/images/background.jpg');
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    background-repeat: no-repeat;
    background-size: cover;
    height: 100vh;
    overflow: hidden;
    color: white; /* This sets the text color to white */
}


        .game-container {
            position: relative;
            width: 400px;
            height: 500px;
            margin: auto;
            background-color: rgb(85, 230, 119);
            border: 3px solid #333;
            overflow: hidden;
            border-radius: 10px;
        }

        #scoreBox, #hiscoreBox {
            font-size: 20px;
            font-weight: bold;
        }

        #monkey {
            position: absolute;
            bottom: 20px;
            left: 50%;
            width: 60px;
            height: 60px;
            background-image: url('../Assest/images/Monkey1.png');
            background-size: cover;
            transition: left 0.1s;
        }

        .banana, .bomb {
            position: absolute;
            width: 40px;
            height: 40px;
            top: 0;
        }

        .banana {
            background-image: url('../Assest/images/Banana1.png');
            background-size: cover;
        }

        .bomb {
            background-image: url('../Assest/images/Bomb1.png');
            background-size: cover;
        }

        #gameOverMessage, #winMessage {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 24px;
            font-weight: bold;
            color: red;
            display: none;
        }

        .hidden { display: none; }

        .controls {
            margin-top: 10px;
        }

        .controls button {
            font-size: 24px;
            padding: 10px;
            margin: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <h2>Monkey Catch Game</h2>
    <div class="game-container">
        <div id="scoreBox">Score: 0</div>
        <div id="hiscoreBox">HiScore: <?php echo $previousScore; ?></div>
        <div id="monkey"></div>
        <div id="gameOverMessage">Game Over</div>
        <div id="winMessage">You Win!</div>
    </div>

    <!-- Controls -->
    <div class="controls">
        <button id="leftArrow">⬅️</button>
        <button id="rightArrow">➡️</button>
        <button id="pauseButton">Pause</button>
        <button id="playButton" class="hidden">Play</button>
    </div>

    <script>
        let score = 0;
        let hiScore = <?php echo $previousScore; ?>;
        let gameOver = false;
        let gamePaused = false;
        const monkey = document.getElementById('monkey');
        const scoreBox = document.getElementById('scoreBox');
        const gameOverMessage = document.getElementById('gameOverMessage');
        const winMessage = document.getElementById('winMessage');
        const pauseButton = document.getElementById('pauseButton');
        const playButton = document.getElementById('playButton');
        let monkeyPosition = 50; // Percentage position

        // Move Monkey with Arrow Keys
        document.addEventListener('keydown', (event) => {
            if (!gamePaused) {
                if (event.key === "ArrowLeft") moveMonkey(-10);
                if (event.key === "ArrowRight") moveMonkey(10);
            }
        });

        // Move Monkey with Buttons
        document.getElementById('leftArrow').addEventListener('click', () => moveMonkey(-10));
        document.getElementById('rightArrow').addEventListener('click', () => moveMonkey(10));

        // Play/Pause Game
        pauseButton.addEventListener('click', () => {
            gamePaused = true;
            pauseButton.classList.add('hidden');
            playButton.classList.remove('hidden');
        });

        playButton.addEventListener('click', () => {
            gamePaused = false;
            playButton.classList.add('hidden');
            pauseButton.classList.remove('hidden');
        });

        function moveMonkey(direction) {
            if (gameOver || gamePaused) return;
            monkeyPosition += direction;
            if (monkeyPosition < 0) monkeyPosition = 0;
            if (monkeyPosition > 90) monkeyPosition = 90;
            monkey.style.left = `${monkeyPosition}%`;
        }

        // Function to Drop Bananas & Bombs
        function dropObject(type) {
            if (gameOver || gamePaused) return;

            const object = document.createElement("div");
            object.classList.add(type);
            object.style.left = `${Math.random() * 90}%`;
            object.style.top = "0px";
            document.querySelector(".game-container").appendChild(object);

            let fallInterval = setInterval(() => {
                let top = parseInt(window.getComputedStyle(object).top);
                object.style.top = `${top + 5}px`;

                if (top > 450) {
                    clearInterval(fallInterval);
                    checkCollision(object, type);
                    object.remove();
                }
            }, 100);
        }

        // Check Collision
        function checkCollision(object, type) {
            let monkeyLeft = monkey.offsetLeft;
            let monkeyRight = monkeyLeft + monkey.offsetWidth;
            let objectLeft = object.offsetLeft;
            let objectRight = objectLeft + object.offsetWidth;

            if (monkeyLeft < objectRight && monkeyRight > objectLeft) {
                if (type === "banana") {
                    score++;
                    scoreBox.textContent = "Score: " + score;
                    if (score >= 3) {
                        winGame();
                    }
                } else if (type === "bomb") {
                    endGame();
                }
            }
        }

        // Win Game
        function winGame() {
            gameOver = true;
            winMessage.style.display = "block";
            saveHiScore(score);
            setTimeout(() => {
                window.location.href = "gameGUI.php";  // Redirect to gameGUI.php
            }, 2000);  // Redirect after 2 seconds
        }

        // End Game
        function endGame() {
            gameOver = true;
            gameOverMessage.style.display = "block";
            saveHiScore(score);
            setTimeout(() => {
                window.location.href = "gameover.php";  // Redirect to gameover.php
            }, 2000);  // Redirect after 2 seconds
        }

        // Save High Score
        function saveHiScore(newHiScore) {
            if (newHiScore > hiScore) {
                hiScore = newHiScore;
                fetch('', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `score=${hiScore}`
                }).then(response => response.json())
                  .then(data => console.log(data.message));
            }
        }

        // Drop Objects Every Second
        setInterval(() => {
            let type = Math.random() > 0.8 ? "bomb" : "banana";
            dropObject(type);
        }, 1000);
    </script>

</body>
</html>
