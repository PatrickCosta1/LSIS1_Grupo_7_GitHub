<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['profile'], ['rh', 'admin'])) {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/RH/BLL_equipa_nova.php';
$equipasBLL = new EquipaNovaManager();

$tipo = $_POST['tipo'] ?? 'colaboradores';
$responsaveis = $equipasBLL->getResponsaveisPorTipo($tipo);
$membros = $equipasBLL->getMembrosPorTipo($tipo);

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $responsavel_id = $_POST['responsavel_id'] ?? null;
    $elementos = $_POST['elementos'] ?? [];
    $tipo = $_POST['tipo'] ?? 'colaboradores';
    if ($nome && $responsavel_id && $tipo) {
        $resultado = $equipasBLL->addEquipa($nome, $responsavel_id, $elementos, $tipo);
        if ($resultado === true) {
            $success = "Equipa criada com sucesso!";
        } else {
            $error = "Erro ao criar equipa: " . $resultado;
        }
    } else {
        $error = "Preencha todos os campos obrigatórios.";
    }
    // Atualizar listas após submit
    $responsaveis = $equipasBLL->getResponsaveisPorTipo($tipo);
    $membros = $equipasBLL->getMembrosPorTipo($tipo);
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
            <div class="dropdown-equipas">
                <a href="equipas.php" class="equipas-link">
                    Equipas
                    <span class="seta-baixo">&#9662;</span>
                </a>
                <div class="dropdown-menu">
                    <a href="relatorios.php">Relatórios</a>
                    <a href="dashboard_rh.php">Dashboard</a>
                </div>
            </div>
            <div class="dropdown-colaboradores">
                <a href="colaboradores_gerir.php" class="colaboradores-link">
                    Colaboradores
                    <span class="seta-baixo">&#9662;</span>
                </a>
                <div class="dropdown-menu">
                    <a href="exportar.php">Exportar</a>
                </div>
            </div>
            <a href="../Comuns/notificacoes.php">Notificações</a>
            <a href="../Comuns/perfil.php">Perfil</a>
            <a href="../Comuns/logout.php">Sair</a>
        </nav>
    </header>
    <main>
        <h1>Criar Nova Equipa</h1>
        <?php if ($success): ?><div class="success-message"><?php echo $success; ?></div><?php endif; ?>
        <?php if ($error): ?><div class="error-message"><?php echo $error; ?></div><?php endif; ?>
        <form method="POST" class="ficha-form ficha-form-moderna" id="form-equipa-nova">
            <div class="ficha-grid">
                <div class="ficha-campo">
                    <label>Tipo de Equipa:</label>
                    <select name="tipo" id="tipo" required onchange="this.form.submit()">
                        <option value="colaboradores" <?= $tipo === 'colaboradores' ? 'selected' : '' ?>>Equipa de Colaboradores</option>
                        <option value="coordenadores" <?= $tipo === 'coordenadores' ? 'selected' : '' ?>>Equipa de Coordenadores</option>
                        <option value="rh" <?= $tipo === 'rh' ? 'selected' : '' ?>>Equipa de RH</option>
                    </select>
                </div>
                <div class="ficha-campo">
                    <label>Nome da Equipa:</label>
                    <input type="text" name="nome" required autocomplete="off" value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>">
                </div>
                <div class="ficha-campo">
                    <label>Responsável:</label>
                    <select name="responsavel_id" required>
                        <option value="">Selecione</option>
                        <?php foreach ($responsaveis as $c): ?>
                            <option value="<?php echo $c['colaborador_id']; ?>" <?= (isset($_POST['responsavel_id']) && $_POST['responsavel_id'] == $c['colaborador_id']) ? 'selected' : '' ?>>
                                <?php echo htmlspecialchars($c['nome']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="ficha-campo">
                    <label>Membros da Equipa:</label>
                    <div class="lista-colaboradores" data-count="<?= count($membros) ?>">
                        <?php if (empty($membros)): ?>
                            <span style="color:#888;">Não existem membros disponíveis para adicionar.</span>
                        <?php else: ?>
                            <?php foreach ($membros as $col): ?>
                                <label>
                                    <input type="checkbox" name="elementos[]" value="<?php echo $col['colaborador_id']; ?>"
                                        <?= (isset($_POST['elementos']) && in_array($col['colaborador_id'], $_POST['elementos'])) ? 'checked' : '' ?>>
                                    <?php echo htmlspecialchars($col['nome']); ?>
                                </label>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <small>Selecione os membros que vão pertencer à equipa.<br>
                    <span style="color:#888;">Apenas membros do tipo selecionado aparecem aqui.</span></small>
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