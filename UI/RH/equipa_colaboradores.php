<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['profile'], ['rh', 'admin'])) {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/RH/BLL_equipas.php';
require_once '../../BLL/RH/BLL_colaboradores_gerir.php';

$equipasBLL = new RHEquipasManager();
$colabBLL = new RHColaboradoresManager();

$equipa_id = $_GET['id'] ?? null;
if (!$equipa_id) {
    header('Location: equipas.php');
    exit();
}
$equipa = $equipasBLL->getEquipaById($equipa_id);

// Buscar todos os colaboradores da empresa (com utilizador_id)
$colaboradores = $colabBLL->getAllColaboradores();

// Buscar IDs dos colaboradores já na equipa
$colabs_equipa = $equipasBLL->getColaboradoresByEquipa($equipa_id);
$ids_equipa = array_column($colabs_equipa, 'id');

// Buscar todos os coordenadores de todas as equipas
$equipas_todas = $equipasBLL->getAllEquipas();
$coordenadores_usados = [];
foreach ($equipas_todas as $eq) {
    if (!empty($eq['coordenador_id'])) {
        $coordenadores_usados[] = $eq['coordenador_id'];
    }
}

// Filtrar apenas colaboradores (não rh, admin, convidado, nem coordenadores de outra equipa)
$colaboradores_filtrados = [];
foreach ($colaboradores as $c) {
    $perfil = strtolower($c['perfil'] ?? '');
    $utilizador_id = $c['utilizador_id'] ?? null;
    if ($perfil === 'colaborador' && $utilizador_id && !in_array($utilizador_id, $coordenadores_usados)) {
        $colaboradores_filtrados[] = $c;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novos = $_POST['colaboradores'] ?? [];
    $equipasBLL->atualizarColaboradoresEquipa($equipa_id, $novos);
    header("Location: equipa_colaboradores.php?id=" . $equipa_id);
    exit();
}

// Buscar nome do coordenador (opcional)
$coordenador_nome = '';
if (!empty($equipa['coordenador_id'])) {
    foreach ($colaboradores as $c) {
        if (isset($c['utilizador_id']) && $c['utilizador_id'] == $equipa['coordenador_id']) {
            $coordenador_nome = $c['nome'];
            break;
        }
    }
}
if (!isset($coordenador_nome)) $coordenador_nome = '';
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Gerir Colaboradores da Equipa</title>
    <link rel="stylesheet" href="../../assets/style.css">
    <link rel="stylesheet" href="../../assets/teste.css">
    <style>
        .equipa-menu {
            display: flex;
            gap: 18px;
            margin-top: 32px;
            justify-content: center;
        }
        .equipa-menu .btn {
            padding: 8px 22px;
            font-size: 1rem;
            border-radius: 7px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.2s;
        }
        .equipa-menu .btn:hover {
            background: linear-gradient(135deg, #5a67d8 0%, #6b47b6 100%);
        }
        .equipa-colab-container {
            max-width: 700px;
            margin: 40px auto 0 auto;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.12);
            padding: 38px 36px 36px 36px;
        }
        .equipa-colab-titulo {
            font-size: 1.4rem;
            color: #3a366b;
            margin-bottom: 26px;
            text-align: center;
            font-weight: 700;
            letter-spacing: 0.2px;
        }
        .equipa-colab-lista {
            max-height: 420px;
            overflow-y: auto;
            margin-bottom: 18px;
            border: 1px solid #ecebfa;
            border-radius: 10px;
            background: #f9f9fb;
            padding: 12px;
        }
        .colab-lista-tabela {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            background: #f9f9fb;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .colab-lista-tabela th, .colab-lista-tabela td {
            padding: 10px 12px;
            text-align: left;
        }
        .colab-lista-tabela th {
            background: #ecebfa;
            color: #4a468a;
            font-weight: 600;
            border-bottom: 1px solid #d5d3f1;
        }
        .colab-lista-tabela tr:nth-child(even) {
            background: #f4f4fa;
        }
        .colab-lista-tabela tr:hover {
            background: #e6e6f7;
        }
        .colab-lista-tabela td {
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
            margin-top: 10px;
            transition: background 0.2s;
            text-decoration: none;
            display: inline-block;
        }
        .btn:hover {
            background: linear-gradient(135deg, #5a67d8 0%, #6b47b6 100%);
        }
        .info-coordenador {
            background: #ecebfa;
            color: #764ba2;
            border-radius: 7px;
            padding: 10px 16px;
            margin-bottom: 18px;
            font-size: 1.05rem;
            font-weight: 500;
            text-align: center;
        }
    </style>
</head>
<body>
    <header>
        <img src="../../assets/tlantic-logo.png" alt="Logo Tlantic" class="logo-header">
        <nav>
            <a href="dashboard_rh.php">Dashboard</a>
            <a href="colaboradores_gerir.php">Colaboradores</a>
            <a href="equipas.php">Equipas</a>
            <a href="relatorios.php">Relatórios</a>
            <a href="exportar.php">Exportar</a>
            <a href="../Comuns/notificacoes.php">Notificações</a>
            <a href="../Comuns/perfil.php">Perfil</a>
            <a href="../Comuns/logout.php">Sair</a>
        </nav>
    </header>
    <div class="equipa-colab-container">
        <div class="equipa-colab-titulo">Gerir Colaboradores da Equipa "<?php echo htmlspecialchars($equipa['nome']); ?>"</div>
        <div class="info-coordenador">
            Coordenador atual: <strong><?php echo $coordenador_nome ? htmlspecialchars($coordenador_nome) : '---'; ?></strong>
        </div>
        <form method="POST">
            <div class="equipa-colab-lista">
                <table class="colab-lista-tabela">
                    <thead>
                        <tr>
                            <th>Selecionar</th>
                            <th>Nome</th>
                            <th>Função</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($colaboradores_filtrados as $c): ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="colaboradores[]" value="<?php echo $c['id']; ?>" <?php if (in_array($c['id'], $ids_equipa)) echo 'checked'; ?>>
                            </td>
                            <td><?php echo htmlspecialchars($c['nome']); ?></td>
                            <td><?php echo htmlspecialchars($c['funcao']); ?></td>
                            <td><?php echo htmlspecialchars($c['email'] ?? ''); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div style="text-align:center;">
                <button type="submit" class="btn">Guardar</button>
            </div>
        </form>
        <div class="equipa-menu">
            <a href="equipa_coordenador.php?id=<?php echo $equipa_id; ?>" class="btn">Alterar Coordenador</a>
            <a href="equipas.php" class="btn" style="background:#ecebfa;color:#4a468a;">Voltar</a>
        </div>
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