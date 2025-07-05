<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['profile'] !== 'admin') {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/Admin/BLL_alertas.php';
$alertasBLL = new AdminAlertasManager();
$alertas = $alertasBLL->getAllAlertas();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Alertas - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/CSS/Admin/base.css">
    <link rel="stylesheet" href="../../assets/CSS/Admin/alertas.css">
    <link rel="stylesheet" href="../../assets/CSS/Comuns/header.css">
</head>
    
<body>
    <header>
        <a href="pagina_inicial_admin.php">
            <img src="../../assets/tlantic-logo2.png" alt="Logo Tlantic" class="logo-header">
        </a>
        <nav>
            <a href="utilizadores.php">Utilizadores</a>
            <a href="permissoes.php">Permissões</a>
            <a href="campos_personalizados.php">Campos Personalizados</a>
            <a href="alertas.php">Alertas</a>
            <a href="../Comuns/perfil.php" class="perfil-link">Perfil</a>
            <a href="../Comuns/logout.php" class="sair-link">Sair</a>
        </nav>
    </header>
    <main>
        <h1>Gestão de Alertas</h1>
        <table class="tabela-colaboradores">
            <thead>
                <tr>
                    <th>Tipo de Alerta</th>
                    <th>Periodicidade</th>
                    <th>Ativo</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($alertas as $a): ?>
                <tr>
                    <td><?php echo htmlspecialchars($a['tipo']); ?></td>
                    <td><?php echo htmlspecialchars($a['periodicidade_meses'] ? $a['periodicidade_meses'].' meses' : ''); ?></td>
                    <td><?php echo $a['ativo'] ? 'Sim' : 'Não'; ?></td>
                    <td><a href="#" class="btn">Editar</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table><br>
        <div class="adicionar-alerta-center">
            <a href="alerta_novo.php" class="btn">Adicionar Novo Alerta</a>
        </div>
    </main>
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