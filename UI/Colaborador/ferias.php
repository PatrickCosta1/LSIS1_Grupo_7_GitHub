<?php
session_start();
$perfil = $_SESSION['profile'] ?? '';
$userId = $_SESSION['user_id'] ?? null;

if (!$userId || !in_array($perfil, ['colaborador', 'coordenador', 'rh', 'admin'])) {
    header('Location: ../Comuns/erro.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Férias - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/CSS/Colaborador/ferias.css">
</head>
<body>
    <header>
        <img src="../../assets/tlantic-logo2.png" alt="Logo Tlantic" class="logo-header"
            <?php if ($perfil === 'colaborador'): ?>
                style="cursor:pointer;" onclick="window.location.href='pagina_inicial_colaborador.php';"
            <?php elseif ($perfil === 'coordenador'): ?>
                style="cursor:pointer;" onclick="window.location.href='../Coordenador/pagina_inicial_coordenador.php';"
            <?php endif; ?>
        >
        <nav>
            <?php if ($perfil === 'coordenador'): ?>
                <?php
                    require_once '../../BLL/Coordenador/BLL_dashboard_coordenador.php';
                    $coordBLL = new CoordenadorDashboardManager();
                    $equipas = $coordBLL->getEquipasByCoordenador($_SESSION['user_id']);
                    $equipaLink = "../Coordenador/equipa.php";
                    if (!empty($equipas) && isset($equipas[0]['id'])) {
                        $equipaLink = "../Coordenador/equipa.php?id=" . urlencode($equipas[0]['id']);
                    }
                ?>
                <div class="dropdown-equipa">
                    <a href="<?php echo $equipaLink; ?>" class="equipa-link">
                        Equipa
                        <span class="seta-baixo">&#9662;</span>
                    </a>
                    <div class="dropdown-menu">
                        <a href="../Coordenador/dashboard_coordenador.php">Dashboard</a>
                        <a href="../Coordenador/relatorios_equipa.php">Relatórios Equipa</a>
                    </div>
                </div>
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
            <?php else: ?>
                <header>
        <img src="../../assets/tlantic-logo2.png" alt="Logo Tlantic" class="logo-header">
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
                </div>
            </div>
            <a href="../Comuns/logout.php">Sair</a>
        </nav>
    </header>
            <?php endif; ?>
        </nav>
    </header>
    <div class="azul-container">
    <main>
        <div class="portal-brand">
            <div class="color-bar">
                <div class="color-segment"></div>
                <div class="color-segment"></div>
                <div class="color-segment"></div>
            </div>
            <span class="portal-text">Portal Do Colaborador</span>
        </div>
        <h1>Pedido de Férias</h1>
        <p class="descricao-inicial">
            Selecione o intervalo de datas em que pretende usufruir das suas férias e submeta o pedido para aprovação.
        </p>
        
        <!-- Mensagens de feedback apenas via JS -->
        <div id="ferias-msg" class="ferias-msg" style="display:none;"></div>
        
        <!-- Calendário sempre visível -->
        <div class="calendario-ferias">
            <div class="calendario-header">
                <h2>Selecione as Datas das Férias</h2>
            </div>
            <div class="calendario-selecao">
                <div class="data-campo">
                    <label for="data_inicio">Data de Início:</label>
                    <input type="date" id="data_inicio" name="data_inicio" required>
                </div>
                <div class="data-campo">
                    <label for="data_fim">Data de Fim:</label>
                    <input type="date" id="data_fim" name="data_fim" required>
                </div>
            </div>
            <button id="btn-pedir-ferias" class="btn-pedir-ferias">Pedir Férias</button>
        </div>
    </main>
</div>

<script>
document.getElementById('btn-pedir-ferias').addEventListener('click', function(e) {
    e.preventDefault();
    const data_inicio = document.getElementById('data_inicio').value;
    const data_fim = document.getElementById('data_fim').value;
    const msg = document.getElementById('ferias-msg');
    
    if (!data_inicio || !data_fim) {
        msg.textContent = "Por favor, selecione as datas de início e fim.";
        msg.style.display = "block";
        msg.style.background = "#ffe0e0";
        msg.style.color = "#b00";
        return;
    }
    
    if (data_inicio > data_fim) {
        msg.textContent = "A data de início não pode ser posterior à data de fim.";
        msg.style.display = "block";
        msg.style.background = "#ffe0e0";
        msg.style.color = "#b00";
        return;
    }
    
    fetch('pedir_ferias.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ data_inicio, data_fim })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            msg.textContent = "Pedido de férias submetido com sucesso!";
            msg.style.display = "block";
            msg.style.background = "#e0ffe0";
            msg.style.color = "#23408e";
            document.getElementById('data_inicio').value = '';
            document.getElementById('data_fim').value = '';
        } else {
            msg.textContent = data.error || "Ocorreu um erro ao submeter o pedido.";
            msg.style.display = "block";
            msg.style.background = "#ffe0e0";
            msg.style.color = "#b00";
        }
    });
});
</script>
</body>
</html>