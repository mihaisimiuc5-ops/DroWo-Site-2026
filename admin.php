<?php

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
    
    // Refresh pagina
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

?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panou Admin</title>
<link rel="stylesheet" href="inscriere/css/admin.css">
</head>
<body>

<h1>Panou Administrare Candidați</h1>

<div class="sectiune">
    <h2 style="color: #ff9800;">Noi Înscriși (Neverificați) - <?php echo count($neverificati); ?></h2>
    <table>
        <tr>
            <th>Nume și Prenume</th>
            <th>Email</th>
            <th>Telefon</th>
            <th>Facultate/An</th>
            <th>Acțiune</th>
        </tr>
        <?php foreach ($neverificati as $c) : ?>
        <tr>
            <td><?php echo $c['nume'] . ' ' . $c['prenume']; ?></td>
            <td><?php echo $c['email']; ?></td>
            <td><?php echo $c['telefon']; ?></td>
            <td><?php echo $c['facultate'] . ' (' . $c['an_studiu'] . ')'; ?></td>
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
    <h2 style="color: #17a2b8;">Nu au răspuns / În așteptare - <?php echo count($in_asteptare); ?></h2>
    <table>
        <tr>
            <th>Nume și Prenume</th>
            <th>Email</th>
            <th>Telefon</th>
            <th>Facultate/An</th>
            <th>Acțiune</th>
        </tr>
        <?php foreach ($in_asteptare as $c) : ?>
        <tr>
            <td><?php echo $c['nume'] . ' ' . $c['prenume']; ?></td>
            <td><?php echo $c['email']; ?></td>
            <td><?php echo $c['telefon']; ?></td> <td><?php echo $c['facultate'] . ' (' . $c['an_studiu'] . ')'; ?></td>
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
            <th>Nume și Prenume</th>
            <th>Email</th>
            <th>Facultate/An</th>
            <th>Acțiune</th>
        </tr>
        <?php foreach ($confirmati as $c) : ?>
        <tr>
            <td><?php echo $c['nume'] . ' ' . $c['prenume']; ?></td>
            <td><?php echo $c['email']; ?></td>
            <td><?php echo $c['facultate'] . ' (' . $c['an_studiu'] . ')'; ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="id" value="<?php echo $c['id']; ?>">
                    <button type="submit" name="actiune" value="asteapta" class="btn btn-blue">Mută la Nu a răspuns</button>
                    <button type="submit" name="actiune" value="esueaza" class="btn btn-red">Mută la Respinși</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<div class="sectiune">
    <h2 style="color: #dc3545;">Eșuați / Respinși - <?php echo count($esuati); ?></h2>
    <table>
        <tr>
            <th>Nume și Prenume</th>
            <th>Email</th>
            <th>Facultate/An</th>
            <th>Acțiune</th>
        </tr>
        <?php foreach ($esuati as $c) : ?>
        <tr>
            <td><?php echo $c['nume'] . ' ' . $c['prenume']; ?></td>
            <td><?php echo $c['email']; ?></td>
            <td><?php echo $c['facultate'] . ' (' . $c['an_studiu'] . ')'; ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="id" value="<?php echo $c['id']; ?>">
                    <button type="submit" name="actiune" value="confirma" class="btn btn-green">Mută la Confirmați</button>
                    <button type="submit" name="actiune" value="asteapta" class="btn btn-blue">Mută la Nu a răspuns</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

</body>
</html>