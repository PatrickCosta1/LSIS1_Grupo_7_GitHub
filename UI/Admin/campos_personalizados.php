<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['profile'] !== 'admin') {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/Admin/BLL_campos_personalizados.php';
$camposBLL = new AdminCamposPersonalizadosManager();
$campos = $camposBLL->getAllCampos();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Campos Personalizados - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/teste.css">
    <style>
        .campos-container {
            max-width: 700px;
            margin: 36px auto 0 auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            padding: 32px 32px 36px 32px;
        }
        .campos-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }
        .campos-header h1 {
            font-size: 2rem;
            color: #3a366b;
            margin: 0;
        }
        .campos-header .btn {
            padding: 10px 22px;
            font-size: 1rem;
            border-radius: 8px;
        }
        .tabela-campos {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: #f9f9fb;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .tabela-campos th, .tabela-campos td {
            padding: 13px 14px;
            text-align: left;
        }
        .tabela-campos th {
            background: #ecebfa;
            color: #4a468a;
            font-weight: 600;
            border-bottom: 2px solid #d5d3f1;
        }
        .tabela-campos tr:nth-child(even) {
            background: #f4f4fa;
        }
        .tabela-campos tr:hover {
            background: #e6e6f7;
        }
        .tabela-campos td {
            color: #3a366b;
            font-size: 1rem;
        }
        .btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border: none;
            border-radius: 7px;
            padding: 7px 18px;
            font-size: 0.98rem;
            cursor: pointer;
            margin-right: 6px;
            transition: background 0.2s;
            text-decoration: none;
            display: inline-block;
        }
        .btn:hover {
            background: linear-gradient(135deg, #5a67d8 0%, #6b47b6 100%);
        }
        .btn-danger {
            background: #e53e3e;
        }
        .btn-danger:hover {
            background: #c53030;
        }
        @media (max-width: 700px) {
            .campos-container { padding: 12px 4px; }
            .tabela-campos th, .tabela-campos td { padding: 8px 6px; font-size: 0.95rem; }
            .campos-header h1 { font-size: 1.3rem; }
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
    <div class="campos-container">
        <div class="campos-header">
            <h1>Campos Personalizados da Ficha</h1>
            <a href="campo_novo.php" class="btn">+ Novo Campo</a>
        </div>
        <table class="tabela-campos">
            <thead>
                <tr>
                    <th>Campo</th>
                    <th>Tipo</th>
                    <th style="min-width:120px;">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($campos as $c): ?>
                <tr>
                    <td><?php echo htmlspecialchars($c['nome']); ?></td>
                    <td><?php echo htmlspecialchars($c['tipo']); ?></td>
                    <td>
                        <a href="#" class="btn">Editar</a>
                        <a href="#" class="btn btn-danger">Remover</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
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