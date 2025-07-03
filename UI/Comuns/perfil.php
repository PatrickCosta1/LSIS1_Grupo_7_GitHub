<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/Comuns/BLL_perfil.php';
$userBLL = new PerfilManager();
$user = $userBLL->getUserById($_SESSION['user_id']);

// Buscar dados do colaborador para formações e férias usando o BLL do perfil
$colab = $userBLL->getColaboradorByUserId($_SESSION['user_id']);
$colaborador_id = $colab['id'] ?? null;

$formacoesInscritas = [];
$pedidosFerias = [];

// Debug melhorado
error_log("=== DEBUG PERFIL INICIAL ===");
error_log("User ID (sessão): " . $_SESSION['user_id']);
error_log("Colaborador encontrado: " . ($colab ? 'SIM' : 'NÃO'));
if ($colab) {
    error_log("Colaborador dados: " . print_r($colab, true));
    error_log("Colaborador ID extraído: " . $colaborador_id);
}

if ($colaborador_id) {
    try {
        // Buscar formações e férias usando os métodos do perfil BLL
        $formacoesInscritas = $userBLL->getFormacoesPorColaborador($colaborador_id);
        $pedidosFerias = $userBLL->getPedidosFeriasPorColaborador($colaborador_id);
        
        // Debug FINAL detalhado
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
        <!-- Mantém o teu menu conforme o perfil -->
        <?php if ($_SESSION['profile'] === 'colaborador'): ?>
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
        <?php elseif ($_SESSION['profile'] === 'coordenador'): ?>
            <?php
                // Get coordinator's team link
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
                    <a href="../Colaborador/ficha_colaborador.php">Ficha Colaborador</a>
                    <a href="../Colaborador/beneficios.php">Benefícios</a>
                    <a href="../Colaborador/ferias.php">Férias</a>
                    <a href="../Colaborador/formacoes.php">Formações</a>
                    <a href="../Colaborador/recibos.php">Recibos</a>
                    <!-- Adiciona mais opções se quiseres -->
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
        <?php /* ...outros perfis... */ endif; ?>
    </nav>
</header>
<main>
    <div class="portal-brand">
        <div class="color-bar">
            <div class="color-segment"></div>
            <div class="color-segment"></div>
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
            <div class="perfil-edit-actions">
                <button type="button" class="btn" id="abrir-modal-pw">Alterar palavra-passe</button>
            </div>
        </form>
    </div>

    <!-- Novos blocos de formações e férias -->
    <div class="perfil-info-blocks">
        <!-- Bloco Formações -->
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

        <!-- Bloco Pedidos de Férias -->
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
                    <!-- Debug info temporário -->
                    <p style="font-size: 0.8rem; color: #666; margin-top: 8px;">
                        Debug: User ID = <?php echo $_SESSION['user_id']; ?><br>
                        Colaborador ID = <?php echo $colaborador_id ?: 'null'; ?><br>
                        Colaborador encontrado = <?php echo $colab ? 'SIM' : 'NÃO'; ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<!-- Modal de alteração de palavra-passe -->
<div class="modal-pw-bg" id="modal-pw-bg" style="display:none;">
    <div class="modal-pw">
        <button class="fechar-modal-pw" onclick="fecharModalPw()">×</button>
        <h2>Alterar Palavra-passe</h2>
        <form id="form-alterar-pw" autocomplete="off">
            <div class="ficha-campo">
                <label>Palavra-passe atual:</label>
                <input type="password" name="pw_atual" required>
            </div>
            <div class="ficha-campo">
                <label>Nova palavra-passe:</label>
                <input type="password" name="pw_nova" required>
            </div>
            <div class="ficha-campo">
                <label>Confirmar nova palavra-passe:</label>
                <input type="password" name="pw_nova_confirma" required>
            </div>
            <div class="perfil-edit-actions">
                <button type="submit" class="btn">Confirmar Alterações</button>
            </div>
            <div id="pw-msg" style="margin-top:12px;font-size:0.98rem;"></div>
        </form>
    </div>
</div>

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
                            <span class="status-<?php echo strtolower($pedido['status'] ?? 'pendente'); ?>">
                                <?php 
                                $status = $pedido['status'] ?? 'pendente';
                                switch(strtolower($status)) {
                                    case 'aceite':
                                    case 'aprovado':
                                        echo '✅ Aprovado';
                                        break;
                                    case 'recusado':
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
                        <?php if (!empty($pedido['data_pedido'])): ?>
                            <div class="evento-data-pedido">
                                <strong>Pedido feito em:</strong> <?php echo date('d/m/Y H:i', strtotime($pedido['data_pedido'])); ?>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($pedido['observacoes'])): ?>
                            <div class="evento-observacoes">
                                <strong>Observações:</strong> <?php echo htmlspecialchars($pedido['observacoes']); ?>
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
document.getElementById('abrir-modal-pw').onclick = function() {
    document.getElementById('modal-pw-bg').style.display = 'flex';
};
function fecharModalPw() {
    document.getElementById('modal-pw-bg').style.display = 'none';
    document.getElementById('pw-msg').textContent = '';
    document.getElementById('form-alterar-pw').reset();
}
document.getElementById('form-alterar-pw').onsubmit = function(e) {
    e.preventDefault();
    const atual = this.pw_atual.value;
    const nova = this.pw_nova.value;
    const nova2 = this.pw_nova_confirma.value;
    const msg = document.getElementById('pw-msg');
    msg.style.color = "#b00";
    if (nova !== nova2) {
        msg.textContent = "A nova palavra-passe e a confirmação não coincidem.";
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
            msg.style.color = "#23408e";
            msg.textContent = "Palavra-passe alterada com sucesso!";
            setTimeout(fecharModalPw, 1500);
        } else {
            msg.textContent = data.error || "Erro ao alterar palavra-passe.";
        }
    });
};

// Funções para modais de calendário
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

// Fechar modais ao clicar fora
window.onclick = function(event) {
    const modalFormacoes = document.getElementById('modal-formacoes-bg');
    const modalFerias = document.getElementById('modal-ferias-bg');
    
    if (event.target === modalFormacoes) {
        fecharModalFormacoes();
    }
    if (event.target === modalFerias) {
        fecharModalFerias();
    }
};
</script>
</body>
</html>