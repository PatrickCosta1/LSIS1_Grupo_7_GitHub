<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['profile'] !== 'colaborador') {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/Colaborador/BLL_formacoes.php';
require_once '../../BLL/Colaborador/BLL_ficha_colaborador.php';

$colabBLL = new ColaboradorFichaManager();
$colab = $colabBLL->getColaboradorByUserId($_SESSION['user_id']);
$colaborador_id = $colab['id'] ?? null;

if (!$colaborador_id) {
    die('Erro: Não foi possível identificar o colaborador.');
}

$formacoesBLL = new FormacoesManager();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['formacao_id'])) {
    $formacoesBLL->inscrever($colaborador_id, intval($_POST['formacao_id']));
}

$formacoes = $formacoesBLL->listarFormacoesFuturas();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Formações Disponíveis</title>
    <link rel="stylesheet" href="../../assets/CSS/Colaborador/formacoes.css">
</head>
<body>
<div class="azul-container">
    <header>
        <img src="../../assets/tlantic-logo2.png" alt="Logo Tlantic" class="logo-header" style="cursor:pointer;" onclick="window.location.href='pagina_inicial_colaborador.php';">
        <nav>
            <a href="ficha_colaborador.php">A Minha Ficha</a>
            <a href="../Comuns/notificacoes.php">Notificações</a>
            <div class="dropdown-perfil">
                <a href="../Comuns/perfil.php" class="perfil-link">
                    Perfil
                    <span class="seta-baixo">&#9662;</span>
                </a>
                <div class="dropdown-menu">
                    <a href="ficha_colaborador.php">Ficha Colaborador</a>
                    <a href="beneficios.php">Benefícios</a>
                    <a href="ferias.php">Férias</a>
                    <a href="formacoes.php">Formações</a>
                    <a href="recibos.php">Recibos</a>
                </div>
            </div>
            <a href="../Comuns/logout.php">Sair</a>
        </nav>
    </header>
    <main>
        <div class="formacoes-container">
            <h1>Formações Disponíveis</h1>
            <table class="tabela-formacoes">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Descrição</th>
                        <th>Data Início</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($formacoes as $f): ?>
                    <tr>
                        <td><?= htmlspecialchars($f['nome']) ?></td>
                        <td><?= htmlspecialchars($f['descricao']) ?></td>
                        <td><?= htmlspecialchars(date('d/m/Y', strtotime($f['data_inicio']))) ?></td>
                        <td>
                            <?php if ($formacoesBLL->jaInscrito($colaborador_id, $f['id'])): ?>
                                <span class="inscrito-label">Inscrito</span>
                            <?php else: ?>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="formacao_id" value="<?= $f['id'] ?>">
                                    <button type="submit" class="btn-inscrever">Inscrever-se</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>
</body>
</html>