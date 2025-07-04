<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['profile'], ['rh', 'admin'])) {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/RH/BLL_colaboradores_gerir.php';
require_once '../../BLL/Comuns/BLL_notificacoes.php';

$colabBLL = new RHColaboradoresManager();
$notBLL = new NotificacoesManager();

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email_pessoal = trim($_POST['email_pessoal'] ?? '');
    $data_inicio = $_POST['data_inicio_contrato'] ?? '';
    $perfil_destino_id = intval($_POST['perfil_destino_id'] ?? 2); // 2=colaborador por default

    if ($nome && $email_pessoal && $data_inicio && $perfil_destino_id) {
        // 1. Criar utilizador convidado
        $username = strtolower(preg_replace('/[^a-z0-9]/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $nome))) . rand(100,999);
        $password = bin2hex(random_bytes(4));
        $perfil_convidado = 5; // Exemplo: se o id do perfil convidado for 5

        $userId = $colabBLL->criarUtilizadorConvidado($username, $perfil_convidado, $password);
        if ($userId) {
            // 2. Gerar token e criar onboarding_temp
            $token = bin2hex(random_bytes(24));
            $ok = $colabBLL->criarOnboardingTemp([
                'nome' => $nome,
                'email_pessoal' => $email_pessoal,
                'data_inicio_contrato' => $data_inicio,
                'perfil_destino_id' => $perfil_destino_id,
                'token' => $token,
                'utilizador_id' => $userId
            ]);
            if ($ok) {
                // 3. Enviar email com link de onboarding
                $link = "http://localhost/LSIS1_Grupo_7_GitHub/UI/Convidado/onboarding_convidado.php?token=$token";
                $mensagem = "Olá $nome,<br><br>Foi iniciado o seu processo de onboarding na Tlantic.<br>
                Por favor, aceda ao seguinte link para preencher os seus dados:<br>
                <a href='$link'>$link</a><br><br>Obrigado.";
                $notBLL->enviarEmailSimples($email_pessoal, "Onboarding Tlantic", $mensagem);
                $success = "Colaborador convidado criado e link enviado para o email pessoal.";
            } else {
                $error = "Erro ao criar registo de onboarding temporário.";
            }
        } else {
            $error = "Erro ao criar utilizador convidado.";
        }
    } else {
        $error = "Preencha todos os campos obrigatórios.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Novo Colaborador (Onboarding)</title>
    <link rel="stylesheet" href="../../assets/CSS/RH/colaborador_novo.css">
</head>
<body>
    <header>
    <img src="../../assets/tlantic-logo2.png" alt="Logo Tlantic" class="logo-header" style="cursor:pointer;" onclick="window.location.href='equipas.php';">
    <nav>
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
                    <a href="gerir_recibos.php">Submeter Recibos</a>
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
    </nav>
</header>
<div class="novo-colaborador-container">
    <h1>Adicionar Novo Colaborador (Onboarding)</h1>
    <?php if ($success): ?>
        <div class="success-message"><?= $success ?></div>
    <?php elseif ($error): ?>
        <div class="error-message"><?= $error ?></div>
    <?php endif; ?>
    <form method="POST">
        <label>Nome Completo:
            <input type="text" name="nome" required>
        </label>
        <label>Email Pessoal:
            <input type="email" name="email_pessoal" required>
        </label>
        <label>Data de Início de Contrato:
            <input type="date" name="data_inicio_contrato" required>
        </label>
        <label>Tipo de Perfil:
            <select name="perfil_destino_id" required>
                <option value="2">Colaborador</option>
                <option value="3">Coordenador</option>
                <option value="4">RH</option>
            </select>
        </label>
        <button type="submit" class="btn">Criar e Enviar Link</button>
    </form>
</div>
</body>
</html>