<?php
session_start();
$perfil = $_SESSION['profile'] ?? '';
$userId = $_SESSION['user_id'] ?? null;

if (!$userId || !in_array($perfil, ['colaborador', 'coordenador', 'rh', 'admin'])) {
    header('Location: ../Comuns/erro.php');
    exit();
}

// Buscar benefícios da base de dados
require_once '../../BLL/RH/BLL_gerir_beneficios.php';
$beneficiosBLL = new RHBeneficiosGerirManager();
$beneficios = $beneficiosBLL->listarBeneficios();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Benefícios - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/CSS/Colaborador/beneficios.css">
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
                    <a href="recibos.php">Recibos</a>
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
        <h1>Benefícios do Colaborador Tlantic</h1>
        <p class="descricao-inicial">
            A Tlantic valoriza os seus colaboradores e oferece um conjunto de benefícios que promovem o bem-estar, desenvolvimento e equilíbrio entre a vida profissional e pessoal.
        </p>
        <section class="beneficios-lista">
            <div class="beneficios-cards">
                <?php if (!empty($beneficios)): ?>
                    <?php foreach ($beneficios as $b): ?>
                        <div class="beneficio-card" onclick="abrirModal('modal<?= $b['id'] ?>')">
                            <span><?= htmlspecialchars($b['titulo']) ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="grid-column: 1 / -1; text-align: center; color: #888; margin: 40px 0;">
                        Nenhum benefício disponível de momento.
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- Modais -->
        <?php if (!empty($beneficios)): ?>
            <?php foreach ($beneficios as $b): ?>
                <div id="modal<?= $b['id'] ?>" class="beneficio-modal">
                    <div class="modal-content">
                        <span class="close" onclick="fecharModal('modal<?= $b['id'] ?>')">&times;</span>
                        <h2><?= htmlspecialchars($b['titulo']) ?></h2>
                        <p><?= nl2br(htmlspecialchars($b['descricao'])) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>
</div>

 <div id="chatbot-widget" style="position: fixed; bottom: 24px; right: 24px; z-index: 9999;">
      <button id="open-chatbot" style="
          background: linear-gradient(135deg,rgb(255, 203, 120) 0%,rgb(251, 155, 0) 100%);
          color:rgb(255, 255, 255);
          border: none;
          border-radius: 50%;
          width: 60px;
          height: 60px;
          box-shadow: 0 4px 16px rgba(0,0,0,0.15);
          font-size: 28px;
          cursor: pointer;
          display: flex;
          align-items: center;
          justify-content: center;
          ">
        ?
      </button>
      <iframe
        id="chatbot-iframe"
        src="https://www.chatbase.co/chatbot-iframe/SHUUk9C_zO-W-kHarKtWh"
        title="Ajuda Chatbot"
        width="350"
        height="500"
        style="display: none; position: absolute; bottom: 70px; right: 0; border: none; border-radius: 16px; box-shadow: 0 4px 16px rgba(0,0,0,0.15);">
      </iframe>
    </div>
    <script src="../../assets/chatbot.js"></script>

<script>
function abrirModal(modalId) {
    document.getElementById(modalId).style.display = 'flex';
}

function fecharModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

// Fechar modal ao clicar fora
window.onclick = function(event) {
    if (event.target.classList.contains('beneficio-modal')) {
        event.target.style.display = 'none';
    }
}
</script>
</body>
</html>