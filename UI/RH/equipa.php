<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['profile'] !== 'rh') {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/RH/BLL_equipa_editar.php';

$equipaId = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($equipaId <= 0) {
    header('Location: equipas.php');
    exit();
}

$equipasBLL = new RHEquipaEditarManager(); // Use a classe da BLL de editar equipa
$equipa = $equipasBLL->getEquipaById($equipaId);
if (!$equipa) {
    header('Location: equipas.php');
    exit();
}

$coordenadores = $equipasBLL->getCoordenadoresDisponiveis($equipaId);
$colaboradoresFora = $equipasBLL->getColaboradoresSemEquipaSoColaboradores();
$colaboradoresEquipa = $equipasBLL->getColaboradoresDaEquipa($equipaId);

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['remover_colab_id'])) {
        $colabId = intval($_POST['remover_colab_id']);
        $ok = $equipasBLL->removerColaboradorDaEquipa($equipaId, $colabId);
        if ($ok) {
            $success = "Colaborador removido da equipa.";
        } else {
            $error = "Erro ao remover colaborador.";
        }
    }
    if (isset($_POST['adicionar_colab_id'])) {
        $colabId = intval($_POST['adicionar_colab_id']);
        $ok = $equipasBLL->adicionarColaboradorAEquipa($equipaId, $colabId);
        if ($ok) {
            $success = "Colaborador adicionado à equipa.";
        } else {
            $error = "Erro ao adicionar colaborador.";
        }
    }
    if (isset($_POST['nome']) && isset($_POST['coordenador_id'])) {
        $novoNome = trim($_POST['nome']);
        $novoCoord = intval($_POST['coordenador_id']);
        $ok = $equipasBLL->atualizarNomeCoordenador($equipaId, $novoNome, $novoCoord);
        if ($ok) {
            $success = "Equipa atualizada com sucesso!";
        } else {
            $error = "Erro ao atualizar equipa.";
        }
    }
    if (isset($_POST['remover_equipa'])) {
        if ($equipasBLL->removerEquipa($equipaId)) {
            header('Location: equipas.php?removida=1');
            exit();
        } else {
            $error = "Erro ao remover a equipa.";
        }
    }
    $equipa = $equipasBLL->getEquipaById($equipaId);
    $colaboradoresEquipa = $equipasBLL->getColaboradoresDaEquipa($equipaId);
    $colaboradoresFora = $equipasBLL->getColaboradoresSemEquipaSoColaboradores();
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Editar Equipa</title>
    <link rel="stylesheet" href="../../assets/CSS/RH/equipa_editar.css">
</head>
<body>
<header>
    <img src="../../assets/tlantic-logo2.png" alt="Logo Tlantic" class="logo-header" style="cursor:pointer;" onclick="window.location.href='equipas.php';">
    <nav>
        <a href="dashboard_rh.php">Dashboard</a>
        <a href="colaboradores_gerir.php">Colaboradores</a>
        <a href="equipas.php" class="active">Equipas</a>
        <a href="relatorios.php">Relatórios</a>
        <a href="exportar.php">Exportar</a>
        <a href="../Comuns/notificacoes.php">Notificações</a>
        <a href="../Comuns/perfil.php">Perfil</a>
        <a href="../Comuns/logout.php">Sair</a>
    </nav>
</header>
<main>
    <h1>Editar Equipa</h1>
    <?php if ($success): ?><div class="success-message"><?= $success ?></div><?php endif; ?>
    <?php if ($error): ?><div class="error-message"><?= $error ?></div><?php endif; ?>

    <form class="ficha-form-moderna" method="POST" style="margin-bottom:32px;">
        <div class="ficha-grid">
            <div class="ficha-campo">
                <label for="nome">Nome da Equipa:</label>
                <input type="text" name="nome" id="nome" value="<?= htmlspecialchars($equipa['nome']) ?>" required>
            </div>
            <div class="ficha-campo">
                <label for="coordenador_id">Coordenador:</label>
                <select name="coordenador_id" id="coordenador_id" required>
                    <?php foreach ($coordenadores as $coord): ?>
                        <option value="<?= $coord['id'] ?>" <?= $coord['id'] == $equipa['coordenador_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($coord['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="perfil-edit-actions" style="margin-top:24px;">
            <button type="submit" class="btn">Guardar Alterações</button>
            <button type="submit" name="remover_equipa" class="btn" style="background:linear-gradient(135deg,#e53e3e 0%,#c53030 100%);color:#fff;margin-left:16px;" onclick="return confirm('Tem a certeza que deseja remover esta equipa?');">Remover Equipa</button>
        </div>
    </form>

    <div class="tabelas-colabs-wrapper">
        <div class="tabela-colab-box">
            <h2>Colaboradores na Equipa</h2>
            <table class="tabela-colabs">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($colaboradoresEquipa)): ?>
                        <tr><td colspan="2" style="color:#888;">Nenhum colaborador nesta equipa.</td></tr>
                    <?php else: ?>
                        <?php foreach ($colaboradoresEquipa as $colab): ?>
                            <tr>
                                <td><?= htmlspecialchars($colab['nome']) ?></td>
                                <td>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="remover_colab_id" value="<?= $colab['id'] ?>">
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Remover este colaborador da equipa?');">Remover</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="tabela-colab-box">
            <h2>Colaboradores disponíveis para adicionar</h2>
            <table class="tabela-colabs">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($colaboradoresFora)): ?>
                        <tr><td colspan="2" style="color:#888;">Nenhum colaborador disponível para adicionar.</td></tr>
                    <?php else: ?>
                        <?php foreach ($colaboradoresFora as $colab): ?>
                            <tr>
                                <td><?= htmlspecialchars($colab['nome']) ?></td>
                                <td>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="adicionar_colab_id" value="<?= isset($colab['colaborador_id']) ? $colab['colaborador_id'] : (isset($colab['id']) ? $colab['id'] : '') ?>">
                                        <button type="submit" class="btn btn-add">Adicionar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
            </table>
        </div>
    </div>

    <div style="margin-top:24px;">
        <a href="equipas.php" class="btn" style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:#fff;">Voltar</a>
    </div>
</main>
</body>
</html>
