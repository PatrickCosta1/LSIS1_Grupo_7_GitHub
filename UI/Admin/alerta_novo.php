<?php
session_start();
require_once '../../BLL/Admin/BLL_alerta_novo.php';

$msg = '';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = trim($_POST['tipo'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $periodicidade_meses = intval($_POST['periodicidade_meses'] ?? 0);
    $ativo = isset($_POST['ativo']) ? 1 : 0;

    if ($tipo && $descricao && $periodicidade_meses > 0) {
        $bll = new AlertaNovoManager();
        $ok = $bll->criarAlerta($tipo, $descricao, $periodicidade_meses, $ativo);
        if ($ok) {
            $msg = "Alerta criado com sucesso!";
        } else {
            $erro = "Erro ao criar alerta.";
        }
    } else {
        $erro = "Preencha todos os campos obrigatórios.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Novo Alerta</title>
    <link rel="stylesheet" href="../../assets/CSS/Admin/alerta_novo.css">
</head>
<body>
    <header>
        <a href="pagina_inicial_admin.php">
            <img src="../../assets/tlantic-logo2.png" alt="Logo Tlantic" class="logo-header">
        </a>
        <nav>
            <a href="utilizadores.php">Utilizadores</a>
            <a href="permissoes.php">Permissões</a>
            <a href="campos_personalizados.php">Campos Personalizados</a>
            <a href="alertas.php">Alertas</a>
            <a href="../Comuns/perfil.php" class="perfil-link">Perfil</a>
            <a href="../Comuns/logout.php" class="sair-link">Sair</a>
        </nav>
    </header>
    <main>
            <h2>Criar Novo Alerta</h2>
            <?php if ($erro): ?><div class="alerta-erro"><?php echo htmlspecialchars($erro); ?></div><?php endif; ?>
            <?php if ($msg): ?><div class="alerta-sucesso"><?php echo htmlspecialchars($msg); ?></div><?php endif; ?>
            <form method="post">
                <label for="tipo">Tipo do Alerta:</label>
                <input type="text" name="tipo" id="tipo" required maxlength="100">
                <label for="descricao">Descrição:</label>
                <textarea name="descricao" id="descricao" rows="4" required maxlength="500"></textarea>
                <label for="periodicidade_meses">Periodicidade (meses):</label>
                <input type="number" name="periodicidade_meses" id="periodicidade_meses" min="1" required>
                <label>
                    <input type="checkbox" name="ativo" checked>
                    Ativo
                </label>
                <button type="submit">Criar Alerta</button>
            </form>
    </main>
</body>
</html>
