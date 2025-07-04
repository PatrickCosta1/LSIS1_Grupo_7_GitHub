<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['profile'] !== 'coordenador') {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/Coordenador/BLL_dashboard_coordenador.php';
$coordBLL = new CoordenadorDashboardManager();
$nome = htmlspecialchars($coordBLL->getCoordenadorName($_SESSION['user_id']));
$equipas = $coordBLL->getEquipasByCoordenador($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Página Inicial Coordenador - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/CSS/Coordenador/pagina_inicial_coordenador.css">
</head>
<body>
<div class="azul-container">
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
                    <a href="../Colaborador/beneficios.php">Benefícios</a>
                    <a href="../Colaborador/ferias.php">Férias</a>
                    <a href="../Colaborador/formacoes.php">Formações</a>
                    <a href="../Colaborador/recibos.php">Recibos</a>
                    <!-- Adiciona mais opções se quiseres -->
                </div>
            </div>
            <a href="../Comuns/logout.php">Sair</a>
        </nav>
    </header>
    <main>
        <h1>Bem-vindo, <?php echo $nome; ?>!</h1>
        <p class="descricao-inicial">
            Gere a tua Equipa através da consulta de relatórios e dashboards, acede à tua Ficha de Colaborador, recebe notificações importantes e muito mais!
        </p>
        <?php if (count($equipas) > 1): ?>
        <form method="get" id="formEscolherEquipa" style="text-align:center; margin-bottom:24px;">
            <label for="equipaSelect" style="font-weight:bold;">Escolher Equipa:</label>
            <select id="equipaSelect" name="equipa_id" style="margin-left:8px; padding:4px 8px; border-radius:6px; border:1px solid #ccd; background:#f7f8fa;">
                <?php foreach ($equipas as $e): ?>
                    <option value="<?php echo $e['id']; ?>" <?php if (isset($_GET['equipa_id']) && $_GET['equipa_id'] == $e['id']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($e['nome']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
        <script>
        document.getElementById('equipaSelect').addEventListener('change', function() {
            document.getElementById('formEscolherEquipa').submit();
        });
        </script>
        <?php endif; ?>
        <?php
            // Determinar equipa selecionada
            $equipaSelecionada = $equipas[0];
            if (isset($_GET['equipa_id'])) {
                foreach ($equipas as $e) {
                    if ($e['id'] == $_GET['equipa_id']) {
                        $equipaSelecionada = $e;
                        break;
                    }
                }
            }
            $equipaLink = "equipa.php?id=" . urlencode($equipaSelecionada['id']);
        ?>
        <div class="botoes-atalho">
            <a href="<?php echo $equipaLink; ?>" class="botao-atalho roxo">A Minha Equipa</a>
            <a href="relatorios_equipa.php?equipa_id=<?php echo urlencode($equipaSelecionada['id']); ?>" class="botao-atalho laranja">Relatórios</a>
            <a href="../Colaborador/ficha_colaborador.php" class="botao-atalho verde">A Minha Ficha</a>
        </div>
        <div class="dash-carousel-container">
            <span class="dash-topic active">Gestão de Equipa</span>
            <span class="dash-topic">Relatórios de Desempenho</span>
            <span class="dash-topic">Notificações Importantes</span>
            <span class="dash-topic">Consulta de Dados</span>
            <img id="dash-img" class="dash-img" src="../../assets/5.png" alt="" />
        </div>
    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const topics = document.querySelectorAll('.dash-topic');
    const img = document.getElementById('dash-img');
    const imgs = [
        '../../assets/5.png', // Gestão de Equipa
        '../../assets/4.png', // Relatórios de Desempenho
        '../../assets/6.png', // Notificações Importantes
        '../../assets/1.png'  // Consulta de Dados
    ];
    let idx = 0;
    setInterval(() => {
        topics[idx].classList.remove('active');
        img.classList.add('fade');
        idx = (idx + 1) % topics.length;
        topics[idx].classList.add('active');
        setTimeout(() => {
            img.src = imgs[idx];
            img.classList.remove('fade');
        }, 350);
    }, 3000);
});
</script>
</body>
</html>