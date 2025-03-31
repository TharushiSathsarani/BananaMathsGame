let score = 0;
let hiScore = parseInt(document.getElementById('hiscoreBox').innerText.replace("HiScore: ", ""));
let gameOver = false;
const monkey = document.getElementById('monkey');
const scoreBox = document.getElementById('scoreBox');
const gameOverMessage = document.getElementById('gameOverMessage');
const winMessage = document.getElementById('winMessage');
const board = document.getElementById('board');
let monkeyPosition = 50;

// Move Monkey with Arrow Keys
document.addEventListener('keydown', (event) => {
    if (event.key === "ArrowLeft") moveMonkey(-10);
    if (event.key === "ArrowRight") moveMonkey(10);
});

// Move Monkey with Buttons
document.getElementById('leftArrow').addEventListener('click', () => moveMonkey(-10));
document.getElementById('rightArrow').addEventListener('click', () => moveMonkey(10));

function moveMonkey(direction) {
    if (gameOver) return;
    monkeyPosition += direction;
    if (monkeyPosition < 0) monkeyPosition = 0;
    if (monkeyPosition > 90) monkeyPosition = 90;
    monkey.style.left = `${monkeyPosition}%`;
}

// Function to Drop Bananas & Bombs
function dropObject(type) {
    if (gameOver) return;

    const object = document.createElement("div");
    object.classList.add(type);
    object.style.left = `${Math.random() * 90}%`;
    board.appendChild(object);

    let fallInterval = setInterval(() => {
        let top = parseInt(window.getComputedStyle(object).top);
        object.style.top = `${top + 5}px`;

        if (top > 450) {
            clearInterval(fallInterval);
            object.remove();

            // Check Collision
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
    }, 100);
}

// Win Game
function winGame() {
    gameOver = true;
    winMessage.classList.remove('hidden');
    saveHiScore(score);
}

// End Game
function endGame() {
    gameOver = true;
    gameOverMessage.classList.remove('hidden');
    saveHiScore(score);
}

// Save High Score
function saveHiScore(newHiScore) {
    if (newHiScore > hiScore) {
        hiScore = newHiScore;
        fetch('save_score.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `score=${hiScore}`
        });
    }
}

// Drop Objects Every Second
setInterval(() => {
    let type = Math.random() > 0.8 ? "bomb" : "banana";
    dropObject(type);
}, 1000);
