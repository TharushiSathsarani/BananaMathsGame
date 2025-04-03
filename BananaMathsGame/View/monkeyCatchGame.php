<?php
session_start();

// High Score Handling with error checking
$highScoreFile = "highscore.txt";
$previousScore = 0; // Default value

if (file_exists($highScoreFile)) {
    $content = file_get_contents($highScoreFile);
    if ($content !== false && is_numeric($content)) {
        $previousScore = intval($content);
    }
}

// Handle AJAX Request for Saving Score
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['score'])) {
    $newScore = intval($_POST['score']);   
    if ($newScore > $previousScore) {
        if (file_put_contents($highScoreFile, $newScore) === false) {
            error_log("Failed to save high score");
        }
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
    <!-- Preload background images -->
    <link rel="preload" href="../Assest/images/dayjungle.jpg" as="image">
    <link rel="preload" href="../Assest/images/nightjungle.jpg" as="image">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --day-bg: url('../Assest/images/dayjungle.jpg');
            --night-bg: url('../Assest/images/nightjungle.jpg');
            --primary-color: #FF9F1C;
            --secondary-color: #2EC4B6;
            --danger-color: #E71D36;
            --text-color: #011627;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-size: cover;
            background-position: center;
            height: 100vh;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            color: var(--text-color);
            overflow: hidden;
            /* Default to day mode */
            background-image: var(--day-bg);
            transition: background-image 0.5s ease;
        }

        /* Night Mode */
        body.night {
            background-image: var(--night-bg);
            color: #fff;
            text-shadow: 0 0 5px rgba(0,0,0,0.7);
        }

        .header {
            padding: 15px 0;
            text-align: center;
            flex-shrink: 0;
        }

        h2 {
            font-size: 2rem;
            margin: 0;
            color: inherit;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .main-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex-grow: 1;
            width: 100%;
            padding: 10px;
            overflow: hidden;
        }

        .score-display {
            display: flex;
            justify-content: space-between;
            width: 100%;
            max-width: 500px;
            padding: 10px 20px;
            background-color: rgba(255, 136, 0, 0.8);
            border-radius: 10px;
            margin-bottom: 15px;
            font-size: 1.2rem;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .score-display span {
            color: #e74c3c;
            font-size: 1.3rem;
        }

        .game-container {
            position: relative;
            width: 100%;
            max-width: 500px;
            height: 60vh;
            min-height: 400px;
            background-color: rgba(46, 196, 181, 0.55);
            border: 4px solid #011627;
            overflow: hidden;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            backdrop-filter: blur(2px);
        }

        #monkey {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 80px;
            background-image: url('../Assest/images/Monkey2.png');
            background-size: contain;
            background-repeat: no-repeat;
            transition: left 0.1s ease-out;
            z-index: 10;
        }

        .banana, .bomb {
            position: absolute;
            width: 60px;
            height: 60px;
            top: -60px;
            background-size: contain;
            background-repeat: no-repeat;
            transition: transform 0.1s;
            z-index: 5;
        }

        .banana {
            background-image: url('../Assest/images/Banana2.png');
            animation: swing 2s infinite ease-in-out;
        }

        .bomb {
            background-image: url('../Assest/images/bomb2.png');
            animation: rotate 3s infinite linear;
        }

        @keyframes swing {
            0%, 100% { transform: rotate(-10deg); }
            50% { transform: rotate(10deg); }
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        #gameOverMessage, #winMessage {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--danger-color);
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
            display: none;
            z-index: 20;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            text-align: center;
            width: 90%;
            max-width: 400px;
        }

        #winMessage {
            color: var(--secondary-color);
        }

        .hidden { 
            display: none; 
        }

        .controls {
            display: flex;
            justify-content: center;
            gap: 15px;
            padding: 15px;
            width: 100%;
            flex-shrink: 0;
            background-color: rgba(0,0,0,0.1);
        }

        .controls button {
            font-size: 1.5rem;
            padding: 12px 25px;
            margin: 0;
            cursor: pointer;
            border-radius: 12px;
            border: none;
            background-color: var(--primary-color);
            color: white;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            transition: all 0.2s ease;
            flex: 1;
            max-width: 150px;
        }

        .controls button:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.3);
        }

        .controls button:active {
            transform: translateY(1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        @media (max-width: 600px) {
            h2 {
                font-size: 1.8rem;
            }
            
            .game-container {
                height: 55vh;
                min-height: 350px;
            }
            
            #monkey {
                width: 70px;
                height: 70px;
            }
            
            .banana, .bomb {
                width: 50px;
                height: 50px;
            }
            
            .controls button {
                font-size: 1.3rem;
                padding: 10px 15px;
            }
            
            #gameOverMessage, #winMessage {
                font-size: 2rem;
                padding: 15px;
            }
        }

        @media (max-height: 700px) {
            .game-container {
                height: 50vh;
            }
            
            .header {
                padding: 10px 0;
            }
            
            h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body id="game-body">
    <div class="header">
        <h2>Monkey Catch Game</h2>
    </div>
    
    <div class="main-content">
        <div class="score-display">
            <div id="scoreBox">Score: <span>0</span></div>
            <div id="hiscoreBox">High Score: <span><?php echo htmlspecialchars($previousScore); ?></span></div>
        </div>
        
        <div class="game-container">
            <div id="monkey"></div>
            <div id="gameOverMessage">Game Over</div>
            <div id="winMessage">You Win!</div>
        </div>
    </div>

    <div class="controls">
        <button id="leftArrow">⬅️</button>
        <button id="pauseButton">Pause</button>
        <button id="rightArrow">➡️</button>
        <button id="playButton" class="hidden">Play</button>
    </div>

    <script>
        let score = 0;
        let hiScore = <?php echo $previousScore; ?>;
        let gameOver = false;
        let gamePaused = false;
        let monkeyPosition = 50; // Percentage position
        const monkey = document.getElementById('monkey');
        const scoreBox = document.getElementById('scoreBox');
        const gameOverMessage = document.getElementById('gameOverMessage');
        const winMessage = document.getElementById('winMessage');
        const pauseButton = document.getElementById('pauseButton');
        const playButton = document.getElementById('playButton');
        const gameBody = document.getElementById('game-body');

        // Set initial mode based on local time immediately
        const hour = new Date().getHours();
        gameBody.className = hour >= 6 && hour < 18 ? 'day' : 'night';

        // Then check with API for more accuracy
        function updateTimeFromAPI() {
            fetch('https://timeapi.io/api/Time/current/zone?timeZone=Asia/Colombo')
                .then(response => response.json())
                .then(data => {
                    const apiHour = new Date(data.dateTime).getHours();
                    const correctMode = apiHour >= 6 && apiHour < 18 ? 'day' : 'night';
                    if (!gameBody.classList.contains(correctMode)) {
                        gameBody.className = correctMode;
                    }
                })
                .catch(error => {
                    console.error("TimeAPI failed, using local time:", error);
                });
        }

        // Call the API check
        updateTimeFromAPI();

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
            document.querySelector(".game-container").appendChild(object);

            let fallInterval = setInterval(() => {
                if (gamePaused) return;
                
                let top = parseInt(object.style.top) || 0;
                object.style.top = `${top + 5}px`;

                // Check collision
                checkCollision(object, type);

                if (top > 450) {
                    clearInterval(fallInterval);
                    object.remove();
                }
            }, 100);
        }

        // Check Collision
        function checkCollision(object, type) {
            if (gameOver) return;
            
            const monkeyRect = monkey.getBoundingClientRect();
            const objectRect = object.getBoundingClientRect();
            
            if (
                monkeyRect.left < objectRect.right &&
                monkeyRect.right > objectRect.left &&
                monkeyRect.top < objectRect.bottom &&
                monkeyRect.bottom > objectRect.top
            ) {
                if (type === "banana") {
                    score++;
                    scoreBox.textContent = "Score: " + score;
                    object.remove();
                    
                    if (score >= 3) {
                        winGame();
                    }
                } else if (type === "bomb") {
                    object.remove();
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
                window.location.href = "gameGUI.php";
            }, 2000);
        }

        // End Game
        function endGame() {
            gameOver = true;
            gameOverMessage.style.display = "block";
            saveHiScore(score);
            setTimeout(() => {
                window.location.href = "gameover.php";
            }, 2000);
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
                  .then(data => {
                    document.getElementById('hiscoreBox').textContent = `HiScore: ${data.score}`;
                });
            }
        }

        // Drop objects periodically
        setInterval(() => {
            if (!gamePaused && !gameOver) {
                let rand = Math.random();
                if (rand < 0.8) {
                    dropObject("banana");
                } else {
                    dropObject("bomb");
                }
            }
        }, 1000);
    </script>
</body>
</html>