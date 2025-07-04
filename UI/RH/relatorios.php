<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['profile'], ['rh'])) {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/RH/BLL_relatorios.php';
$relatoriosBLL = new RHRelatoriosManager();
$indicadores = $relatoriosBLL->getIndicadoresGlobais();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Relatórios - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/CSS/RH/relatorios.css">
    <link rel="stylesheet" href="../../assets/CSS/Comuns/header.css">
</head>
<body>
    <header>
        <img src="../../assets/tlantic-logo2.png" alt="Logo Tlantic" class="logo-header" style="cursor:pointer;" onclick="window.location.href='pagina_inicial_RH.php';">
        <nav>
            <?php if ($_SESSION['profile'] === 'admin'): ?>
                <a href="../Admin/dashboard_admin.php">Dashboard</a>
                <a href="../Admin/utilizadores.php">Utilizadores</a>
                <a href="../Admin/permissoes.php">Permissões</a>
                <a href="../Admin/campos_personalizados.php">Campos Personalizados</a>
                <a href="../Admin/alertas.php">Alertas</a>
                <a href="colaboradores_gerir.php">Colaboradores</a>
                <a href="equipas.php">Equipas</a>
                <a href="relatorios.php">Relatórios</a>
                <a href="../Comuns/perfil.php">Perfil</a>
                <a href="../Comuns/logout.php">Sair</a>
            <?php else: ?>
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
            <?php endif; ?>
        </nav>
    </header>
    <div class="portal-brand">
        <div class="color-bar">
            <div class="color-segment"></div>
            <div class="color-segment"></div>
            <div class="color-segment"></div>
        </div>
        <span class="portal-text">Portal Do Colaborador</span>
    </div>
    <main>
        <h1 class="relatorios-titulo">Relatórios e Dashboards</h1>
        <section>
            <div class="indicadores-titulo">Indicadores Gerais</div>
            <table class="indicadores-table">                   
                        <thead>
                            <tr>
                                <th>Indicador</th>
                                <th>Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Total de colaboradores</td>
                                <td><?php echo htmlspecialchars($indicadores['total_colaboradores']); ?></td>
                            </tr>
                            <tr>
                                <td>Colaboradores ativos</td>
                                <td><?php echo htmlspecialchars($indicadores['ativos']); ?></td>
                            </tr>
                            <tr>
                                <td>Colaboradores inativos</td>
                                <td><?php echo htmlspecialchars($indicadores['inativos']); ?></td>
                            </tr>
                            <tr>
                                <td>Total de equipas</td>
                                <td><?php echo htmlspecialchars($indicadores['total_equipas']); ?></td>
                            </tr>
                        </tbody>
                    </table>
        </section>
        <section class="dashboard-cards">
            <div class="card">
                <h2>Aniversários por Equipa</h2>
                <a href="#" class="btn">Ver Relatório</a>
            </div>
            <div class="card">
                <h2>Alterações Contratuais</h2>
                <a href="#" class="btn">Ver Relatório</a>
            </div>
            <div class="card">
                <h2>Vouchers Atribuídos</h2>
                <a href="#" class="btn">Ver Relatório</a>
            </div>
            <div class="card">
                <h2>Dashboards de Equipa</h2>
                <a href="#" class="btn">Ver Dashboard</a>
            </div>
        </section>
    </main>

    <!-- Removido o chatbot -->
    <script src="../../assets/chatbot.js"></script>
</body>
</html>