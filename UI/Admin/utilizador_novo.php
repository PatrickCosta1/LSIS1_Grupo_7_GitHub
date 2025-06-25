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
</head>
<body>
    <h1>Novo Utilizador</h1>
    <?php if ($success): ?><div class="success-message"><?php echo $success; ?></div><?php endif; ?>
    <?php if ($error): ?><div class="error-message"><?php echo $error; ?></div><?php endif; ?>
    <form method="POST">
        <label>Nome: <input type="text" name="nome" required></label><br>
        <label>Username: <input type="text" name="username" required></label><br>
        <label>Email: <input type="email" name="email" required></label><br>
        <label>Perfil:
            <select name="perfil_id" required>
                <?php foreach ($perfis as $p): ?>
                    <option value="<?php echo $p['id']; ?>"><?php echo htmlspecialchars($p['nome']); ?></option>
                <?php endforeach; ?>
            </select>
        </label><br>
        <label>Ativo: <input type="checkbox" name="ativo" checked></label><br>
        <label>Password: <input type="password" name="password" required></label><br>
        <button type="submit" class="btn">Criar</button>
    </form>
    <a href="utilizadores.php" class="btn">Voltar</a>

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