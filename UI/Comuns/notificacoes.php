<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: erro.php');
    exit();
}
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

switch ($_SESSION['profile']) {
    case 'admin':
        $menu = [
            'Dashboard' => '../Admin/dashboard_admin.php',
            'Utilizadores' => '../Admin/utilizadores.php',
            'Permissões' => '../Admin/permissoes.php',
            'Campos Personalizados' => '../Admin/campos_personalizados.php',
            'Alertas' => '../Admin/alertas.php',
            'Colaboradores' => '../RH/colaboradores_gerir.php',
            'Equipas' => '../RH/equipas.php',
            'Relatórios' => '../RH/relatorios.php',
            'Perfil' => '../Comuns/perfil.php',
            $icone_sino => 'notificacoes.php',
            'Sair' => '../Comuns/logout.php'
        ];
        break;
    case 'rh':
        $menu = [
            'Dashboard' => '../RH/dashboard_rh.php',
            'Colaboradores' => '../RH/colaboradores_gerir.php',
            'Equipas' => '../RH/equipas.php',
            'Relatórios' => '../RH/relatorios.php',
            'Exportar' => '../RH/exportar.php',
            $icone_sino => 'notificacoes.php',
            'Perfil' => '../Comuns/perfil.php',
            'Sair' => '../Comuns/logout.php'
        ];
        break;
    case 'coordenador':
        $menu = [
            'Dashboard' => '../Coordenador/dashboard_coordenador.php',
            'Minha Ficha' => '../Colaborador/ficha_colaborador.php',
            'Minha Equipa' => '../Coordenador/equipa.php',
            $icone_sino => 'notificacoes.php',
            'Perfil' => '../Comuns/perfil.php',
            'Sair' => '../Comuns/logout.php'
        ];
        break;
    case 'colaborador':
        $menu = [
            'Dashboard' => '../Colaborador/dashboard_colaborador.php',
            'Minha Ficha' => '../Colaborador/ficha_colaborador.php',
            $icone_sino => 'notificacoes.php',
            'Perfil' => '../Comuns/perfil.php',
            'Sair' => '../Comuns/logout.php'
        ];
        break;
    case 'convidado':
        $menu = [
            'Preencher Dados' => '../Convidado/onboarding_convidado.php',
            'Sair' => '../Comuns/logout.php'
        ];
        break;
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Notificações - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/teste.css">
    <link rel="stylesheet" href="../../assets/menu_notificacoes.css">
    <style>
        body {
            background: #f4f4fa;
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        .notificacoes-container {
            max-width: 900px;
            margin: 40px auto 0 auto;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 6px 32px rgba(102,126,234,0.10), 0 1.5px 6px rgba(118,75,162,0.07);
            padding: 38px 40px 40px 40px;
        }
        h1 {
            color: #3a366b;
            font-size: 2.1rem;
            margin-bottom: 28px;
            text-align: center;
            letter-spacing: 1px;
        }
        .tabela-notificacoes {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: #f9f9fb;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(102,126,234,0.06);
        }
        .tabela-notificacoes th, .tabela-notificacoes td {
            padding: 14px 16px;
            text-align: left;
        }
        .tabela-notificacoes th {
            background: #ecebfa;
            color: #4a468a;
            font-weight: 600;
            border-bottom: 2px solid #d5d3f1;
            font-size: 1.05rem;
        }
        .tabela-notificacoes tr:nth-child(even) {
            background: #f4f4fa;
        }
        .tabela-notificacoes tr.unread {
            background: #ffeaea;
            font-weight: 600;
        }
        .tabela-notificacoes tr:hover {
            background: #e6e6f7;
        }
        .tabela-notificacoes td {
            color: #3a366b;
            font-size: 1rem;
        }
        .btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border: none;
            border-radius: 7px;
            padding: 8px 22px;
            font-size: 1rem;
            cursor: pointer;
            margin-right: 8px;
            margin-top: 4px;
            transition: background 0.2s;
            text-decoration: none;
            display: inline-block;
        }
        .btn:hover {
            background: linear-gradient(135deg, #5a67d8 0%, #6b47b6 100%);
        }
        .no-notifications {
            text-align: center;
            color: #764ba2;
            font-size: 1.1rem;
            padding: 32px 0;
        }
        header {
            background: #fff;
            border-bottom: 1.5px solid #ecebfa;
            padding: 0 0 8px 0;
        }
        .logo-header {
            height: 48px;
            margin: 12px 28px 8px 28px;
            display: inline-block;
            vertical-align: middle;
        }
        nav {
            margin-top: 12px;
            text-align: center;
        }
        nav a {
            color: #4a468a;
            text-decoration: none;
            font-weight: 500;
            margin: 0 14px;
            font-size: 1.08rem;
            position: relative;
            transition: color 0.2s;
            padding: 6px 10px;
            border-radius: 6px;
        }
        nav a:hover {
            color: #764ba2;
            background: #ecebfa;
        }
        nav a .notificacao-sino {
            vertical-align: middle;
        }
        @media (max-width: 700px) {
            .logo-header { margin: 8px 8px 8px 8px; height: 36px; }
            nav a { font-size: 0.98rem; margin: 0 6px; padding: 5px 6px; }
        }
    </style>
</head>
<body>
    <header>
        <img src="../../assets/tlantic-logo.png" alt="Logo Tlantic" class="logo-header">
        <nav>
            <?php foreach ($menu as $label => $url): ?>
                <a href="<?php echo $url; ?>"><?php echo $label; ?></a>
            <?php endforeach; ?>
        </nav>
    </header>
    <div class="notificacoes-container">
        <h1>Notificações</h1>
        <table class="tabela-notificacoes">
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Descrição</th>
                    <th>Periodicidade (meses)</th>
                    <th>Lido</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $tem_notificacao = false;
                foreach ($alertas as $a):
                    $lido = $alertasBLL->isAlertaLido($a['id'], $user_id);
                    if (!$lido) $tem_notificacao = true;
                ?>
                <tr class="<?php echo !$lido ? 'unread' : ''; ?>">
                    <td><?php echo htmlspecialchars($a['tipo']); ?></td>
                    <td><?php echo htmlspecialchars($a['descricao']); ?></td>
                    <td><?php echo htmlspecialchars($a['periodicidade_meses']); ?></td>
                    <td><?php echo $lido ? 'Sim' : 'Não'; ?></td>
                    <td>
                        <?php if (!$lido): ?>
                            <a href="?lido=<?php echo $a['id']; ?>" class="btn">Marcar como lido</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($alertas)): ?>
                    <tr><td colspan="5" class="no-notifications"><em>Sem notificações.</em></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>