<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['profile'] !== 'coordenador') {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/Coordenador/BLL_dashboard_coordenador.php';
$coordBLL = new CoordenadorDashboardManager();
$equipas = $coordBLL->getEquipasByCoordenador($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Relatórios Equipa - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/CSS/Coordenador/relatorios_equipa.css">
    <link rel="stylesheet" href="../../assets/CSS/Comuns/header.css">
</head>
<body>
    <header>
        <img src="../../assets/tlantic-logo2.png" alt="Logo Tlantic" class="logo-header">
        <nav>
            <a href="dashboard_coordenador.php">Dashboard</a>
            <a href="../Colaborador/ficha_colaborador.php">Minha Ficha</a>
            <a href="equipa.php">Minha Equipa</a>
            <a href="relatorios_equipa.php">Relatórios Equipa</a>
            <a href="../Comuns/notificacoes.php">Notificações</a>
            <a href="../Comuns/perfil.php">Perfil</a>
            <a href="../Comuns/logout.php">Sair</a>
        </nav>
    </header>
    <main>
        <h1>Relatórios das Equipas</h1>
        <?php foreach ($equipas as $e): ?>
            <h2><?php echo htmlspecialchars($e['nome']); ?></h2>
            <!-- Aqui pode adicionar gráficos, KPIs, etc. -->
            <p>Relatórios e indicadores da equipa <?php echo htmlspecialchars($e['nome']); ?>.</p>
        <?php endforeach; ?>
    </main>
    <!-- ...chatbot e scripts... -->
</body>
</html>