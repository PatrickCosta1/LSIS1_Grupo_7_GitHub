<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['profile'] !== 'admin') {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/Admin/BLL_alertas.php';
$alertasBLL = new AdminAlertasManager();

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = trim($_POST['tipo'] ?? '');
    $mensagem = trim($_POST['mensagem'] ?? '');
    $periodicidade = intval($_POST['periodicidade_meses'] ?? 0);
    $ativo = isset($_POST['ativo']) ? 1 : 0;

    if ($tipo && $mensagem) {
        if ($alertasBLL->addAlerta($tipo, $mensagem, $periodicidade, $ativo, $_SESSION['user_id'])) {
            $success = "Alerta criado com sucesso!";
        } else {
            $error = "Erro ao criar alerta.";
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
    <title>Novo Alerta</title>
    <link rel="stylesheet" href="../../assets/style.css">
    <link rel="stylesheet" href="../../assets/teste.css">
    <style>
        .novo-alerta-container {
            max-width: 500px;
            margin: 48px auto 0 auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.10);
            padding: 36px 32px 32px 32px;
        }
        .novo-alerta-titulo {
            font-size: 1.6rem;
            color: #3a366b;
            margin-bottom: 22px;
            text-align: center;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .novo-alerta-form label {
            color: #4a468a;
            font-weight: 500;
            margin-bottom: 4px;
            font-size: 1rem;
        }
        .novo-alerta-form input[type="text"],
        .novo-alerta-form input[type="number"],
        .novo-alerta-form textarea {
            width: 100%;
            padding: 8px 10px;
            margin-bottom: 18px;
            border: 1px solid #d5d3f1;
            border-radius: 7px;
            font-size: 1rem;
            background: #f8f8fc;
            color: #3a366b;
            transition: border 0.2s;
        }
        .novo-alerta-form input[type="text"]:focus,
        .novo-alerta-form input[type="number"]:focus,
        .novo-alerta-form textarea:focus {
            border-color: #764ba2;
            outline: none;
        }
        .novo-alerta-form input[type="checkbox"] {
            margin-right: 7px;
        }
        .novo-alerta-form .btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border: none;
            border-radius: 7px;
            padding: 8px 22px;
            font-size: 1rem;
            cursor: pointer;
            margin-right: 8px;
            margin-top: 10px;
            transition: background 0.2s;
            text-decoration: none;
            display: inline-block;
        }
        .novo-alerta-form .btn:hover {
            background: linear-gradient(135deg, #5a67d8 0%, #6b47b6 100%);
        }
        .success-message, .error-message {
            text-align: center;
            margin-bottom: 18px;
            padding: 8px 0;
            border-radius: 6px;
            font-weight: 600;
        }
        .success-message { background: #e6fffa; color: #2c7a7b; }
        .error-message { background: #fff5f5; color: #c53030; }
        @media (max-width: 600px) {
            .novo-alerta-container { padding: 12px 4px; }
        }
    </style>
</head>
<body>
    <header>
        <img src="../../assets/tlantic-logo.png" alt="Logo Tlantic" class="logo-header">
        <nav>
            <a href="dashboard_admin.php">Dashboard</a>
            <a href="utilizadores.php">Utilizadores</a>
            <a href="permissoes.php">Permissões</a>
            <a href="campos_personalizados.php">Campos Personalizados</a>
            <a href="alertas.php">Alertas</a>
            <a href="../RH/colaboradores_gerir.php">Colaboradores</a>
            <a href="../RH/equipas.php">Equipas</a>
            <a href="../RH/relatorios.php">Relatórios</a>
            <a href="../Comuns/perfil.php">Perfil</a>
            <a href="../Comuns/logout.php">Sair</a>
        </nav>
    </header>
    <div class="novo-alerta-container">
        <div class="novo-alerta-titulo">Novo Alerta</div>
        <?php if ($success): ?><div class="success-message"><?php echo $success; ?></div><?php endif; ?>
        <?php if ($error): ?><div class="error-message"><?php echo $error; ?></div><?php endif; ?>
        <form method="POST" class="novo-alerta-form">
            <label>Tipo de Alerta:</label>
            <input type="text" name="tipo" required>

            <label>Mensagem:</label>
            <textarea name="mensagem" rows="3" required></textarea>

            <label>Periodicidade (meses):</label>
            <input type="number" name="periodicidade_meses" min="0" value="0">

            <label>
                <input type="checkbox" name="ativo" value="1" checked>
                Ativo
            </label>

            <div style="text-align:center; margin-top: 24px;">
                <button type="submit" class="btn">Criar Alerta</button>
                <a href="alertas.php" class="btn" style="background:#ecebfa;color:#4a468a;">Voltar</a>
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
