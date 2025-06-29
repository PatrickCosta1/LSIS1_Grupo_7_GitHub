<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['profile'] !== 'admin') {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/Admin/BLL_utilizadores.php';
$utilBLL = new AdminUtilizadoresManager();
$perfis = $utilBLL->getPerfis();

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: utilizadores.php');
    exit();
}
$util = $utilBLL->getUtilizadorById($id);

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $perfil_id = $_POST['perfil_id'];
    $ativo = isset($_POST['ativo']) ? 1 : 0;

    if ($utilBLL->updateUtilizador($id, $nome, $username, $email, $perfil_id, $ativo)) {
        $success = "Utilizador atualizado!";
        $util = $utilBLL->getUtilizadorById($id);
    } else {
        $error = "Erro ao atualizar utilizador.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Editar Utilizador</title>
    <link rel="stylesheet" href="../../assets/teste.css">
    <style>
    .editar-utilizador-container {
        max-width: 480px;
        margin: 40px auto 0 auto;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 24px rgba(3,96,233,0.10);
        padding: 36px 32px 32px 32px;
    }

    .editar-utilizador-container h1 {
        font-size: 1.7rem;
        color: #0360e9;
        margin-bottom: 22px;
        text-align: center;
        font-weight: 700;
    }

    .editar-utilizador-container label {
        display: block;
        margin-bottom: 14px;
        color: #0360e9;
        font-weight: 500;
        font-size: 1rem;
    }

    .editar-utilizador-container input[type="text"],
    .editar-utilizador-container input[type="email"],
    .editar-utilizador-container select {
        width: 100%;
        padding: 10px 12px;
        margin-top: 5px;
        margin-bottom: 18px;
        border: 1px solid #c3cfe2;
        border-radius: 8px;
        font-size: 1rem;
        background: #f5f7fa;
        color: #1c3c69;
        transition: border 0.2s;
    }

    .editar-utilizador-container input[type="text"]:focus,
    .editar-utilizador-container input[type="email"]:focus,
    .editar-utilizador-container select:focus {
        border-color: #0360e9;
        outline: none;
    }

    .editar-utilizador-container input[type="checkbox"] {
        accent-color: #0360e9;
        margin-right: 7px;
    }

    .editar-utilizador-container .btn {
        background: linear-gradient(135deg, #0360e9 0%, #299cf3 100%);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 10px 28px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        margin-right: 8px;
        margin-top: 10px;
        transition: background 0.2s;
        text-decoration: none;
        display: inline-block;
        box-shadow: 0 2px 8px rgba(3,96,233,0.08);
    }

    .editar-utilizador-container .btn:hover {
        background: linear-gradient(135deg, #1c3c69 0%, #0360e9 100%);
    }

    .success-message, .error-message {
        text-align: center;
        margin-bottom: 18px;
        padding: 12px 0;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1rem;
    }
    .success-message {
        background: #e6fffa;
        color: #0360e9;
        border: 1px solid #36b3e9;
    }
    .error-message {
        background: #ffeaea;
        color: #e53e3e;
        border: 1px solid #e53e3e;
    }

    @media (max-width: 600px) {
        .editar-utilizador-container { padding: 12px 4px; }
        .editar-utilizador-container h1 { font-size: 1.2rem; }
    }
    </style>
</head>
<body>
    <div class="editar-utilizador-container">
        <h1>Editar Utilizador</h1>
        <?php if ($success): ?><div class="success-message"><?php echo $success; ?></div><?php endif; ?>
        <?php if ($error): ?><div class="error-message"><?php echo $error; ?></div><?php endif; ?>
        <form method="POST">
            <label>Nome:
                <input type="text" name="nome" value="<?php echo htmlspecialchars($util['nome']); ?>" required>
            </label>
            <label>Username:
                <input type="text" name="username" value="<?php echo htmlspecialchars($util['username']); ?>" required>
            </label>
            <label>Email:
                <input type="email" name="email" value="<?php echo htmlspecialchars($util['email']); ?>" required>
            </label>
            <label>Perfil:
                <select name="perfil_id" required>
                    <?php foreach ($perfis as $p): ?>
                        <option value="<?php echo $p['id']; ?>" <?php if ($util['perfil_id'] == $p['id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($p['nome']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label>
                <input type="checkbox" name="ativo" <?php if ($util['ativo']) echo 'checked'; ?>>
                Ativo
            </label>
            <div style="text-align:center;">
                <button type="submit" class="btn">Guardar</button>
                <a href="utilizadores.php" class="btn" style="background:#ecebfa;color:#4a468a;">Voltar</a>
            </div>
        </form>
    </div>

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