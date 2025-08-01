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
        <img src="../../assets/tlantic-logo2.png" alt="Logo Tlantic" class="logo-header" style="cursor:pointer;" onclick="window.location.href='pagina_inicial_coordenador.php';">
        <nav>
            <?php
                // Corrigir link da equipa para incluir o id da equipa do coordenador
                $equipaLink = "equipa.php";
                if (!empty($equipas) && isset($equipas[0]['id'])) {
                    $equipaLink = "equipa.php?id=" . urlencode($equipas[0]['id']);
                }
            ?>
            <div class="dropdown-equipa">
                <a href="<?php echo $equipaLink; ?>" class="equipa-link">
                    Equipa
                    <span class="seta-baixo">&#9662;</span>
                </a>
                <div class="dropdown-menu">
                    <a href="dashboard_coordenador.php">Dashboard</a>
                    <a href="relatorios_equipa.php">Relatórios Equipa</a>
                </div>
            </div>
            <a href="../Comuns/notificacoes.php">Notificações</a>
            <div class="dropdown-perfil">
                <a href="../Comuns/perfil.php" class="perfil-link">
                    Perfil
                    <span class="seta-baixo">&#9662;</span>
                </a>
                <div class="dropdown-menu">
                        <a href="../Colaborador/ficha_colaborador.php">Ficha Colaborador</a>
                    <a href="beneficios.php">Benefícios</a>
                    <a href="ferias.php">Férias</a>
                    <a href="formacoes.php">Formações</a>
                    <a href="recibos.php">Recibos</a>
                    <!-- Adiciona mais opções se quiseres -->
                </div>
            </div>
            <a href="../Comuns/logout.php">Sair</a>
        </nav>
    </header>
    <main>
    <h1>Relatórios das Equipas</h1>
    <?php foreach ($equipas as $e): ?>
        <div class="relatorio-container">
            <h2><?php echo htmlspecialchars($e['nome']); ?></h2>
            <table class="relatorio-table">
                <thead>
                    <tr>
                        <th>Relatório</th>
                        <th>Estado</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Assiduidade</td>
                        <td><span class="estado-azul">Disponível</span></td>
                        <td>
                            <a href="#" class="btn-azul">Ver</a>
                        </td>
                    </tr>
                    <tr>
                        <td>Férias</td>
                        <td><span class="estado-azul">Disponível</span></td>
                        <td>
                            <a href="#" class="btn-azul">Ver</a>
                        </td>
                    </tr>
                    <tr>
                        <td>Formações</td>
                        <td><span class="estado-azul">Disponível</span></td>
                        <td>
                            <a href="#" class="btn-azul">Ver</a>
                        </td>
                    </tr>
                    <!-- Adiciona mais linhas conforme necessário -->
                </tbody>
            </table>
            <ul class="relatorio-list">
                <li><strong>Assiduidade:</strong> Relatório de presenças, faltas e atrasos da equipa.</li>
                <li><strong>Férias:</strong> Resumo dos dias de férias marcados e disponíveis.</li>
                <li><strong>Formações:</strong> Histórico de formações realizadas pelos membros da equipa.</li>
                <!-- Adiciona mais descrições conforme necessário -->
            </ul>
        </div>
    <?php endforeach; ?>
</main>
    <!-- ...chatbot e scripts... -->
</body>
</html>