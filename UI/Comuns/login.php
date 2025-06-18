<?php
require_once '../../BLL/Comuns/BLL_login.php';

session_start();

$error_message = '';
$success_message = '';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

// Process login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Basic validation
    if (empty($username) || empty($password)) {
        $error_message = 'Por favor, preencha todos os campos obrigatórios.';
    } elseif (strlen($username) < 3) {
        $error_message = 'O nome de utilizador deve ter pelo menos 3 caracteres.';
    } elseif (strlen($password) < 6) {
        $error_message = 'A palavra-passe deve ter pelo menos 6 caracteres.';
    } else {
        try {
            $auth = new Authenticator();
            $user = $auth->login($username, $password);
            
            if ($user) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['profile'] = $user['profile'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['last_login'] = date('Y-m-d H:i:s');
                
                // Redirect based on user profile
                switch ($user['profile']) {
                    case 'guest':
                        header('Location: guest_dashboard.php');
                        break;
                    case 'employee':
                        header('Location: employee_dashboard.php');
                        break;
                    case 'coordinator':
                        header('Location: coordinator_dashboard.php');
                        break;
                    case 'hr':
                        header('Location: hr_dashboard.php');
                        break;
                    case 'admin':
                        header('Location: admin_dashboard.php');
                        break;
                    default:
                        header('Location: index.php');
                }
                exit();
            } else {
                $error_message = 'Credenciais inválidas. Verifique o utilizador e palavra-passe.';
            }
        } catch (Exception $e) {
            error_log('Login error: ' . $e->getMessage());
            $error_message = 'Erro interno do sistema. Tente novamente mais tarde.';
        }
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
    <link rel="stylesheet" href="../../assets/styles.css">
</head>

<body>
    <div class="login-container">
        <img src="../../assets/tlantic-logo.png" alt="Logo Tlantic" class="logo-img" />
        <h1 class="welcome-text">Bem-vindo</h1>
        <p class="subtitle">Portal do Colaborador</p>
        <p class="company-info">Tlantic - Unlocking Smart Business</p>
  
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

        <div class="security-info">
            <i class="fas fa-shield-alt"></i>
            <strong>Acesso Seguro:</strong> O seu perfil de acesso é automaticamente determinado com base nas suas credenciais. Todas as sessões são monitorizadas por segurança.
        </div>

        <div class="footer">
            <p>&copy; <?php echo date('Y'); ?> Tlantic. Todos os direitos reservados.</p>
            <p class="version">Portal de Colaboradores v1.0 | Sistema LSIS1</p>
        </div>
    </div>
    <script src="../assets/login.js"></script>

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
</body>
</html>