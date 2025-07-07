<?php
require_once '../../BLL/Comuns/BLL_login.php';
require_once '../../vendor/autoload.php'; // Para Google2FA e QRCode

session_start();

$error_message = '';
$success_message = '';
$show_2fa = false;
$show_2fa_setup = false;
$qrCodeDataUri = '';
$google2fa_secret = '';
$user_id_2fa = null;

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

// Process login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $otp = $_POST['otp'] ?? null;
    $user_id_2fa = $_POST['user_id_2fa'] ?? null;
    $reset_2fa = isset($_POST['reset_2fa']); // NOVO: botão para resetar 2FA

    $loginResult = null; // <-- Corrige o warning de variável indefinida

    try {
        $auth = new Authenticator();
        if ($user_id_2fa && $reset_2fa) {
            // Gerar novo secret e mostrar QR code
            $userRow = (new \DAL_Login())->getUserById($user_id_2fa);
            $google2fa = new \PragmaRX\Google2FA\Google2FA();
            $newSecret = $google2fa->generateSecretKey();
            (new \DAL_Login())->setGoogle2FASecret($user_id_2fa, $newSecret);
            $qrCodeUrl = $google2fa->getQRCodeUrl(
                'Portal Tlantic',
                $userRow['email'],
                $newSecret
            );
            $renderer = new \BaconQrCode\Renderer\ImageRenderer(
                new \BaconQrCode\Renderer\RendererStyle\RendererStyle(200),
                new \BaconQrCode\Renderer\Image\SvgImageBackEnd()
            );
            $writer = new \BaconQrCode\Writer($renderer);
            $qrCodeSvg = $writer->writeString($qrCodeUrl);
            $qrCodeDataUri = 'data:image/svg+xml;base64,' . base64_encode($qrCodeSvg);
            $show_2fa_setup = true;
            $google2fa_secret = $newSecret;
            $user_id_2fa = $user_id_2fa;
            // Não faz login neste fluxo, então $loginResult permanece null
        } elseif ($user_id_2fa && $otp) {
            // Segunda etapa: validar OTP
            $userRow = (new \DAL_Login())->getUserById($user_id_2fa);
            $loginResult = $auth->login($userRow['username'], $password ?: $userRow['password'], $otp);
        } else {
            $loginResult = $auth->login($username, $password, $otp);
        }

        if (is_array($loginResult) && isset($loginResult['2fa_setup'])) {
            // Primeira vez: mostrar QR Code para configurar
            $show_2fa_setup = true;
            $google2fa_secret = $loginResult['secret'];
            $user_id_2fa = $loginResult['user_id'];
            // Gerar QR Code (SVG, não requer imagick)
            $google2fa = new \PragmaRX\Google2FA\Google2FA();
            $qrCodeUrl = $google2fa->getQRCodeUrl(
                'Portal Tlantic',
                $loginResult['email'],
                $google2fa_secret
            );
            $renderer = new \BaconQrCode\Renderer\ImageRenderer(
                new \BaconQrCode\Renderer\RendererStyle\RendererStyle(200),
                new \BaconQrCode\Renderer\Image\SvgImageBackEnd()
            );
            $writer = new \BaconQrCode\Writer($renderer);
            $qrCodeSvg = $writer->writeString($qrCodeUrl);
            $qrCodeDataUri = 'data:image/svg+xml;base64,' . base64_encode($qrCodeSvg);
        } elseif (is_array($loginResult) && isset($loginResult['2fa_required'])) {
            // Já tem 2FA, pedir código
            $show_2fa = true;
            $user_id_2fa = $loginResult['user_id'];
        } elseif ($loginResult && is_array($loginResult)) {
            // Login completo
            $_SESSION['user_id'] = $loginResult['id'];
            $_SESSION['username'] = $loginResult['username'];
            $_SESSION['profile'] = $loginResult['profile'];
            $_SESSION['name'] = $loginResult['name'];
            $_SESSION['last_login'] = date('Y-m-d H:i:s');

            // Redirecionamento por perfil
            switch ($loginResult['profile']) {
                case 'guest':
                case 'convidado':
                    header('Location: ../Convidado/dashboard_convidado.php');
                    break;
                case 'employee':
                case 'colaborador':
                    header('Location: ../Colaborador/pagina_inicial_colaborador.php');
                    break;
                case 'coordinator':
                case 'coordenador':
                    header('Location: ../Coordenador/pagina_inicial_coordenador.php');
                    break;
                case 'hr':
                case 'rh':
                    header('Location: ../RH/pagina_inicial_RH.php');
                    break;
                case 'admin':
                    header('Location: ../Admin/pagina_inicial_admin.php');
                    break;
                default:
                    header('Location: ../index.php');
            }
            exit();
        } else {
            $error_message = 'Credenciais inválidas, código 2FA inválido ou conta inativa.';
        }
    } catch (Exception $e) {
        error_log('Login error: ' . $e->getMessage());
        $error_message = 'Erro interno do sistema. Tente novamente mais tarde.';
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal de Colaboradores - Tlantic</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/CSS/Comuns/login.css">
</head>

<body>
    <div class="login-container">
        <img src="../../assets/tlantic-logo-escuro.png" alt="Logo Tlantic" class="logo-img" />
        <h1 class="welcome-text">Bem-vindo</h1>
        <p class="subtitle">Portal do Colaborador</p>
        <p class="company-info">Tlantic - Unlocking Smart Business</p>

        <?php if ($show_2fa_setup): ?>
            <div class="twofa-setup">
                <h3>Configuração do Google Authenticator</h3>
                <p>Escaneie o QR Code abaixo com o Google Authenticator e insira o código gerado:</p>
                <img src="<?php echo $qrCodeDataUri; ?>" alt="QR Code 2FA">
                <form method="POST" action="" id="twofaSetupForm" autocomplete="off">
                    <input type="hidden" name="user_id_2fa" value="<?php echo htmlspecialchars($user_id_2fa); ?>">
                    <label for="otp">Código 2FA:</label>
                    <input type="text" name="otp" id="otp" required pattern="\d{6}" maxlength="6" autofocus>
                    <button type="submit" class="login-btn">Validar 2FA</button>
                </form>
            </div>
        <?php elseif ($show_2fa): ?>
            <div class="twofa-prompt">
                <h3>Autenticação 2FA</h3>
                <form method="POST" action="" id="twofaPromptForm" autocomplete="off">
                    <input type="hidden" name="user_id_2fa" value="<?php echo htmlspecialchars($user_id_2fa); ?>">
                    <label for="otp">Código 2FA:</label>
                    <input type="text" name="otp" id="otp" required pattern="\d{6}" maxlength="6" autofocus>
                    <button type="submit" class="login-btn">Validar 2FA</button>
                </form>
                <form method="POST" action="" style="margin-top:10px;">
                    <input type="hidden" name="user_id_2fa" value="<?php echo htmlspecialchars($user_id_2fa); ?>">
                    <button type="submit" name="reset_2fa" class="login-btn" style="background:#e6eaf7;color:#0360e9;">Ler novo QR Code</button>
                </form>
                <div style="margin-top:8px;font-size:0.95em;color:#888;">Problemas com o código? Associe novamente o Google Authenticator.</div>
            </div>
        <?php else: ?>
            <form method="POST" action="" id="loginForm" autocomplete="on">
                <?php if (!empty($error_message)): ?>
                    <div class="error-message">
                        <i class="fas fa-exclamation-triangle"></i>
                        <?php echo htmlspecialchars($error_message); ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($success_message)): ?>
                    <div class="success-message">
                        <i class="fas fa-check-circle"></i>
                        <?php echo htmlspecialchars($success_message); ?>
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <label for="username">Utilizador</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user input-icon"></i>
                        <input type="text" 
                               id="username" 
                               name="username" 
                               required 
                               minlength="3"
                               placeholder="Digite o seu nome de utilizador"
                               value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                               autocomplete="username">
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Palavra-passe</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               required 
                               minlength="6"
                               placeholder="Digite a sua palavra-passe"
                               autocomplete="current-password">
                    </div>
                </div>

                <div class="forgot-password">
                    <a href="forgot_password.php">Esqueceu a palavra-passe?</a>
                </div>

                <button type="submit" class="login-btn" id="loginBtn">
                    <i class="fas fa-sign-in-alt"></i>
                    Iniciar Sessão
                </button>
            </form>
        <?php endif; ?>

        <div class="security-info">
            <i class="fas fa-shield-alt"></i>
            <strong>Acesso Seguro:</strong> O seu perfil de acesso é automaticamente determinado com base nas suas credenciais. Todas as sessões são monitorizadas por segurança.
        </div>

        <div class="footer">
            <p>&copy; <?php echo date('Y'); ?> Tlantic. Todos os direitos reservados.</p>
            <p class="version">Portal de Colaboradores v1.0 | Sistema ABMP</p>
        </div>
    </div>
    <script src="../../assets/login.js"></script> <!-- Corrigido caminho -->


</body>
</html>

<?php
// Após login bem-sucedido, redirecionamento já está correto.
// Para garantir navegação correta, ajuste os menus em cada dashboard/ficha conforme o perfil do utilizador.

// Exemplo de menu dinâmico (coloque este bloco PHP no início de cada página protegida):
/*
$menu = [];
switch ($_SESSION['profile']) {
    case 'admin':
        $menu = [
            'Dashboard' => '../Admin/dashboard_admin.php',
            'Utilizadores' => '../Admin/utilizadores.php',
            'Permissões' => '../Admin/permissoes.php',
            'Campos Personalizados' => '../Admin/campos_personalizados.php',
            'Alertas' => '../Admin/alertas.php',
            'Colaboradores' => '../Admin/colaboradores_gerir.php',
            'Equipas' => '../Admin/equipas.php',
            'Relatórios' => '../Admin/relatorios.php',
            'Perfil' => '../Comuns/perfil.php',
            'Sair' => '../Comuns/logout.php'
        ];
        break;
    case 'rh':
        $menu = [
            'Dashboard' => '../RH/dashboard_rh.php',
            'Colaboradores' => '../RH/colaboradores_gerir.php',
            'Equipas' => '../RH/equipas.php',
            'Relatórios' => '../RH/relatorios.php',
            'Exportar' => '../RH/exportar.php',
            'Notificações' => '../Comuns/notificacoes.php',
            'Perfil' => '../Comuns/perfil.php',
            'Sair' => '../Comuns/logout.php'
        ];
        break;
    case 'coordenador':
        $menu = [
            'Dashboard' => '../Coordenador/dashboard_coordenador.php',
            'Minha Ficha' => '../Colaborador/ficha_colaborador.php',
            'Minha Equipa' => '../Coordenador/equipa.php',
            'Relatórios Equipa' => '../Coordenador/relatorios_equipa.php',
            'Notificações' => '../Comuns/notificacoes.php',
            'Perfil' => '../Comuns/perfil.php',
            'Sair' => '../Comuns/logout.php'
        ];
        break;
    case 'colaborador':
        $menu = [
            'Dashboard' => '../Colaborador/dashboard_colaborador.php',
            'Minha Ficha' => '../Colaborador/ficha_colaborador.php',
            'Notificações' => '../Comuns/notificacoes.php',
            'Perfil' => '../Comuns/perfil.php',
            'Sair' => '../Comuns/logout.php'
        ];
        break;
    case 'convidado':
        $menu = [
            'Preencher Dados' => '../Convidado/onboarding_convidado.php',
            'Sair' => '../Comuns/logout.php'
        ];
        break;
}
*/

// Para mostrar o menu:
/*
<nav>
<?php foreach ($menu as $label => $url): ?>
    <a href="<?php echo $url; ?>"><?php echo $label; ?></a>
<?php endforeach; ?>
</nav>
*/