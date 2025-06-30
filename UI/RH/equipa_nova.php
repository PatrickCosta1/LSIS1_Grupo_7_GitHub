<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['profile'], ['rh', 'admin'])) {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/RH/BLL_equipa_nova.php';
$equipasBLL = new EquipaNovaManager();

// Buscar apenas colaboradores que NÃO estão em nenhuma equipa
$coordenadores = $equipasBLL->getCoordenadores();
$colaboradores = $equipasBLL->getColaboradoresSemEquipa();
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $coordenador_id = $_POST['coordenador_id'] ?? null;
    $elementos = $_POST['elementos'] ?? [];
    if ($nome && $coordenador_id) {
        $resultado = $equipasBLL->addEquipa($nome, $coordenador_id, $elementos);
        if ($resultado === true) {
            $success = "Equipa criada com sucesso!";
        } else {
            $error = "Erro ao criar equipa: " . $resultado;
        }
    } else {
        $error = "Preencha todos os campos obrigatórios.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Nova Equipa</title>
    <link rel="stylesheet" href="../../assets/CSS/RH/equipa_nova.css">
    <link rel="stylesheet" href="../../assets/CSS/Comuns/header.css">
</head>
<body>
    <header>
        <img src="../../assets/tlantic-logo2.png" alt="Logo Tlantic" class="logo-header" style="cursor:pointer;" onclick="window.location.href='pagina_inicial_RH.php';">
        <nav>
            <a href="dashboard_rh.php">Dashboard</a>
            <a href="colaboradores_gerir.php">Colaboradores</a>
            <a href="equipas.php">Equipas</a>
            <a href="relatorios.php">Relatórios</a>
            <a href="exportar.php">Exportar</a>
            <a href="../Comuns/notificacoes.php">Notificações</a>
            <a href="../Comuns/perfil.php">Perfil</a>
            <a href="../Comuns/logout.php">Sair</a>
        </nav>
    </header>
    <main>
        <h1>Criar Nova Equipa</h1>
        <?php if ($success): ?><div class="success-message"><?php echo $success; ?></div><?php endif; ?>
        <?php if ($error): ?><div class="error-message"><?php echo $error; ?></div><?php endif; ?>
        <form method="POST" class="ficha-form ficha-form-moderna">
            <div class="ficha-grid">
                <div class="ficha-campo">
                    <label>Nome da Equipa:</label>
                    <input type="text" name="nome" required autocomplete="off">
                </div>
                <div class="ficha-campo">
                    <label>Coordenador:</label>
                    <select name="coordenador_id" required>
                        <option value="">Selecione</option>
                        <?php foreach ($coordenadores as $c): ?>
                            <option value="<?php echo $c['utilizador_id']; ?>"><?php echo htmlspecialchars($c['nome']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="ficha-campo">
                    <label>Elementos da Equipa:</label>
                    <div class="lista-colaboradores" data-count="<?= count($colaboradores) ?>">
                        <?php if (empty($colaboradores)): ?>
                            <span style="color:#888;">Não existem colaboradores disponíveis para adicionar.</span>
                        <?php else: ?>
                            <?php foreach ($colaboradores as $col): ?>
                                <label>
                                    <input type="checkbox" name="elementos[]" value="<?php echo $col['colaborador_id']; ?>">
                                    <?php echo htmlspecialchars($col['nome']); ?>
                                </label>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <small>Selecione os colaboradores que vão pertencer à equipa.<br>
                    <span style="color:#888;">Apenas colaboradores sem equipa aparecem aqui.</span></small>
                </div>
            </div>
            <div style="text-align:center; margin-top: 24px;">
                <button type="submit" class="btn">Criar Equipa</button>
            </div>
        </form>
        <div style="text-align:center; margin-top: 16px;">
            <a href="equipas.php" class="btn">Voltar</a>
        </div>
    </main>
</body>
</html>