<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['profile'] !== 'admin') {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/Admin/BLL_campos_personalizados.php';
$camposBLL = new AdminCamposPersonalizadosManager();

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $tipo = $_POST['tipo'] ?? 'texto';
    $obrigatorio = isset($_POST['obrigatorio']) ? 1 : 0;
    $ordem = intval($_POST['ordem'] ?? 0);

    if ($nome && $tipo) {
        if ($camposBLL->addCampo($nome, $tipo, $obrigatorio, $ordem)) {
            $success = "Campo criado com sucesso!";
        } else {
            $error = "Erro ao criar campo.";
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
    <title>Novo Campo Personalizado</title>
    <link rel="stylesheet" href="../../assets/style.css">
    <link rel="stylesheet" href="../../assets/teste.css">
    <style>
        .novo-campo-container {
            max-width: 480px;
            margin: 48px auto 0 auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.10);
            padding: 36px 32px 32px 32px;
        }
        .novo-campo-titulo {
            font-size: 1.6rem;
            color: #3a366b;
            margin-bottom: 22px;
            text-align: center;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .novo-campo-form label {
            color: #4a468a;
            font-weight: 500;
            margin-bottom: 4px;
            font-size: 1rem;
        }
        .novo-campo-form input[type="text"],
        .novo-campo-form input[type="number"],
        .novo-campo-form select {
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
        .novo-campo-form input[type="text"]:focus,
        .novo-campo-form input[type="number"]:focus,
        .novo-campo-form select:focus {
            border-color: #764ba2;
            outline: none;
        }
        .novo-campo-form input[type="checkbox"] {
            margin-right: 7px;
        }
        .novo-campo-form .btn {
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
        .novo-campo-form .btn:hover {
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
            .novo-campo-container { padding: 12px 4px; }
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
    <div class="novo-campo-container">
        <div class="novo-campo-titulo">Novo Campo Personalizado</div>
        <?php if ($success): ?><div class="success-message"><?php echo $success; ?></div><?php endif; ?>
        <?php if ($error): ?><div class="error-message"><?php echo $error; ?></div><?php endif; ?>
        <form method="POST" class="novo-campo-form">
            <label>Nome do Campo:</label>
            <input type="text" name="nome" required>

            <label>Tipo:</label>
            <select name="tipo" required>
                <option value="texto">Texto</option>
                <option value="numero">Número</option>
                <option value="data">Data</option>
            </select>

            <label>
                <input type="checkbox" name="obrigatorio" value="1">
                Obrigatório
            </label>

            <label>Ordem:</label>
            <input type="number" name="ordem" value="0" min="0">

            <div style="text-align:center; margin-top: 24px;">
                <button type="submit" class="btn">Criar Campo</button>
                <a href="campos_personalizados.php" class="btn" style="background:#ecebfa;color:#4a468a;">Voltar</a>
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
