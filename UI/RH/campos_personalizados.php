<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['profile'] !== 'rh') {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/RH/BLL_campos_personalizados.php';
$camposBLL = new CamposPersonalizadosManager();

$success = '';
$error = '';

// Adicionar novo campo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['novo_campo'])) {
    $nome = trim($_POST['nome'] ?? '');
    $tipo = $_POST['tipo'] ?? 'texto';
    if ($nome) {
        if ($camposBLL->adicionarCampo($nome, $tipo)) {
            $success = "Campo adicionado com sucesso!";
        } else {
            $error = "Erro ao adicionar campo.";
        }
    } else {
        $error = "Nome do campo obrigatório.";
    }
}

// Editar campo existente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_campo'])) {
    $id = $_POST['campo_id'] ?? '';
    $nome_novo = trim($_POST['nome_novo'] ?? '');
    $tipo = $_POST['tipo'] ?? 'texto';
    if ($id && $nome_novo) {
        if ($camposBLL->editarCampo($id, $nome_novo, $tipo)) {
            $success = "Campo atualizado com sucesso!";
        } else {
            $error = "Erro ao atualizar campo.";
        }
    } else {
        $error = "Nome do campo obrigatório.";
    }
}

// Remover campo
if (isset($_GET['remover']) && $_GET['remover']) {
    if ($camposBLL->removerCampo($_GET['remover'])) {
        $success = "Campo removido.";
    } else {
        $error = "Erro ao remover campo.";
    }
}

$campos = $camposBLL->listarCampos();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Campos Personalizados - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/CSS/RH/campos_personalizados.css">
    <style>
        .campos-lista { max-width: 700px; margin: 30px auto; background: #fff; border-radius: 14px; box-shadow: 0 2px 16px #23408e1a; padding: 32px 28px;}
        .campo-item { display: flex; align-items: center; gap: 12px; margin-bottom: 10px; }
        .campo-nome { flex: 1; }
        .btn { background: #23408e; color: #fff; border: none; border-radius: 6px; padding: 6px 16px; font-weight: 600; cursor: pointer;}
        .btn-edit { background: #ffb347; color: #23408e;}
        .btn-remove { background: #e53e3e; color: #fff;}
        .btn-add { background: #38a169; color: #fff;}
        .campo-form { display: flex; gap: 8px; margin-bottom: 18px; }
        .campo-form input, .campo-form select { padding: 6px 8px; border-radius: 5px; border: 1px solid #cfd8e3;}
        .sucesso { color: #38a169; margin-bottom: 8px;}
        .erro { color: #e53e3e; margin-bottom: 8px;}
    </style>
</head>
<body>
    <header>
        <img src="../../assets/tlantic-logo2.png" alt="Logo Tlantic" class="logo-header" onclick="window.location.href='pagina_inicial_RH.php';" style="cursor:pointer;">
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
            <div class="dropdown-gestao">
                <a href="#" class="gestao-link">
                    Gestão
                    <span class="seta-baixo">&#9662;</span>
                </a>
                <div class="dropdown-menu">
                    <a href="gerir_beneficios.php">Gerir Benefícios</a>
                    <a href="gerir_formacoes.php">Gerir Formações</a>
                    <a href="gerir_recibos.php">Submeter Recibos</a>
                    <a href="campos_personalizados.php">Campos Personalizados</a>
                </div>
            </div>
            <a href="../Comuns/notificacoes.php">Notificações</a>
            <div class="dropdown-perfil">
                <a href="../Comuns/perfil.php" class="perfil-link">
                    Perfil
                    <span class="seta-baixo">&#9662;</span>
                </a>
                <div class="dropdown-menu">
                    <a href="../Colaborador/ficha_colaborador.php">Perfil Colaborador</a>
                </div>
            </div>
            <a href="../Comuns/logout.php">Sair</a>
        </nav>
    </header>
    <!-- Marca do portal -->
    <div class="portal-brand">
        <div class="color-bar">
            <div class="color-segment"></div>
            <div class="color-segment"></div>
            <div class="color-segment"></div>
        </div>
        <span class="portal-text">Portal Tlantic</span>
    </div>
    <main>
        <h1>Gestão de Campos Personalizados</h1>
        <div class="campos-lista">
            <?php if ($success): ?><div class="sucesso"><?= htmlspecialchars($success) ?></div><?php endif; ?>
            <?php if ($error): ?><div class="erro"><?= htmlspecialchars($error) ?></div><?php endif; ?>

            <form method="post" class="campo-form">
                <input type="text" name="nome" placeholder="Novo campo" required>
                <select name="tipo">
                    <option value="texto">Texto</option>
                    <option value="numero">Número</option>
                    <option value="data">Data</option>
                    <option value="email">Email</option>
                </select>
                <button type="submit" name="novo_campo" class="btn btn-add">+</button>
            </form>

            <?php foreach ($campos as $campo): ?>
                <div class="campo-item">
                    <span class="campo-nome"><?= htmlspecialchars($campo['nome']) ?> (<?= htmlspecialchars($campo['tipo']) ?>)</span>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="campo_id" value="<?= htmlspecialchars($campo['id']) ?>">
                        <input type="text" name="nome_novo" value="<?= htmlspecialchars($campo['nome']) ?>" required>
                        <select name="tipo">
                            <option value="texto" <?= $campo['tipo']=='texto'?'selected':''; ?>>Texto</option>
                            <option value="numero" <?= $campo['tipo']=='numero'?'selected':''; ?>>Número</option>
                            <option value="data" <?= $campo['tipo']=='data'?'selected':''; ?>>Data</option>
                            <option value="email" <?= $campo['tipo']=='email'?'selected':''; ?>>Email</option>
                        </select>
                        <button type="submit" name="editar_campo" class="btn btn-edit">&#9998;</button>
                    </form>
                    <a href="?remover=<?= htmlspecialchars($campo['id']) ?>" class="btn btn-remove" onclick="return confirm('Remover este campo?')">-</a>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>
