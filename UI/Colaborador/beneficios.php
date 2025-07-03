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
                <div class="beneficio-card" onclick="abrirModal('modal1')">
                    <span>Seguro de Saúde</span>
                </div>
                <div class="beneficio-card" onclick="abrirModal('modal2')">
                    <span>Plano de Formação Contínua</span>
                </div>
                <div class="beneficio-card" onclick="abrirModal('modal3')">
                    <span>Horário Flexível</span>
                </div>
                <div class="beneficio-card" onclick="abrirModal('modal4')">
                    <span>Dia de Aniversário Livre</span>
                </div>
                <div class="beneficio-card" onclick="abrirModal('modal5')">
                    <span>Protocolos com Ginásios</span>
                </div>
                <div class="beneficio-card" onclick="abrirModal('modal6')">
                    <span>Cartão Refeição</span>
                </div>
                <div class="beneficio-card" onclick="abrirModal('modal7')">
                    <span>Programa de Reconhecimento</span>
                </div>
                <div class="beneficio-card" onclick="abrirModal('modal8')">
                    <span>Teletrabalho Parcial</span>
                </div>
                <div class="beneficio-card" onclick="abrirModal('modal9')">
                    <span>Apoio à Família</span>
                </div>
                <div class="beneficio-card" onclick="abrirModal('modal10')">
                    <span>Eventos Corporativos</span>
                </div>
                <div class="beneficio-card" onclick="abrirModal('modal11')">
                    <span>Subsídio de Transporte</span>
                </div>
                <div class="beneficio-card" onclick="abrirModal('modal12')">
                    <span>Programa de Wellness</span>
                </div>
            </div>
        </section>

        <!-- Modais -->
        <div id="modal1" class="beneficio-modal">
            <div class="modal-content">
                <span class="close" onclick="fecharModal('modal1')">&times;</span>
                <h2>Seguro de Saúde</h2>
                <p>Cobertura médica abrangente para colaboradores e familiares diretos.</p>
            </div>
        </div>

        <div id="modal2" class="beneficio-modal">
            <div class="modal-content">
                <span class="close" onclick="fecharModal('modal2')">&times;</span>
                <h2>Plano de Formação Contínua</h2>
                <p>Acesso a cursos, workshops e certificações profissionais.</p>
            </div>
        </div>

        <div id="modal3" class="beneficio-modal">
            <div class="modal-content">
                <span class="close" onclick="fecharModal('modal3')">&times;</span>
                <h2>Horário Flexível</h2>
                <p>Possibilidade de ajustar o horário de trabalho para melhor conciliação pessoal.</p>
            </div>
        </div>

        <div id="modal4" class="beneficio-modal">
            <div class="modal-content">
                <span class="close" onclick="fecharModal('modal4')">&times;</span>
                <h2>Dia de Aniversário Livre</h2>
                <p>Folga no dia de aniversário do colaborador.</p>
            </div>
        </div>

        <div id="modal5" class="beneficio-modal">
            <div class="modal-content">
                <span class="close" onclick="fecharModal('modal5')">&times;</span>
                <h2>Protocolos com Ginásios</h2>
                <p>Descontos em ginásios e academias parceiras.</p>
            </div>
        </div>

        <div id="modal6" class="beneficio-modal">
            <div class="modal-content">
                <span class="close" onclick="fecharModal('modal6')">&times;</span>
                <h2>Cartão Refeição</h2>
                <p>Subsídio de alimentação em cartão para maior comodidade.</p>
            </div>
        </div>

        <div id="modal7" class="beneficio-modal">
            <div class="modal-content">
                <span class="close" onclick="fecharModal('modal7')">&times;</span>
                <h2>Programa de Reconhecimento</h2>
                <p>Prémios e distinções para colaboradores de excelência.</p>
            </div>
        </div>

        <div id="modal8" class="beneficio-modal">
            <div class="modal-content">
                <span class="close" onclick="fecharModal('modal8')">&times;</span>
                <h2>Teletrabalho Parcial</h2>
                <p>Opção de trabalho remoto em determinados dias da semana.</p>
            </div>
        </div>

        <div id="modal9" class="beneficio-modal">
            <div class="modal-content">
                <span class="close" onclick="fecharModal('modal9')">&times;</span>
                <h2>Apoio à Família</h2>
                <p>Licenças parentais alargadas e apoio em situações familiares especiais.</p>
            </div>
        </div>

        <div id="modal10" class="beneficio-modal">
            <div class="modal-content">
                <span class="close" onclick="fecharModal('modal10')">&times;</span>
                <h2>Eventos Corporativos</h2>
                <p>Participação em eventos, team buildings e iniciativas internas.</p>
            </div>
        </div>

        <div id="modal11" class="beneficio-modal">
            <div class="modal-content">
                <span class="close" onclick="fecharModal('modal11')">&times;</span>
                <h2>Subsídio de Transporte</h2>
                <p>Apoio financeiro para deslocações casa-trabalho através de passes de transporte público ou ajuda de custo para combustível.</p>
            </div>
        </div>

        <div id="modal12" class="beneficio-modal">
            <div class="modal-content">
                <span class="close" onclick="fecharModal('modal12')">&times;</span>
                <h2>Programa de Wellness</h2>
                <p>Iniciativas de bem-estar incluindo sessões de mindfulness, workshops de gestão de stress e atividades de relaxamento no local de trabalho.</p>
            </div>
        </div>
    </main>
</div>

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