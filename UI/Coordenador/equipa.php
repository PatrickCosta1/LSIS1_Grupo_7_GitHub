<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['profile'] !== 'coordenador') {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/Coordenador/BLL_dashboard_coordenador.php';
$coordBLL = new CoordenadorDashboardManager();

$equipaId = $_GET['id'] ?? null;
if (!$equipaId) {
    header('Location: dashboard_coordenador.php');
    exit();
}

// Buscar todas as equipas do coordenador
$equipas = $coordBLL->getEquipasByCoordenador($_SESSION['user_id']);
$nomeEquipa = '';
foreach ($equipas as $e) {
    if ($e['id'] == $equipaId) {
        $nomeEquipa = $e['nome'];
        break;
    }
}

// Corrigir: buscar membros da equipa pelo ID da equipa, não pelo coordenador
$colaboradores = $coordBLL->getColaboradoresByEquipa($equipaId);

// Definir menu do coordenador
$menu = [
    'Dashboard' => 'dashboard_coordenador.php',
    'Minha Equipa' => 'equipa.php?id=' . $equipaId,
    'Relatórios Equipa' => 'relatorios_equipa.php?id=' . $equipaId,
    'Perfil' => '../Comuns/perfil.php',
    'Sair' => '../Comuns/logout.php'
];
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Minha Equipa - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/style.css">
    <link rel="stylesheet" href="../../assets/teste.css">
    <link rel="stylesheet" href="../../assets/menu_notificacoes.css">
    <style>
        body {
            background: #f6f7fb;
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        main {
            max-width: 1000px;
            margin: 0 auto;
            padding: 36px;
        }
        h1 {
            color: #3a366b;
            font-size: 1.6rem;
            margin-bottom: 12px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-align: left;
        }
        h2 {
            color: #764ba2;
            font-size: 1.15rem;
            margin-bottom: 24px;
            font-weight: 600;
            letter-spacing: 0.2px;
        }
        .tabela-colaboradores {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(102,126,234,0.08);
            margin-bottom: 18px;
            overflow: hidden;
        }
        .tabela-colaboradores th, .tabela-colaboradores td {
            padding: 13px 14px;
            text-align: left;
        }
        .tabela-colaboradores th {
            background: #ecebfa;
            color: #4a468a;
            font-weight: 600;
            border-bottom: 2px solid #d5d3f1;
            font-size: 1.01rem;
        }
        .tabela-colaboradores tr:nth-child(even) {
            background: #f4f4fa;
        }
        .tabela-colaboradores tr:hover {
            background: #e6e6f7;
        }
        .tabela-colaboradores td {
            color: #3a366b;
            font-size: 0.99rem;
        }
        .btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border: none;
            border-radius: 7px;
            padding: 8px 22px;
            font-size: 1rem;
            cursor: pointer;
            margin-top: 8px;
            transition: background 0.2s, box-shadow 0.2s;
            text-decoration: none;
            font-weight: 500;
            box-shadow: 0 2px 8px rgba(102,126,234,0.08);
        }
        .btn:hover {
            background: linear-gradient(135deg, #5a67d8 0%, #6b47b6 100%);
            box-shadow: 0 4px 16px rgba(102,126,234,0.13);
        }
        .no-membros {
            color: #764ba2;
            font-size: 1.05rem;
            text-align: center;
            padding: 24px 0;
        }
        @media (max-width: 900px) {
            main { padding: 12px 0 0 0; }
            .tabela-colaboradores th, .tabela-colaboradores td { padding: 8px 6px; font-size: 0.95rem; }
            h1 { font-size: 1.15rem; }
            h2 { font-size: 1rem; }
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
    <main>
        <h1>Colaboradores da Equipa</h1>
        <?php if ($nomeEquipa): ?>
            <h2><?php echo htmlspecialchars($nomeEquipa); ?></h2>
        <?php endif; ?>
        <table class="tabela-colaboradores">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Função</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($colaboradores)): ?>
                    <?php foreach ($colaboradores as $c): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($c['nome']); ?></td>
                        <td><?php echo htmlspecialchars($c['cargo']); ?></td>
                        <td><?php echo htmlspecialchars($c['email']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="no-membros"><em>Sem membros nesta equipa.</em></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div style="margin-top:24px; text-align:right;">
            <a href="relatorios_equipa.php?id=<?php echo urlencode($equipaId); ?>" class="btn">Ver Relatórios da Equipa</a>
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