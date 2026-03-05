<?php

session_start();

$parola_admin = "AlexSiDorin2000"; 

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit();
}

$eroare_login = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login_password'])) {
    if ($_POST['login_password'] === $parola_admin) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin.php"); 
        exit();
    } else {
        $eroare_login = "Parolă incorectă!";
    }
}


if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {

?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Panou Admin</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-box { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); text-align: center; width: 300px; }
        .login-box input[type="password"] { width: 90%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px; }
        .login-box button { background: #333; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 4px; width: 100%; }
        .login-box button:hover { background: #555; }
        .error { color: red; margin-bottom: 10px; font-size: 14px; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Autentificare</h2>
        <?php if ($eroare_login): ?>
            <div class="error"><?php echo htmlspecialchars($eroare_login); ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <input type="password" name="login_password" placeholder="Introdu parola" required>
            <button type="submit">Intră în Panou</button>
        </form>
    </div>
</body>
</html>
<?php
    exit(); 
}

require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id']) && isset($_POST['actiune'])) {
    $id = $_POST['id'];
    
    $act_map = [
        'confirma' => 'confirmat',
        'esueaza' => 'esuat',
        'asteapta' => 'in_asteptare'
    ];

    if (array_key_exists($_POST['actiune'], $act_map)) {
        $status_nou = $act_map[$_POST['actiune']];
        $stmt = $pdo->prepare("UPDATE candidati SET status = :status WHERE id = :id");
        $stmt->execute([':status' => $status_nou, ':id' => $id]);
    }
    
    header("Location: admin.php");
    exit();
}

function getCandidatesByStatus($pdo, $status) {
    $stmt = $pdo->prepare("SELECT * FROM candidati WHERE status = :status ORDER BY data_inscriere DESC");
    $stmt->execute([':status' => $status]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$neverificati = getCandidatesByStatus($pdo, 'neverificat');
$in_asteptare = getCandidatesByStatus($pdo, 'in_asteptare');
$confirmati = getCandidatesByStatus($pdo, 'confirmat');
$esuati = getCandidatesByStatus($pdo, 'esuat');

function formatEducatie($c) {
    $html = "";
    if ($c['is_student_upb'] == 1) {
        $html .= "<strong>UPB:</strong> " . htmlspecialchars($c['facultate']);
    } else {
        $html .= "<strong>Extern:</strong> " . htmlspecialchars($c['universitate_externa']);
    }
    $html .= "<br><small>" . htmlspecialchars($c['an_studiu']) . "</small>";
    return $html;
}

function formatEuroavia($c) {
    if ($c['a_mai_participat'] == 1) {
        return "<span style='color:green'>DA</span><br><small>" . htmlspecialchars($c['evenimente_anterioare']) . "</small>";
    }
    return "<span style='color:grey'>NU</span>";
}

function formatMembruEA($c) {
    if (isset($c['membru_euroavia']) && $c['membru_euroavia'] == 1) {
        return "<span style='color:#007bff; font-weight:bold;'>DA</span><br><small>" . htmlspecialchars($c['departament_euroavia']) . "</small>";
    }
    return "<span style='color:grey'>NU</span>";
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panou Admin</title>
    <link rel="stylesheet" href="inscriere/css/admin.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header-top { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #ccc; padding-bottom: 10px; margin-bottom: 20px; }
        .btn-logout { background-color: #dc3545; color: white; padding: 8px 15px; text-decoration: none; border-radius: 4px; font-weight: bold; }
        .btn-logout:hover { background-color: #c82333; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; vertical-align: top; }
        th { background-color: #333; color: white; }
    </style>
</head>
<body>

<div class="header-top">
    <h1>Panou Administrare Candidați</h1>
    <a href="?logout=true" class="btn-logout">Deconectare</a>
</div>

<div class="sectiune">
    <h2 style="color: #ff9800;">Noi Înscriși (Neverificați) - <?php echo count($neverificati); ?></h2>
    <table>
        <tr>
            <th>Nume</th>
            <th>Contact</th>
            <th>Educație</th>
            <th>Exp. EuroAvia</th>
            <th>Membru EA</th>
            <th>Cum a aflat</th>
            <th>Acțiune</th>
        </tr>
        <?php foreach ($neverificati as $c) : ?>
        <tr>
            <td><?php echo htmlspecialchars($c['nume'] . ' ' . $c['prenume']); ?></td>
            <td><?php echo htmlspecialchars($c['email']); ?><br><?php echo htmlspecialchars($c['telefon']); ?></td>
            <td><?php echo formatEducatie($c); ?></td>
            <td><?php echo formatEuroavia($c); ?></td>
            <td><?php echo formatMembruEA($c); ?></td>
            <td><?php echo htmlspecialchars($c['cum_ai_aflat']); ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="id" value="<?php echo $c['id']; ?>">
                    <button type="submit" name="actiune" value="confirma" class="btn btn-green">Confirmă</button>
                    <button type="submit" name="actiune" value="asteapta" class="btn btn-blue">Nu a răspuns</button>
                    <button type="submit" name="actiune" value="esueaza" class="btn btn-red">Respinge</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<div class="sectiune">
    <h2 style="color: #17a2b8;">În așteptare - <?php echo count($in_asteptare); ?></h2>
    <table>
        <tr>
            <th>Nume</th>
            <th>Contact</th>
            <th>Educație</th>
            <th>Exp. EuroAvia</th>
            <th>Membru EA</th>
            <th>Cum a aflat</th>
            <th>Acțiune</th>
        </tr>
        <?php foreach ($in_asteptare as $c) : ?>
        <tr>
            <td><?php echo htmlspecialchars($c['nume'] . ' ' . $c['prenume']); ?></td>
            <td><?php echo htmlspecialchars($c['email']); ?><br><?php echo htmlspecialchars($c['telefon']); ?></td>
            <td><?php echo formatEducatie($c); ?></td>
            <td><?php echo formatEuroavia($c); ?></td>
            <td><?php echo formatMembruEA($c); ?></td>
            <td><?php echo htmlspecialchars($c['cum_ai_aflat']); ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="id" value="<?php echo $c['id']; ?>">
                    <button type="submit" name="actiune" value="confirma" class="btn btn-green">Confirmă</button>
                    <button type="submit" name="actiune" value="esueaza" class="btn btn-red">Respinge</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<div class="sectiune">
    <h2 style="color: #28a745;">Confirmați - <?php echo count($confirmati); ?></h2>
    <table>
        <tr>
            <th>Nume</th>
            <th>Contact</th>
            <th>Educație</th>
            <th>Exp. EuroAvia</th>
            <th>Membru EA</th>
            <th>Cum a aflat</th>
            <th>Acțiune</th>
        </tr>
        <?php foreach ($confirmati as $c) : ?>
        <tr>
            <td><?php echo htmlspecialchars($c['nume'] . ' ' . $c['prenume']); ?></td>
            <td><?php echo htmlspecialchars($c['email']); ?><br><?php echo htmlspecialchars($c['telefon']); ?></td>
            <td><?php echo formatEducatie($c); ?></td>
            <td><?php echo formatEuroavia($c); ?></td>
            <td><?php echo formatMembruEA($c); ?></td>
            <td><?php echo htmlspecialchars($c['cum_ai_aflat']); ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="id" value="<?php echo $c['id']; ?>">
                    <button type="submit" name="actiune" value="asteapta" class="btn btn-blue">Mută la Nu a răspuns</button>
                    <button type="submit" name="actiune" value="esueaza" class="btn btn-red">Respinge</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<div class="sectiune">
    <h2 style="color: #dc3545;">Respinși - <?php echo count($esuati); ?></h2>
    <table>
        <tr>
            <th>Nume</th>
            <th>Contact</th>
            <th>Educație</th>
            <th>Exp. EuroAvia</th>
            <th>Membru EA</th>
            <th>Cum a aflat</th>
            <th>Acțiune</th>
        </tr>
        <?php foreach ($esuati as $c) : ?>
        <tr>
            <td><?php echo htmlspecialchars($c['nume'] . ' ' . $c['prenume']); ?></td>
            <td><?php echo htmlspecialchars($c['email']); ?><br><?php echo htmlspecialchars($c['telefon']); ?></td>
            <td><?php echo formatEducatie($c); ?></td>
            <td><?php echo formatEuroavia($c); ?></td>
            <td><?php echo formatMembruEA($c); ?></td>
            <td><?php echo htmlspecialchars($c['cum_ai_aflat']); ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="id" value="<?php echo $c['id']; ?>">
                    <button type="submit" name="actiune" value="confirma" class="btn btn-green">Reactivează</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

</body>
</html>