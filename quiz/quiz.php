<?php
session_start();
require 'db.php';

if (!isset($_SESSION['team_id'])) { header("Location: index.php"); exit; }

$team_id = $_SESSION['team_id'];

// Aflam in ce etapa este echipa
$stmt = $pdo->prepare("SELECT current_stage FROM teams WHERE id = ?");
$stmt->execute([$team_id]);
$stage = $stmt->fetchColumn();

if ($stage > 4) {
    echo "<h1>Felicitari! Ai finalizat toate etapele.</h1>";
    echo "<a href='rezultate.php'>Vezi scorul</a>";
    exit;
}

// Luam intrebarile pentru etapa curenta
$stmt = $pdo->prepare("SELECT * FROM questions WHERE stage = ?");
$stmt->execute([$stage]);
$questions = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Etapa <?php echo $stage; ?></title>
</head>
<body>
    <h1>Etapa <?php echo $stage; ?></h1>
    <p>Echipa: <?php echo $_SESSION['team_name']; ?></p>
    
    <h2>Timp ramas: <span id="timerDisplay">05:00</span></h2>
    
    <form id="quizForm" method="POST" action="submit.php">
        <input type="hidden" name="time_left" id="timeLeftInput" value="300">
        
        <?php foreach ($questions as $index => $q): ?>
            <p><strong><?php echo ($index + 1) . ". " . $q['question_text']; ?></strong></p>
            <input type="radio" name="q_<?php echo $q['id']; ?>" value="a" required> a) <?php echo $q['opt_a']; ?><br>
            <input type="radio" name="q_<?php echo $q['id']; ?>" value="b"> b) <?php echo $q['opt_b']; ?><br>
            <input type="radio" name="q_<?php echo $q['id']; ?>" value="c"> c) <?php echo $q['opt_c']; ?><br>
            <input type="radio" name="q_<?php echo $q['id']; ?>" value="d"> d) <?php echo $q['opt_d']; ?><br>
        <?php endforeach; ?>
        
        <br><br>
        <button type="submit">Trimite Raspunsurile</button>
    </form>

    <script>
        // Setam timpul la 5 minute (300 secunde)
        var timeLeft = 300; 
        
        var timerInterval = setInterval(function() {
            timeLeft--;
            
            // Calculam minute si secunde
            var minutes = Math.floor(timeLeft / 60);
            var seconds = timeLeft % 60;
            
            // Formatare adaugand zero in fata daca e sub 10
            if(minutes < 10) minutes = "0" + minutes;
            if(seconds < 10) seconds = "0" + seconds;
            
            document.getElementById("timerDisplay").innerText = minutes + ":" + seconds;
            document.getElementById("timeLeftInput").value = timeLeft;
            
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                alert("Timpul a expirat!");
                // Adaugam niste dummy values ca sa se poata face submit fara erori de validare "required" de la html
                var radios = document.querySelectorAll('input[type="radio"]');
                radios.forEach(r => r.removeAttribute('required'));
                document.getElementById("quizForm").submit();
            }
        }, 1000);
    </script>
</body>
</html>
