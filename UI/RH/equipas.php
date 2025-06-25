<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['profile'], ['rh', 'admin'])) {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/RH/BLL_equipas.php';
$equipasBLL = new RHEquipasManager();
$equipas = $equipasBLL->getAllEquipas();

// Menu dinâmico com sino
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
if ($_SESSION['profile'] === 'admin') {
    $menu = [
        'Dashboard' => '../Admin/dashboard_admin.php',
        'Utilizadores' => '../Admin/utilizadores.php',
        'Permissões' => '../Admin/permissoes.php',
        'Campos Personalizados' => '../Admin/campos_personalizados.php',
        'Alertas' => '../Admin/alertas.php',
        'Colaboradores' => 'colaboradores_gerir.php',
        'Equipas' => 'equipas.php',
        'Relatórios' => 'relatorios.php',
        'Perfil' => '../Comuns/perfil.php',
        $icone_sino => '../Comuns/notificacoes.php',
        'Sair' => '../Comuns/logout.php'
    ];
} else {
    $menu = [
        'Dashboard' => 'dashboard_rh.php',
        'Colaboradores' => 'colaboradores_gerir.php',
        'Equipas' => 'equipas.php',
        'Relatórios' => 'relatorios.php',
        'Exportar' => 'exportar.php',
        $icone_sino => '../Comuns/notificacoes.php',
        'Perfil' => '../Comuns/perfil.php',
        'Sair' => '../Comuns/logout.php'
    ];
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Gestão de Equipas - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/teste.css">
    <?php if (!in_array($_SESSION['profile'], ['admin', 'rh'])): ?>
        <link rel="stylesheet" href="../../assets/menu_notificacoes.css">
    <?php endif; ?>
    <style>
        .equipas-container {
            max-width: 950px;
            margin: 36px auto 0 auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            padding: 32px 32px 36px 32px;
        }
        .equipas-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }
        .equipas-header h1 {
            font-size: 2rem;
            color: #3a366b;
            margin: 0;
        }
        .equipas-header .btn {
            padding: 10px 22px;
            font-size: 1rem;
            border-radius: 8px;
        }
        .tabela-equipas {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: #f9f9fb;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .tabela-equipas th, .tabela-equipas td {
            padding: 13px 14px;
            text-align: left;
        }
        .tabela-equipas th {
            background: #ecebfa;
            color: #4a468a;
            font-weight: 600;
            border-bottom: 2px solid #d5d3f1;
        }
        .tabela-equipas tr:nth-child(even) {
            background: #f4f4fa;
        }
        .tabela-equipas tr:hover {
            background: #e6e6f7;
        }
        .tabela-equipas td {
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
        @media (max-width: 900px) {
            .equipas-container { padding: 12px 4px; }
            .tabela-equipas th, .tabela-equipas td { padding: 8px 6px; font-size: 0.95rem; }
            .equipas-header h1 { font-size: 1.3rem; }
        }
    </style>
</head>
<body>
    <header>
        <img src="../../assets/tlantic-logo.png" alt="Logo Tlantic" class="logo-header">
        <nav>
            <?php if ($_SESSION['profile'] === 'admin'): ?>
                <a href="../Admin/dashboard_admin.php">Dashboard</a>
                <a href="../Admin/utilizadores.php">Utilizadores</a>
                <a href="../Admin/permissoes.php">Permissões</a>
                <a href="../Admin/campos_personalizados.php">Campos Personalizados</a>
                <a href="../Admin/alertas.php">Alertas</a>
                <a href="colaboradores_gerir.php">Colaboradores</a>
                <a href="equipas.php">Equipas</a>
                <a href="relatorios.php">Relatórios</a>
                <a href="../Comuns/perfil.php">Perfil</a>
                <a href="../Comuns/logout.php">Sair</a>
            <?php elseif ($_SESSION['profile'] === 'rh'): ?>
                <a href="dashboard_rh.php">Dashboard</a>
                <a href="colaboradores_gerir.php">Colaboradores</a>
                <a href="equipas.php">Equipas</a>
                <a href="relatorios.php">Relatórios</a>
                <a href="exportar.php">Exportar</a>
                <a href="../Comuns/perfil.php">Perfil</a>
                <a href="../Comuns/logout.php">Sair</a>
            <?php else: ?>
                <?php foreach ($menu as $label => $url): ?>
                    <a href="<?php echo $url; ?>"><?php echo $label; ?></a>
                <?php endforeach; ?>
            <?php endif; ?>
        </nav>
    </header>
    <div class="equipas-container">
        <div class="equipas-header">
            <h1>Gestão de Equipas</h1>
            <a href="equipa_nova.php" class="btn">+ Nova Equipa</a>
        </div>
        <table class="tabela-equipas">
            <thead>
                <tr>
                    <th>Equipa</th>
                    <th>Coordenador</th>
                    <th>Nº Colaboradores</th>
                    <th style="min-width:120px;">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($equipas as $eq): ?>
                <tr>
                    <td><?php echo htmlspecialchars($eq['nome']); ?></td>
                    <td><?php echo htmlspecialchars($eq['coordenador']); ?></td>
                    <td><?php echo htmlspecialchars($eq['num_colaboradores']); ?></td>
                    <td>
                        <a href="equipa_editar.php?id=<?php echo $eq['id']; ?>" class="btn">Editar</a>
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