<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['profile'], ['rh', 'admin'])) {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/RH/BLL_equipas.php';
$equipasBLL = new RHEquipasManager();

$equipa_id = $_GET['id'] ?? null;
if (!$equipa_id) {
    header('Location: equipas.php');
    exit();
}
$equipa = $equipasBLL->getEquipaById($equipa_id);
$coordenadores = $equipasBLL->getCoordenadores();

// Buscar todos os coordenadores de todas as equipas
$equipas_todas = $equipasBLL->getAllEquipas();
$coordenadores_usados = [];
foreach ($equipas_todas as $eq) {
    if (!empty($eq['coordenador_id']) && $eq['id'] != $equipa_id) {
        $coordenadores_usados[] = $eq['coordenador_id'];
    }
}

// Filtrar coordenadores disponíveis (só pode estar numa equipa)
$coordenadores_disponiveis = [];
foreach ($coordenadores as $c) {
    if (!in_array($c['utilizador_id'], $coordenadores_usados) || $equipa['coordenador_id'] == $c['utilizador_id']) {
        $coordenadores_disponiveis[] = $c;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novo_coord = $_POST['coordenador_id'] ?? null;
    if ($novo_coord) {
        $equipasBLL->alterarCoordenador($equipa_id, $novo_coord);
    }
    header("Location: equipas.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Alterar Coordenador da Equipa</title>
    <link rel="stylesheet" href="../../assets/style.css">
    <link rel="stylesheet" href="../../assets/teste.css">
    <style>
        .equipa-coord-container {
            max-width: 500px;
            margin: 40px auto 0 auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.10);
            padding: 36px 32px 32px 32px;
        }
        .equipa-coord-titulo {
            font-size: 1.3rem;
            color: #3a366b;
            margin-bottom: 22px;
            text-align: center;
            font-weight: 700;
        }
        .btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border: none;
            border-radius: 7px;
            padding: 8px 22px;
            font-size: 1rem;
            cursor: pointer;
            margin-right: 8px;
            margin-top: 10px;
            transition: background 0.2s;
            text-decoration: none;
            display: inline-block;
        }
        .btn:hover {
            background: linear-gradient(135deg, #5a67d8 0%, #6b47b6 100%);
        }
    </style>
</head>
<body>
    <header>
        <!-- ...existing code... -->
    </header>
    <div class="equipa-coord-container">
        <div class="equipa-coord-titulo">Alterar Coordenador da Equipa "<?php echo htmlspecialchars($equipa['nome']); ?>"</div>
        <form method="POST">
            <label>Coordenador:
                <select name="coordenador_id" required>
                    <option value="">Selecione</option>
                    <?php foreach ($coordenadores_disponiveis as $c): ?>
                        <option value="<?php echo $c['utilizador_id']; ?>" <?php if ($equipa['coordenador_id'] == $c['utilizador_id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($c['nome']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
            <div style="text-align:center; margin-top: 24px;">
                <button type="submit" class="btn">Guardar</button>
                <a href="equipas.php" class="btn" style="background:#ecebfa;color:#4a468a;">Voltar</a>
            </div>
        </form>
    </div>
</body>
</html>