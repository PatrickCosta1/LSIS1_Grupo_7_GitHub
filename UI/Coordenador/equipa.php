<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['profile'] !== 'coordenador') {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/Coordenador/BLL_dashboard_coordenador.php';
$coordBLL = new CoordenadorDashboardManager();

$equipaId = $_GET['id'] ?? null;
if (!$equipaId) {
    header('Location: dashboard_coordenador.php');
    exit();
}
$colaboradores = $coordBLL->getColaboradoresByEquipa($equipaId);
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Minha Equipa - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/CSS/Coordenador/equipa.css">
    <link rel="stylesheet" href="../../assets/CSS/Comuns/header.css">
</head>
<body>
    <header>
        <img src="../../assets/tlantic-logo2.png" alt="Logo Tlantic" class="logo-header">
        <nav>
            <a href="dashboard_coordenador.php">Dashboard</a>
            <a href="../Colaborador/ficha_colaborador.php">Minha Ficha</a>
            <a href="equipa.php<?php echo isset($equipaId) ? '?id=' . urlencode($equipaId) : ''; ?>">Minha Equipa</a>
            <a href="relatorios_equipa.php">Relatórios Equipa</a>
            <a href="../Comuns/notificacoes.php">Notificações</a>
            <a href="../Comuns/perfil.php">Perfil</a>
            <a href="../Comuns/logout.php">Sair</a>
        </nav>
    </header>
    <main>
        <h1>Colaboradores da Equipa</h1>
        <table class="tabela-colaboradores">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Função</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($colaboradores as $c): ?>
                <tr>
                    <td><?php echo htmlspecialchars($c['nome']); ?></td>
                    <td><?php echo htmlspecialchars($c['cargo']); ?></td>
                    <td><?php echo htmlspecialchars($c['email']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
    <!-- ...chatbot e scripts... -->
</body>
</html>