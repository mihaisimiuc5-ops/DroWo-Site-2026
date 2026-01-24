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
    <title>Formular Înscriere</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f7f6; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .container { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        h2 { text-align: center; color: #333; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; font-size: 14px; }
        input, select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background-color: #007bff; color: white; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; }
        button:hover { background-color: #0056b3; }
        .success { color: green; text-align: center; font-weight: bold; }
        .error { color: red; text-align: center; font-weight: bold; }
    </style>
</head>
<body>

<div class="container">
    <h2>Formular Înscriere</h2>
    <?php echo $mesaj; ?>
    <form method="POST" action="">
        <div class="form-group">
            <label>Nume</label>
            <input type="text" name="nume" required>
        </div>
        <div class="form-group">
            <label>Prenume</label>
            <input type="text" name="prenume" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>
        <div class="form-group">
            <label>Număr Telefon</label>
            <input type="tel" name="telefon" required>
        </div>
        <div class="form-group">
            <label>Facultate</label>
            <input type="text" name="facultate" required>
        </div>
        <div class="form-group">
            <label>Anul de studiu</label>
            <select name="an_studiu" required>
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