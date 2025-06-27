<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/Comuns/BLL_notificacoes.php';
$notBLL = new NotificacoesManager();
$notificacoes = $notBLL->getNotificacoesByUserId($_SESSION['user_id']);

// Marcar notificação como lida
if (isset($_GET['marcar_lida'])) {
    $notificacaoId = $_GET['marcar_lida'];
    $notBLL->marcarComoLida($notificacaoId);
    header('Location: notificacoes.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Notificações - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/CSS/Comuns/notificacoes.css">
</head>
<body>
    <header>
        <?php
            // Define o destino do logo conforme o perfil
            $logoLink = "#";
            if ($_SESSION['profile'] === 'colaborador') {
                $logoLink = "../Colaborador/pagina_inicial_colaborador.php";
            } elseif ($_SESSION['profile'] === 'coordenador') {
                $logoLink = "../Coordenador/dashboard_coordenador.php";
            } elseif ($_SESSION['profile'] === 'admin') {
                $logoLink = "../Admin/dashboard_admin.php";
            } elseif ($_SESSION['profile'] === 'rh') {
                $logoLink = "../RH/dashboard_rh.php";
            } else {
                $logoLink = "../Convidado/onboarding_convidado.php";
            }
        ?>
        <a href="<?php echo $logoLink; ?>">
            <img src="../../assets/tlantic-logo2.png" alt="Logo Tlantic" class="logo-header" style="cursor:pointer;">
        </a>
        <nav>
            <?php if ($_SESSION['profile'] === 'coordenador'): ?>
                <a href="../Coordenador/dashboard_coordenador.php">Dashboard</a>
                <a href="../Colaborador/ficha_colaborador.php">Minha Ficha</a>
                <a href="../Coordenador/equipa.php">A Minha Equipa</a>
                <a href="../Coordenador/relatorios_equipa.php">Relatórios Equipa</a>
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
                    </div>
                </div>
                <a href="../Comuns/logout.php">Sair</a>
            <?php elseif ($_SESSION['profile'] === 'colaborador'): ?>
                <a href="../Colaborador/ficha_colaborador.php">A Minha Ficha</a>
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
                    </div>
                </div>
                <a href="../Comuns/logout.php">Sair</a>
            <?php elseif ($_SESSION['profile'] === 'admin'): ?>
                <a href="../Admin/dashboard_admin.php">Dashboard</a>
                <a href="../Admin/utilizadores.php">Utilizadores</a>
                <a href="../Admin/permissoes.php">Permissões</a>
                <a href="../Admin/campos_personalizados.php">Campos Personalizados</a>
                <a href="../Admin/alertas.php">Alertas</a>
                <a href="../RH/colaboradores_gerir.php">Colaboradores</a>
                <a href="../RH/equipas.php">Equipas</a>
                <a href="../RH/relatorios.php">Relatórios</a>
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
                    </div>
                </div>
                <a href="../Comuns/logout.php">Sair</a>
            <?php elseif ($_SESSION['profile'] === 'rh'): ?>
                <a href="../RH/dashboard_rh.php">Dashboard</a>
                <a href="../RH/colaboradores_gerir.php">Colaboradores</a>
                <a href="../RH/equipas.php">Equipas</a>
                <a href="../RH/relatorios.php">Relatórios</a>
                <a href="../RH/exportar.php">Exportar</a>
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
                    </div>
                </div>
                <a href="../Comuns/logout.php">Sair</a>
            <?php else: ?>
                <a href="../Convidado/onboarding_convidado.php">Preencher Dados</a>
                <a href="../Comuns/logout.php">Sair</a>
            <?php endif; ?>
        </nav>
    </header>
    <main>
        <h1>Notificações</h1>
            <div class="notificacoes-container">
                <ul class="notificacoes-lista">
            <li class="notificacao unread" data-titulo="Nova Mensagem" data-perfil="RH" data-mensagem="Tens uma nova mensagem da equipa de RH." data-data="2024-06-27 10:15">
                <span class="titulo">Nova Mensagem</span>
                <span class="mensagem">Tens uma nova mensagem da equipa de RH.</span>
                <span class="data">2024-06-27 10:15</span>
                <div class="detalhes">
                    <strong>Enviada por:</strong> RH<br>
                    <strong>Mensagem:</strong> Tens uma nova mensagem da equipa de RH.<br>
                    <strong>Data/Hora:</strong> 2024-06-27 10:15
                </div>
            </li>
            <li class="notificacao" data-titulo="Férias Aprovadas" data-perfil="Coordenador" data-mensagem="O teu pedido de férias foi aprovado." data-data="2024-06-25 09:00">
                <span class="titulo">Férias Aprovadas</span>
                <span class="mensagem">O teu pedido de férias foi aprovado.</span>
                <span class="data">2024-06-25 09:00</span>
                <div class="detalhes">
                    <strong>Enviada por:</strong> Coordenador<br>
                    <strong>Mensagem:</strong> O teu pedido de férias foi aprovado.<br>
                    <strong>Data/Hora:</strong> 2024-06-25 09:00
                </div>
            </li>
            <li class="notificacao unread" data-titulo="Nova Formação Disponível" data-perfil="Formações" data-mensagem="Inscreve-te já na formação &quot;Excel Avançado&quot;." data-data="2024-06-24 14:30">
                <span class="titulo">Nova Formação Disponível</span>
                <span class="mensagem">Inscreve-te já na formação "Excel Avançado".</span>
                <span class="data">2024-06-24 14:30</span>
                <div class="detalhes">
                    <strong>Enviada por:</strong> Formações<br>
                    <strong>Mensagem:</strong> Inscreve-te já na formação "Excel Avançado".<br>
                    <strong>Data/Hora:</strong> 2024-06-24 14:30
                </div>
            </li>
            <li class="notificacao" data-titulo="Recibo Disponível" data-perfil="Financeiro" data-mensagem="O recibo de vencimento de junho já está disponível." data-data="2024-06-20 08:00">
                <span class="titulo">Recibo Disponível</span>
                <span class="mensagem">O recibo de vencimento de junho já está disponível.</span>
                <span class="data">2024-06-20 08:00</span>
                <div class="detalhes">
                    <strong>Enviada por:</strong> Financeiro<br>
                    <strong>Mensagem:</strong> O recibo de vencimento de junho já está disponível.<br>
                    <strong>Data/Hora:</strong> 2024-06-20 08:00
                </div>
            </li>
        </ul>
    </div>
</main>

    <div id="chatbot-widget" style="position: fixed; bottom: 24px; right: 24px; z-index: 9999;">
      <button id="open-chatbot" style="
          background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
          color: white;
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
    document.querySelectorAll('.notificacao').forEach(function(item) {
        item.addEventListener('click', function(e) {
            // Evita expandir ao clicar no botão
            if(e.target.classList.contains('btn')) return;
            document.querySelectorAll('.notificacao.expandida').forEach(function(n) {
                if(n !== item) n.classList.remove('expandida');
            });
            item.classList.toggle('expandida');
            // Se estava por ler, remove a classe 'unread' ao expandir
            if(item.classList.contains('expandida') && item.classList.contains('unread')) {
                item.classList.remove('unread');
                // Aqui podes fazer um fetch/ajax para marcar como lida na base de dados se quiseres
            }
        });
    });
    </script>

</body>
</html>