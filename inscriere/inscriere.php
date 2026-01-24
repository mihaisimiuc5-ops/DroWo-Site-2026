<?php

require_once '../db.php';

$mesaj = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nume = htmlspecialchars($_POST['nume']);
    $prenume = htmlspecialchars($_POST['prenume']);
    $email = htmlspecialchars($_POST['email']);
    $telefon = htmlspecialchars($_POST['telefon']);
    $facultate = htmlspecialchars($_POST['facultate']);
    $an_studiu = htmlspecialchars($_POST['an_studiu']);

    try {
        $sql = "INSERT INTO candidati (nume, prenume, email, telefon, facultate, an_studiu) 
                VALUES (:nume, :prenume, :email, :telefon, :facultate, :an_studiu)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nume' => $nume,
            ':prenume' => $prenume,
            ':email' => $email,
            ':telefon' => $telefon,
            ':facultate' => $facultate,
            ':an_studiu' => $an_studiu
        ]);
        $mesaj = "<p class='success'>Înscriere realizată cu succes!</p>";
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $mesaj = "<p class='error'>Acest email a fost deja folosit!</p>";
        } else {
            $mesaj = "<p class='error'>A apărut o eroare. Încearcă din nou.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formular Înscriere EUROAVIA</title>
    <link rel="stylesheet" href="css/inscriere.css">
</head>
<body>

<div class="container">
    <h2>Formular Înscriere</h2>
    <?php echo $mesaj; ?>
    <form method="POST" action="">
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
            <label>Facultate</label>
            <select name="facultate" required>
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
        <button type="submit">Înscrie-te</button>
    </form>
</div>

</body>
</html>