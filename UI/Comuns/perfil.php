<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/Comuns/BLL_perfil.php';
$userBLL = new PerfilManager();
$user = $userBLL->getUserById($_SESSION['user_id']);
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
</script>
</body>
</html>