<?php

require_once '../db.php';

$mesaj = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nume = htmlspecialchars($_POST['nume']);
    $prenume = htmlspecialchars($_POST['prenume']);
    $email = htmlspecialchars($_POST['email']);
    $telefon = htmlspecialchars($_POST['telefon']);
    $an_studiu = htmlspecialchars($_POST['an_studiu']);
    
    $is_student_upb = isset($_POST['is_student_upb']) && $_POST['is_student_upb'] == '1' ? 1 : 0;
    $facultate = ($is_student_upb) ? htmlspecialchars($_POST['facultate_upb']) : ""; 
    $universitate_externa = (!$is_student_upb) ? htmlspecialchars($_POST['universitate_externa']) : "";

    $a_mai_participat = isset($_POST['a_mai_participat']) && $_POST['a_mai_participat'] == '1' ? 1 : 0;
    $evenimente_anterioare = ($a_mai_participat) ? htmlspecialchars($_POST['evenimente_anterioare']) : "";
    
    $gdpr = isset($_POST['gdpr']) ? 'Da' : 'Nu';

    try {
        $sql = "INSERT INTO candidati (nume, prenume, email, telefon, facultate, an_studiu, is_student_upb, universitate_externa, a_mai_participat, evenimente_anterioare, gdpr) 
                VALUES (:nume, :prenume, :email, :telefon, :facultate, :an_studiu, :is_student_upb, :universitate_externa, :a_mai_participat, :evenimente_anterioare, :gdpr)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nume' => $nume,
            ':prenume' => $prenume,
            ':email' => $email,
            ':telefon' => $telefon,
            ':facultate' => $facultate,
            ':an_studiu' => $an_studiu,
            ':is_student_upb' => $is_student_upb,
            ':universitate_externa' => $universitate_externa,
            ':a_mai_participat' => $a_mai_participat,
            ':evenimente_anterioare' => $evenimente_anterioare,
            ':gdpr' => $gdpr
        ]);
        $mesaj = "<p class='success'>Înscriere realizată cu succes!</p>";
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $mesaj = "<p class='error'>Acest email a fost deja folosit!</p>";
        } else {
            $mesaj = "<p class='error'>A apărut o eroare: " . $e->getMessage() . "</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formular Înscriere Drone WorkShop 2026</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/inscriere.css">
    <link rel="stylesheet" href="css/cursor.css">
    <style>
        .hidden { display: none; }
        .radio-group { display: flex; gap: 15px; margin-bottom: 10px; color: #fff; }
        .radio-group label { font-weight: normal; cursor: pointer; text-transform: none; letter-spacing: normal; text-shadow: none; }
        .sub-label { font-size: 0.9em; color: #ccc; margin-bottom: 5px; display: block; text-transform: none; text-shadow: none; letter-spacing: normal;}
    </style>
</head>
<body>

<div class="container">
    <h2>Formular Înscriere</h2>
    <?php echo $mesaj; ?>
    
    <form method="POST" action="" id="registrationForm">
        <div class="form-group">
            <label>Nume</label>
            <input type="text" name="nume" required placeholder="Ex: Popescu">
        </div>
        <div class="form-group">
            <label>Prenume</label>
            <input type="text" name="prenume" required placeholder="Ex: Andrei">
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required placeholder="Ex: andrei@email.com">
        </div>
        <div class="form-group">
            <label>Număr Telefon</label>
            <input type="tel" name="telefon" required placeholder="Ex: 0712345678">
        </div>
        
        <div class="form-group">
            <label>Ești student la Politehnica București?</label>
            <div class="radio-group">
                <label><input type="radio" name="is_student_upb" value="1" checked onchange="toggleStudentType()"> Da</label>
                <label><input type="radio" name="is_student_upb" value="0" onchange="toggleStudentType()"> Nu</label>
            </div>
        </div>

        <div class="form-group" id="div_facultate_upb">
            <label class="sub-label">Selectează Facultatea</label>
            <select name="facultate_upb" id="input_facultate_upb" required>
                <option value="" disabled selected>Selectează facultatea...</option>
                <option value="Facultatea de Inginerie Electrică">Facultatea de Inginerie Electrică</option>
                <option value="Facultatea de Energetică">Facultatea de Energetică</option>
                <option value="Facultatea de Automatică și Calculatoare">Facultatea de Automatică și Calculatoare</option>
                <option value="Facultatea de Electronică, Telecomunicații și Tehnologia Informației">Facultatea de Electronică, Telecomunicații și Tehnologia Informației</option>
                <option value="Facultatea de Inginerie Mecanică și Mecatronică">Facultatea de Inginerie Mecanică și Mecatronică</option>
                <option value="Facultatea de Ingineria și Managementul Sistemelor Tehnologice">Facultatea de Ingineria și Managementul Sistemelor Tehnologice</option>
                <option value="Facultatea de Ingineria Sistemelor Biotehnice">Facultatea de Ingineria Sistemelor Biotehnice</option>
                <option value="Facultatea de Transporturi din București">Facultatea de Transporturi din București</option>
                <option value="Facultatea de Inginerie Aerospațială">Facultatea de Inginerie Aerospațială</option>
                <option value="Facultatea de Știința și Ingineria Materialelor">Facultatea de Știința și Ingineria Materialelor</option>
                <option value="Facultatea de Chimie Aplicată și Știința Materialelor">Facultatea de Chimie Aplicată și Știința Materialelor</option>
                <option value="Facultatea de Inginerie cu predare în limbi străine">Facultatea de Inginerie cu predare în limbi străine</option>
                <option value="Facultatea de Științe Aplicate">Facultatea de Științe Aplicate</option>
                <option value="Facultatea de Inginerie Medicală">Facultatea de Inginerie Medicală</option>
                <option value="Facultatea de Antreprenoriat, Ingineria și Managementul Afacerilor">Facultatea de Antreprenoriat, Ingineria și Managementul Afacerilor</option>
            </select>
        </div>

        <div class="form-group hidden" id="div_univ_externa">
            <label class="sub-label">La ce universitate și facultate ești?</label>
            <input type="text" name="universitate_externa" id="input_univ_externa" placeholder="Ex: Univ. București - Fac. de Drept">
        </div>

        <div class="form-group">
            <label>Anul de studiu</label>
            <select name="an_studiu" required>
                <option value="" disabled selected>Selectează anul de studiu...</option>
                <option value="Licenta - Anul 1">Licență - Anul 1</option>
                <option value="Licenta - Anul 2">Licență - Anul 2</option>
                <option value="Licenta - Anul 3">Licență - Anul 3</option>
                <option value="Licenta - Anul 4">Licență - Anul 4</option>
                <option value="Masterat - Anul 1">Masterat - Anul 1</option>
                <option value="Masterat - Anul 2">Masterat - Anul 2</option>
            </select>
        </div>

        <div class="form-group">
            <label>Ai mai participat la evenimente EuroAvia?</label>
            <div class="radio-group">
                <label><input type="radio" name="a_mai_participat" value="1" onchange="toggleEuroavia()"> Da</label>
                <label><input type="radio" name="a_mai_participat" value="0" checked onchange="toggleEuroavia()"> Nu</label>
            </div>
        </div>

        <div class="form-group hidden" id="div_evenimente">
            <label class="sub-label">La ce evenimente ai participat?</label>
            <input type="text" name="evenimente_anterioare" id="input_evenimente" placeholder="Ex: Rachetomodelism, Simulator Zbor...">
        </div>

        <div class="form-group gdpr-group">
            <p class="gdpr-text">Acest formular colectează date cu caracter personal. Datele vor fi stocate pe toată perioada funcționării asociației, cu clauze de trecere a informației de la echipă la echipă. Păstrarea datelor furnizate va fi în responsabilitatea departamentelor de IT și Legal.</p>
            <label class="checkbox-label">
                <input type="checkbox" name="gdpr" value="Da" required>
                <span>Am citit și sunt de acord cu prelucrarea datelor furnizate în conformitate cu Regulamentul GDPR.</span>
            </label>
        </div>

        <button type="submit">Înscrie-te</button>
    </form>
</div>

<script src="js/script.js"></script>

</body>
</html>