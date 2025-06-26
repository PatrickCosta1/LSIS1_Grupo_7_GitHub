<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['profile'] !== 'admin') {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/Admin/BLL_permissoes.php';
$permBLL = new AdminPermissoesManager();

// Atualizar permissões
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['permissao'])) {
    foreach ($_POST['permissao'] as $id => $valor) {
        $permBLL->updatePermissao($id, $valor ? 1 : 0);
    }
    header('Location: permissoes.php');
    exit();
}

// Obter permissões organizadas por perfil e lista de permissões distintas
$permissoes = $permBLL->getAllPermissoes();
$perfis = [];
$colunas = [];
foreach ($permissoes as $p) {
    $perfis[$p['perfil_id']]['nome'] = $p['perfil'];
    $perfis[$p['perfil_id']]['permissoes'][$p['permissao']] = [
        'id' => $p['id'],
        'valor' => $p['valor']
    ];
    $colunas[$p['permissao']] = true;
}
$colunas = array_keys($colunas);
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Permissões - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/CSS/Admin/base.css">
    <style>
        .permissoes-container {
            max-width: 1100px;
            margin: 36px auto 0 auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            padding: 32px 32px 36px 32px;
        }
        .permissoes-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }
        .permissoes-header h1 {
            font-size: 2rem;
            color: #3a366b;
            margin: 0;
        }
        .tabela-scroll {
            overflow-x: auto;
        }
        .tabela-permissoes {
            width: 100%;
            min-width: 700px;
            border-collapse: separate;
            border-spacing: 0;
            background: #f9f9fb;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .tabela-permissoes th, .tabela-permissoes td {
            padding: 13px 14px;
            text-align: center;
        }
        .tabela-permissoes th {
            background: #ecebfa;
            color: #4a468a;
            font-weight: 600;
            border-bottom: 2px solid #d5d3f1;
        }
        .tabela-permissoes tr:nth-child(even) {
            background: #f4f4fa;
        }
        .tabela-permissoes tr:hover {
            background: #e6e6f7;
        }
        .tabela-permissoes td {
            color: #3a366b;
            font-size: 1rem;
        }
        .perfil-col {
            font-weight: 600;
            color: #764ba2;
            text-align: left;
        }
        .btn-salvar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border: none;
            border-radius: 7px;
            padding: 10px 28px;
            font-size: 1.08rem;
            cursor: pointer;
            margin-top: 18px;
            transition: background 0.2s;
            text-decoration: none;
            display: inline-block;
        }
        .btn-salvar:hover {
            background: linear-gradient(135deg, #5a67d8 0%, #6b47b6 100%);
        }
        @media (max-width: 900px) {
            .permissoes-container { padding: 12px 4px; }
            .permissoes-header h1 { font-size: 1.3rem; }
            .tabela-permissoes th, .tabela-permissoes td { padding: 8px 6px; font-size: 0.95rem; }
        }
    </style>
</head>
<body>
    <header>
        <img src="../../assets/tlantic-logo2.png" alt="Logo Tlantic" class="logo-header">
        <nav>
            <a href="dashboard_admin.php">Dashboard</a>
            <a href="utilizadores.php">Utilizadores</a>
            <a href="permissoes.php">Permissões</a>
            <a href="campos_personalizados.php">Campos Personalizados</a>
            <a href="alertas.php">Alertas</a>
            <a href="../RH/colaboradores_gerir.php">Colaboradores</a>
            <a href="../RH/equipas.php">Equipas</a>
            <a href="../RH/relatorios.php">Relatórios</a>
            <a href="../Comuns/perfil.php" class="perfil-link">Perfil</a>
            <a href="../Comuns/logout.php" class="sair-link">Sair</a>
        </nav>
    </header>
    <div class="permissoes-container">
        <div class="permissoes-header">
            <h1>Permissões dos Perfis</h1>
        </div>
        <form method="POST">
        <div class="tabela-scroll">
        <table class="tabela-permissoes">
            <thead>
                <tr>
                    <th>Perfil</th>
                    <?php foreach ($colunas as $col): ?>
                        <th><?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $col))); ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($perfis as $perfil): ?>
                <tr>
                    <td class="perfil-col"><?php echo htmlspecialchars($perfil['nome']); ?></td>
                    <?php foreach ($colunas as $col): ?>
                        <td>
                            <?php if (isset($perfil['permissoes'][$col])): ?>
                                <input type="checkbox" name="permissao[<?php echo $perfil['permissoes'][$col]['id']; ?>]" value="1" <?php if ($perfil['permissoes'][$col]['valor']) echo 'checked'; ?>>
                            <?php else: ?>
                                <span style="color:#bbb;">—</span>
                            <?php endif; ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <div style="text-align:center;">
            <button type="submit" class="btn-salvar">Guardar Permissões</button>
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