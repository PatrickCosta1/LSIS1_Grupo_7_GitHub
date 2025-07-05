<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['profile'] !== 'admin') {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/Admin/BLL_utilizadores.php';
$utilBLL = new AdminUtilizadoresManager();
$perfis = $utilBLL->getPerfis();

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $perfil_id = $_POST['perfil_id'];
    $ativo = isset($_POST['ativo']) ? 1 : 0;
    $password = $_POST['password'];

    if ($utilBLL->addUtilizador($nome, $username, $email, $perfil_id, $ativo, $password)) {
        $success = "Utilizador criado com sucesso!";
    } else {
        $error = "Erro ao criar utilizador.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Novo Utilizador</title>
    <link rel="stylesheet" href="../../assets/teste.css">
    <style>
        .novo-utilizador-container {
            max-width: 520px;
            margin: 48px auto 0 auto;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 4px 24px rgba(3,96,233,0.10);
            padding: 36px 38px 32px 38px;
        }

        .novo-utilizador-container h1 {
            font-size: 2rem;
            color: #0360e9;
            margin-bottom: 28px;
            text-align: center;
        }

        .novo-utilizador-container form label {
            display: block;
            margin-bottom: 18px;
            color: #1c3c69;
            font-weight: 500;
            font-size: 1rem;
        }

        .novo-utilizador-container input[type="text"],
        .novo-utilizador-container input[type="email"],
        .novo-utilizador-container input[type="password"],
        .novo-utilizador-container select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #c3cfe2;
            border-radius: 8px;
            font-size: 1rem;
            margin-top: 6px;
            background: #f5f7fa;
            color: #1c3c69;
            transition: border 0.2s;
        }

        .novo-utilizador-container input[type="text"]:focus,
        .novo-utilizador-container input[type="email"]:focus,
        .novo-utilizador-container input[type="password"]:focus,
        .novo-utilizador-container select:focus {
            border: 1.5px solid #0360e9;
            outline: none;
        }

        .novo-utilizador-container input[type="checkbox"] {
            accent-color: #0360e9;
            margin-left: 8px;
        }

        .novo-utilizador-container .btn {
            background: linear-gradient(135deg, #0360e9 0%, #299cf3 100%);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 10px 28px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
            margin-right: 8px;
            transition: background 0.2s;
            box-shadow: 0 2px 8px rgba(3,96,233,0.08);
            text-decoration: none;
            display: inline-block;
        }

        .novo-utilizador-container .btn:hover {
            background: linear-gradient(135deg, #1c3c69 0%, #0360e9 100%);
        }

        .success-message, .error-message {
            padding: 12px 18px;
            border-radius: 8px;
            margin-bottom: 18px;
            font-weight: 600;
            text-align: center;
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
            .novo-utilizador-container {
                padding: 16px 6px;
            }
            .novo-utilizador-container h1 {
                font-size: 1.2rem;
            }
        }
</style>
</head>


<body>
    <div class="novo-utilizador-container">
        <h1>Novo Utilizador</h1>

        <?php if ($success): ?>
            <div class="success-message"><?php echo $success; ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <label>Nome:
                <input type="text" name="nome" required>
            </label>

            <label>Username:
                <input type="text" name="username" required>
            </label>

            <label>Email:
                <input type="email" name="email" required>
            </label>

            <label>Perfil:
                <select name="perfil_id" required>
                    <?php foreach ($perfis as $p): ?>
                        <option value="<?php echo $p['id']; ?>"><?php echo htmlspecialchars($p['nome']); ?></option>
                    <?php endforeach; ?>
                </select>
            </label>

            <label>Ativo:
                <input type="checkbox" name="ativo" checked>
            </label>

            <label>Password:
                <input type="password" name="password" required>
            </label>

            <button type="submit" class="btn">Criar</button>
            <a href="utilizadores.php" class="btn">Voltar</a>
        </form>

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