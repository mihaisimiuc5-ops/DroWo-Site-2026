<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password = $_POST['password'];
    $stmt = $pdo->prepare("SELECT * FROM teams WHERE password = ?");
    $stmt->execute([$password]);
    $team = $stmt->fetch();

    if ($team) {
        $_SESSION['team_id'] = $team['id'];
        $_SESSION['team_name'] = $team['name'];
        header("Location: quiz.php");
        exit;
    } else {
        $error = "Parola incorecta!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login Quiz</title>
</head>
<body>
    <h1>Conectare Echipe</h1>
    <?php if(isset($error)) echo "<p><strong>$error</strong></p>"; ?>
    <form method="POST" action="">
        <label>Parola Echipei:</label><br>
        <input type="password" name="password" required><br><br>
        <button type="submit">Intra in Quiz</button>
    </form>
</body>
</html>
