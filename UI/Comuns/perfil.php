<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/Comuns/BLL_perfil.php';
$userBLL = new PerfilManager();
$user = $userBLL->getUserById($_SESSION['user_id']);

$colab = $userBLL->getColaboradorByUserId($_SESSION['user_id']);
$colaborador_id = $colab['id'] ?? null;

$formacoesInscritas = [];
$pedidosFerias = [];

error_log("=== DEBUG PERFIL INICIAL ===");
error_log("User ID (sessão): " . $_SESSION['user_id']);
error_log("Colaborador encontrado: " . ($colab ? 'SIM' : 'NÃO'));
if ($colab) {
    error_log("Colaborador dados: " . print_r($colab, true));
    error_log("Colaborador ID extraído: " . $colaborador_id);
}

if ($colaborador_id) {
    try {
        $formacoesInscritas = $userBLL->getFormacoesPorColaborador($colaborador_id);
        $pedidosFerias = $userBLL->getPedidosFeriasPorColaborador($colaborador_id);
        
        error_log("=== DEBUG PERFIL FINAL ===");
        error_log("User ID (sessão): " . $_SESSION['user_id']);
        error_log("Colaborador dados completos: " . print_r($colab, true));
        error_log("Colaborador ID usado na query: " . $colaborador_id);
        error_log("Formações encontradas: " . count($formacoesInscritas));
        error_log("Pedidos de férias encontrados: " . count($pedidosFerias));
        
        if (!empty($pedidosFerias)) {
            error_log("SUCESSO: Pedidos de férias encontrados!");
            foreach ($pedidosFerias as $index => $pedido) {
                error_log("Pedido " . ($index + 1) . ": " . print_r($pedido, true));
            }
        } else {
            error_log("PROBLEMA: Nenhum pedido de férias encontrado para colaborador_id = " . $colaborador_id);
        }
    } catch (Exception $e) {
        error_log("EXCEÇÃO no perfil: " . $e->getMessage());
        $formacoesInscritas = [];
        $pedidosFerias = [];
    }
} else {
    error_log("ERRO: colaborador_id é null. Dados do colaborador: " . print_r($colab, true));
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../../assets/CSS/Comuns/perfil.css">
    <link rel="stylesheet" href="../../assets/CSS/Comuns/header.css">
</head>
<body>
<header>
    <?php
        $logoLink = "#";
        if ($_SESSION['profile'] === 'colaborador') {
            $logoLink = "../Colaborador/pagina_inicial_colaborador.php";
        } elseif ($_SESSION['profile'] === 'coordenador') {
            $logoLink = "../Coordenador/pagina_inicial_coordenador.php";
        } elseif ($_SESSION['profile'] === 'admin') {
            $logoLink = "../Admin/dashboard_admin.php";
        } elseif ($_SESSION['profile'] === 'rh') {
            $logoLink = "../RH/pagina_inicial_RH.php";
        } else {
            $logoLink = "../Convidado/onboarding_convidado.php";
        }
    ?>
    <a href="<?php echo $logoLink; ?>">
        <img src="../../assets/tlantic-logo2.png" alt="Logo Tlantic" class="logo-header" style="cursor:pointer;">
    </a>
    <nav>
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

        <?php if ($_SESSION['profile'] === 'coordenador'): ?>
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
                    <span class="seta-baixo">▾</span>
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
                    <span class="seta-baixo">▾</span>
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
                    <span class="seta-baixo">▾</span>
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
                    <span class="seta-baixo">▾</span>
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
                <div class="dropdown-equipas">
                <a href="../RH/equipas.php" class="equipas-link">
                    Equipas
                    <span class="seta-baixo">&#9662;</span>
                </a>
                <div class="dropdown-menu">
                    <a href="../RH/relatorios.php">Relatórios</a>
                    <a href="../RH/dashboard_rh.php">Dashboard</a>
                </div>
            </div>
            <div class="dropdown-colaboradores">
                <a href="../RH/colaboradores_gerir.php" class="colaboradores-link">
                    Colaboradores
                    <span class="seta-baixo">&#9662;</span>
                </a>
                <div class="dropdown-menu">
                    <a href="../RH/exportar.php">Exportar</a>
                </div>
            </div>
            <div class="dropdown-gestao">
                <a href="#" class="gestao-link">
                    Gestão
                    <span class="seta-baixo">&#9662;</span>
                </a>
                <div class="dropdown-menu">
                    <a href="../RH/gerir_beneficios.php">Gerir Benefícios</a>
                    <a href="../RH/gerir_formacoes.php">Gerir Formações</a>
                    <a href="../RH/gerir_recibos.php">Submeter Recibos</a>
                    <a href="../RH/campos_personalizados.php">Campos Personalizados</a>
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
        <?php else: ?>
            <a href="../Convidado/onboarding_convidado.php">Preencher Dados</a>
            <a href="../Comuns/logout.php">Sair</a>
        <?php endif; ?>
    </nav>
</header>
<main>
    <div class="portal-brand">
        <div class="color-bar">
            <div class="color-segment"></div>
            <div class="color-bar"></div>
            <div class="color-segment"></div>
        </div>
        <span class="portal-text">Portal Do Colaborador</span>
    </div>
    <h1>O Teu Perfil, <?php echo htmlspecialchars($user['nome'] ?? ''); ?>:</h1>
    
    <div class="perfil-edit-container">
        <form class="perfil-edit-form" autocomplete="off">
            <div class="ficha-grid">
                <div class="ficha-campo">
                    <label>Nome:</label>
                    <input type="text" name="nome" value="<?php echo htmlspecialchars($user['nome'] ?? ''); ?>" readonly>
                </div>
                <div class="ficha-campo">
                    <label>Email:</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" readonly>
                </div>
            </div>
            <button id="btnAlterarPassword" class="btn" type="button">Alterar Palavra-passe</button>
        </form>
    </div>

    <!-- Modal de alteração de palavra-passe -->
    <div id="modalAlterarPassword" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close" id="fecharModalAlterarPassword">×</span>
            <h2>Alterar Palavra-passe</h2>
            <form method="post" action="alterar_password.php" autocomplete="off" id="formAlterarPassword">
                <label for="password_atual">Palavra-passe atual:</label>
                <input type="password" name="password_atual" id="password_atual" required minlength="6">
                <label for="nova_password">Nova palavra-passe:</label>
                <input type="password" name="nova_password" id="nova_password" required minlength="6">
                <label for="confirmar_password">Confirmar nova palavra-passe:</label>
                <input type="password" name="confirmar_password" id="confirmar_password" required minlength="6">
                <div id="pw-msg" style="margin-top: 10px;"></div>
                <button type="submit" class="btn">Alterar</button>
            </form>
        </div>
    </div>

    <!-- Blocos de formações e férias -->
    <?php if ($_SESSION['profile'] !== 'rh'): ?>
    <div class="perfil-info-blocks">
        <div class="info-block formacoes-block">
            <div class="info-block-header">
                <h3>📚 Formações Inscritas</h3>
                <span class="info-count"><?php echo count($formacoesInscritas); ?></span>
            </div>
            <div class="info-block-content">
                <?php if (!empty($formacoesInscritas)): ?>
                    <p>Tens <?php echo count($formacoesInscritas); ?> formação<?php echo count($formacoesInscritas) > 1 ? 'ões' : ''; ?> programada<?php echo count($formacoesInscritas) > 1 ? 's' : ''; ?>.</p>
                    <button class="btn-ver-calendario" onclick="abrirModalFormacoes()">Ver Calendário</button>
                <?php else: ?>
                    <p>Não tens formações inscritas no momento.</p>
                <?php endif; ?>
            </div>
        </div>
        <div class="info-block ferias-block">
            <div class="info-block-header">
                <h3>🏖️ Pedidos de Férias</h3>
                <span class="info-count"><?php echo count($pedidosFerias); ?></span>
            </div>
            <div class="info-block-content">
                <?php if (!empty($pedidosFerias)): ?>
                    <p>Tens <?php echo count($pedidosFerias); ?> pedido<?php echo count($pedidosFerias) > 1 ? 's' : ''; ?> de férias.</p>
                    <button class="btn-ver-calendario" onclick="abrirModalFerias()">Ver Calendário</button>
                <?php else: ?>
                    <p>Não tens pedidos de férias registados.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
</main>

<!-- Modal Calendário Férias -->
<div class="modal-calendario-bg" id="modal-ferias-bg" style="display:none;">
    <div class="modal-calendario">
        <button class="fechar-modal-calendario" onclick="fecharModalFerias()">×</button>
        <h2>🏖️ Calendário de Férias</h2>
        <div id="calendario-ferias" class="calendario-container">
            <?php if (!empty($pedidosFerias)): ?>
                <?php foreach ($pedidosFerias as $pedido): ?>
                    <div class="evento-ferias <?php echo strtolower($pedido['status'] ?? 'pendente'); ?>" 
                         data-inicio="<?php echo htmlspecialchars($pedido['data_inicio']); ?>"
                         data-fim="<?php echo htmlspecialchars($pedido['data_fim']); ?>">
                        <div class="evento-titulo">Pedido de Férias #<?php echo $pedido['id']; ?></div>
                        <div class="evento-data">
                            <strong>De:</strong> <?php echo date('d/m/Y', strtotime($pedido['data_inicio'])); ?><br>
                            <strong>Até:</strong> <?php echo date('d/m/Y', strtotime($pedido['data_fim'])); ?>
                        </div>
                        <div class="evento-status">
                            <strong>Estado:</strong> 
                            <span class="status-<?php echo strtolower($pedido['estado'] ?? 'pendente'); ?>">
                                <?php 
                                $estado = strtolower(trim($pedido['estado'] ?? 'pendente'));
                                switch($estado) {
                                    case 'aceite':
                                    case 'aprovado':
                                    case 'aprovada':
                                        echo '✅ Aprovado';
                                        break;
                                    case 'recusado':
                                    case 'rejeitado':
                                    case 'recusada':
                                        echo '❌ Recusado';
                                        break;
                                    case 'pendente':
                                    default:
                                        echo '⏳ Pendente';
                                        break;
                                }
                                ?>
                            </span>
                        </div>
                        <?php if (!empty($pedido['observacoes'])): ?>
                        <div class="evento-observacoes">
                            <strong>Observações:</strong> <?php echo htmlspecialchars($pedido['observacoes']); ?>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($pedido['data_pedido'])): ?>
                            <div class="evento-data-pedido">
                                <strong>Pedido feito em:</strong> <?php echo date('d/m/Y H:i', strtotime($pedido['data_pedido'])); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="calendario-vazio">
                    <p>Não tens pedidos de férias registados.</p>
                    <p style="font-size: 0.8rem; color: #666; margin-top: 8px;">
                        Debug Info:<br>
                        User ID: <?php echo $_SESSION['user_id']; ?><br>
                        Colaborador ID: <?php echo $colaborador_id ?: 'null'; ?><br>
                        Colaborador encontrado: <?php echo $colab ? 'SIM' : 'NÃO'; ?><br>
                        <?php if ($colab): ?>
                            Colaborador.id: <?php echo $colab['id'] ?? 'NULL'; ?><br>
                            Colaborador.utilizador_id: <?php echo $colab['utilizador_id'] ?? 'NULL'; ?>
                        <?php endif; ?>
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal Calendário Formações -->
<div class="modal-calendario-bg" id="modal-formacoes-bg" style="display:none;">
    <div class="modal-calendario">
        <button class="fechar-modal-calendario" onclick="fecharModalFormacoes()">×</button>
        <h2>📚 Calendário de Formações</h2>
        <div id="calendario-formacoes" class="calendario-container">
            <?php if (!empty($formacoesInscritas)): ?>
                <?php foreach ($formacoesInscritas as $formacao): ?>
                    <div class="evento-formacao" 
                         data-inicio="<?php echo htmlspecialchars($formacao['data_inicio']); ?>"
                         data-fim="<?php echo htmlspecialchars($formacao['data_fim']); ?>">
                        <div class="evento-titulo"><?php echo htmlspecialchars($formacao['nome']); ?></div>
                        <div class="evento-data">
                            <?php echo date('d/m/Y', strtotime($formacao['data_inicio'])); ?> - 
                            <?php echo date('d/m/Y', strtotime($formacao['data_fim'])); ?>
                        </div>
                        <div class="evento-descricao"><?php echo htmlspecialchars($formacao['descricao'] ?? ''); ?></div>
                        <?php if (!empty($formacao['data_inscricao'])): ?>
                            <div class="evento-inscricao">
                                Inscrito em: <?php echo date('d/m/Y', strtotime($formacao['data_inscricao'])); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="calendario-vazio">
                    <p>Não tens formações inscritas no momento.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.getElementById('btnAlterarPassword').onclick = function() {
    document.getElementById('modalAlterarPassword').style.display = 'flex';
};

function fecharModalAlterarPassword() {
    document.getElementById('modalAlterarPassword').style.display = 'none';
    document.getElementById('formAlterarPassword').reset();
    document.getElementById('pw-msg').textContent = '';
}

document.getElementById('fecharModalAlterarPassword').onclick = function() {
    fecharModalAlterarPassword();
};

document.getElementById('formAlterarPassword').onsubmit = function(e) {
    e.preventDefault();
    const atual = this.password_atual.value;
    const nova = this.nova_password.value;
    const nova2 = this.confirmar_password.value;
    const msg = document.getElementById('pw-msg');
    
    msg.style.color = '#b00';
    if (nova !== nova2) {
        msg.textContent = 'A nova palavra-passe e a confirmação não coincidem.';
        return;
    }

    fetch('alterar_password.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ atual, nova })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            msg.style.color = '#23408e';
            msg.textContent = 'Palavra-passe alterada com sucesso!';
            setTimeout(fecharModalAlterarPassword, 1500);
        } else {
            msg.textContent = data.error || 'Erro ao alterar palavra-passe.';
        }
    })
    .catch(err => {
        msg.textContent = 'Erro ao comunicar com o servidor.';
        console.error('Erro:', err);
    });
};

function abrirModalFormacoes() {
    document.getElementById('modal-formacoes-bg').style.display = 'flex';
}

function fecharModalFormacoes() {
    document.getElementById('modal-formacoes-bg').style.display = 'none';
}

function abrirModalFerias() {
    document.getElementById('modal-ferias-bg').style.display = 'flex';
}

function fecharModalFerias() {
    document.getElementById('modal-ferias-bg').style.display = 'none';
}

window.onclick = function(event) {
    const modalFormacoes = document.getElementById('modal-formacoes-bg');
    const modalFerias = document.getElementById('modal-ferias-bg');
    const modalPassword = document.getElementById('modalAlterarPassword');
    
    if (event.target === modalFormacoes) {
        fecharModalFormacoes();
    }
    if (event.target === modalFerias) {
        fecharModalFerias();
    }
    if (event.target === modalPassword) {
        fecharModalAlterarPassword();
    }
};
</script>
</body>
</html>