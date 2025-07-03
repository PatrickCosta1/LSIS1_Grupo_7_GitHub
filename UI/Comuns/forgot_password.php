<?php
session_start();
require_once '../../BLL/Comuns/BLL_forgot_password.php';
$bll = new ForgotPasswordManager();

$step = 1;
$email = '';
$msg = '';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email'])) {
        $email = trim($_POST['email']);
        if ($bll->emailExiste($email)) {
            $bll->enviarCodigo($email);
            $_SESSION['fp_email'] = $email;
            $step = 2;
        } else {
            $erro = "Email não encontrado.";
        }
    } elseif (isset($_POST['codigo'])) {
        $codigo = trim($_POST['codigo']);
        $email = $_SESSION['fp_email'] ?? '';
        if ($bll->verificarCodigo($email, $codigo)) {
            $step = 3;
        } else {
            $erro = "Código inválido.";
            $step = 2;
        }
    } elseif (isset($_POST['nova_password'], $_POST['confirmar_password'])) {
        $email = $_SESSION['fp_email'] ?? '';
        $nova = $_POST['nova_password'];
        $conf = $_POST['confirmar_password'];
        if ($nova !== $conf) {
            $erro = "As palavras-passe não coincidem.";
            $step = 3;
        } elseif (strlen($nova) < 6) {
            $erro = "A palavra-passe deve ter pelo menos 6 caracteres.";
            $step = 3;
        } else {
            if ($bll->alterarPassword($email, $nova)) {
                unset($_SESSION['fp_email']);
                $msg = "Palavra-passe alterada com sucesso! Redirecionando...";
                header("refresh:2;url=login.php");
                $step = 4;
            } else {
                $erro = "Erro ao alterar palavra-passe.";
                $step = 3;
            }
        }
    }
} elseif (isset($_SESSION['fp_email'])) {
    $step = 2;
}

?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Palavra-passe</title>
    <link rel="stylesheet" href="../../assets/CSS/Comuns/forgot_password.css">
</head>
<body>
    <div class="forgot-container">
        <h2>Recuperar Palavra-passe</h2>
        <?php if ($erro): ?><div class="error"><?php echo htmlspecialchars($erro); ?></div><?php endif; ?>
        <?php if ($msg): ?><div class="success"><?php echo htmlspecialchars($msg); ?></div><?php endif; ?>

        <form method="post" autocomplete="off">
            <?php if ($step === 1): ?>
                <div class="info">Insira o seu email empresarial para receber um código de recuperação.</div>
                <label for="email">Email empresarial:</label>
                <input type="email" name="email" id="email" required autofocus placeholder="exemplo@empresa.pt">
                <button type="submit">Enviar código</button>
            <?php elseif ($step === 2): ?>
                <div class="info">Enviámos um código de 6 dígitos para o seu email.<br>Verifique a caixa de entrada e spam.</div>
                <label for="codigo">Código recebido no email:</label>
                <input type="text" name="codigo" id="codigo" maxlength="6" required autofocus pattern="\d{6}" placeholder="6 dígitos">
                <button type="submit">Validar código</button>
            <?php elseif ($step === 3): ?>
                <div class="info">Defina a sua nova palavra-passe.<br>Mínimo 6 caracteres.</div>
                <label for="nova_password">Nova palavra-passe:</label>
                <input type="password" name="nova_password" id="nova_password" required minlength="6" placeholder="Nova palavra-passe">
                <label for="confirmar_password">Confirmar palavra-passe:</label>
                <input type="password" name="confirmar_password" id="confirmar_password" required minlength="6" placeholder="Confirmar palavra-passe">
                <button type="submit">Alterar palavra-passe</button>
            <?php elseif ($step === 4): ?>
                <div class="success">Redirecionando para o login...</div>
                <div class="loading"><span>&#8635;</span></div>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
