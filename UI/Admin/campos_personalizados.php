<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['profile'] !== 'admin') {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/Admin/BLL_campos_personalizados.php';
$camposBLL = new AdminCamposPersonalizadosManager();
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
    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill=\"#4a468a\" viewBox=\"0 0 24 24\" style=\"vertical-align:middle;\">
        <path d=\"M12 2a6 6 0 0 0-6 6v3.586l-.707.707A1 1 0 0 0 5 14h14a1 1 0 0 0 .707-1.707L19 11.586V8a6 6 0 0 0-6-6zm0 20a2.978 2.978 0 0 0 2.816-2H9.184A2.978 2.978 0 0 0 12 22z\"/>
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

$success = '';
$error = '';

// Adicionar campo
if (isset($_POST['add_nome'], $_POST['add_tipo'])) {
    if ($camposBLL->addCampo(trim($_POST['add_nome']), $_POST['add_tipo'])) {
        $success = "Campo adicionado!";
    } else {
        $error = "Erro ao adicionar campo.";
    }
}

// Editar campo
if (isset($_POST['edit_id'], $_POST['edit_nome'], $_POST['edit_tipo'])) {
    if ($camposBLL->updateCampo($_POST['edit_id'], trim($_POST['edit_nome']), $_POST['edit_tipo'])) {
        $success = "Campo atualizado!";
    } else {
        $error = "Erro ao atualizar campo.";
    }
}

// Remover campo
if (isset($_GET['remover']) && is_numeric($_GET['remover'])) {
    if ($camposBLL->removeCampo($_GET['remover'])) {
        $success = "Campo removido!";
    } else {
        $error = "Erro ao remover campo.";
    }
}

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
        </div>
        <?php if ($success): ?><div class="success-message"><?php echo $success; ?></div><?php endif; ?>
        <?php if ($error): ?><div class="error-message"><?php echo $error; ?></div><?php endif; ?>

        <!-- Formulário adicionar campo -->
        <form method="POST" style="margin-bottom:18px;">
            <input type="text" name="add_nome" placeholder="Nome do campo" required>
            <select name="add_tipo" required>
                <option value="texto">Texto</option>
                <option value="numero">Número</option>
                <option value="data">Data</option>
            </select>
            <button type="submit" class="btn">Adicionar Campo</button>
        </form>

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
                    <form method="POST" style="display:inline;">
                        <td>
                            <input type="hidden" name="edit_id" value="<?php echo $c['id']; ?>">
                            <input type="text" name="edit_nome" value="<?php echo htmlspecialchars($c['nome']); ?>" required>
                        </td>
                        <td>
                            <select name="edit_tipo" required>
                                <option value="texto" <?php if ($c['tipo'] === 'texto') echo 'selected'; ?>>Texto</option>
                                <option value="numero" <?php if ($c['tipo'] === 'numero') echo 'selected'; ?>>Número</option>
                                <option value="data" <?php if ($c['tipo'] === 'data') echo 'selected'; ?>>Data</option>
                            </select>
                        </td>
                        <td>
                            <button type="submit" class="btn">Guardar</button>
                            <a href="?remover=<?php echo $c['id']; ?>" class="btn btn-danger" onclick="return confirm('Remover este campo?');">Remover</a>
                        </td>
                    </form>
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