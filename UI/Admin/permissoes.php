<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['profile'] !== 'admin') {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/Admin/BLL_permissoes.php';
require_once '../../BLL/Admin/BLL_alertas.php';
require_once '../../DAL/Admin/DAL_utilizadores.php';
$alertasBLL = new AdminAlertasManager();
$dalUtil = new DAL_UtilizadoresAdmin();
$user = $dalUtil->getUtilizadorById($_SESSION['user_id']);
$perfil_id = $user['perfil_id'];
$user_id = $_SESSION['user_id'];
$alertas = $alertasBLL->getAlertasParaUtilizador($perfil_id);
$tem_nao_lidas = false;
foreach ($alertas as $a) {
    if (!$alertasBLL->isAlertaLido($a['id'], $user_id)) {
        $tem_nao_lidas = true;
        break;
    }
}
$icone_sino = '<span style="position:relative;display:inline-block;">
    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#4a468a" viewBox="0 0 24 24" style="vertical-align:middle;">
        <path d="M12 2a6 6 0 0 0-6 6v3.586l-.707.707A1 1 0 0 0 5 14h14a1 1 0 0 0 .707-1.707L19 11.586V8a6 6 0 0 0-6-6zm0 20a2.978 2.978 0 0 0 2.816-2H9.184A2.978 2.978 0 0 0 12 22z"/>
    </svg>';
if ($tem_nao_lidas) {
    $icone_sino .= '<span style="position:absolute;top:2px;right:2px;width:10px;height:10px;background:#e53e3e;border-radius:50%;border:2px solid #fff;"></span>';
}
$icone_sino .= '</span>';
$menu = [
    'Dashboard' => 'dashboard_admin.php',
    'Utilizadores' => 'utilizadores.php',
    'Permissões' => 'permissoes.php',
    'Campos Personalizados' => 'campos_personalizados.php',
    'Alertas' => 'alertas.php',
    'Colaboradores' => '../RH/colaboradores_gerir.php',
    'Equipas' => '../RH/equipas.php',
    'Relatórios' => '../RH/relatorios.php',
    'Perfil' => '../Comuns/perfil.php',
    $icone_sino => '../Comuns/notificacoes.php',
    'Sair' => '../Comuns/logout.php'
];
require_once '../../BLL/Admin/BLL_permissoes.php';
$permBLL = new AdminPermissoesManager();
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
    <link rel="stylesheet" href="../../assets/style.css">
    <link rel="stylesheet" href="../../assets/teste.css">
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