<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['profile'], ['rh', 'admin'])) {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/RH/BLL_colaboradores_gerir.php';
$colabBLL = new RHColaboradoresManager();
$colaboradores = $colabBLL->getAllColaboradores($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Gestão de Colaboradores - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/style.css">
    <link rel="stylesheet" href="../../assets/teste.css">
    <style>
        .colaboradores-container {
            max-width: 1100px;
            margin: 36px auto 0 auto;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.10);
            padding: 36px 36px 40px 36px;
        }
        .colaboradores-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
        }
        .colaboradores-header h1 {
            font-size: 2rem;
            color: #3a366b;
            margin: 0;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .colaboradores-header .btn {
            padding: 10px 22px;
            font-size: 1rem;
            border-radius: 8px;
        }
        .tabela-colaboradores {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: #f9f9fb;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .tabela-colaboradores th, .tabela-colaboradores td {
            padding: 14px 16px;
            text-align: left;
        }
        .tabela-colaboradores th {
            background: #ecebfa;
            color: #4a468a;
            font-weight: 600;
            border-bottom: 2px solid #d5d3f1;
        }
        .tabela-colaboradores tr:nth-child(even) {
            background: #f4f4fa;
        }
        .tabela-colaboradores tr:hover {
            background: #e6e6f7;
        }
        .tabela-colaboradores td {
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
        .add-colab-btn {
            margin-top: 24px;
            padding: 10px 28px;
            font-size: 1.05rem;
        }
        @media (max-width: 900px) {
            .colaboradores-container { padding: 12px 4px; }
            .tabela-colaboradores th, .tabela-colaboradores td { padding: 8px 6px; font-size: 0.95rem; }
            .colaboradores-header h1 { font-size: 1.3rem; }
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
            <?php else: ?>
                <a href="dashboard_rh.php">Dashboard</a>
                <a href="colaboradores_gerir.php">Colaboradores</a>
                <a href="equipas.php">Equipas</a>
                <a href="relatorios.php">Relatórios</a>
                <a href="exportar.php">Exportar</a>
                <a href="../Comuns/notificacoes.php">Notificações</a>
                <a href="../Comuns/perfil.php">Perfil</a>
                <a href="../Comuns/logout.php">Sair</a>
            <?php endif; ?>
        </nav>
    </header>
    <div class="colaboradores-container">
        <div class="colaboradores-header">
            <h1>Gestão de Colaboradores</h1>
            <a href="colaborador_novo.php" class="btn add-colab-btn">+ Novo Colaborador</a>
        </div>
        <div class="tabela-colaboradores-wrapper">
            <table class="tabela-colaboradores tabela-colaboradores-compacta">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Tipo</th>
                        <th>Função</th>
                        <th>Equipa</th>
                        <th>Estado</th>
                        <th style="min-width:90px;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($colaboradores as $col): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($col['nome']); ?></td>
                        <td><?php echo htmlspecialchars($col['username'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($col['email']); ?></td>
                        <td>
                            <?php
                            if (isset($col['perfil'])) {
                                $tipo = strtolower($col['perfil']);
                                if ($tipo === 'coordenador') {
                                    echo 'Coordenador';
                                } elseif ($tipo === 'colaborador') {
                                    echo 'Colaborador';
                                } elseif ($tipo === 'rh') {
                                    echo 'RH';
                                } elseif ($tipo === 'admin') {
                                    echo 'Administrador';
                                } else {
                                    echo ucfirst($tipo);
                                }
                            } else {
                                echo 'Colaborador';
                            }
                            ?>
                        </td>
                        <td><?php echo htmlspecialchars($col['funcao']); ?></td>
                        <td><?php echo htmlspecialchars($col['equipa']); ?></td>
                        <td>
                            <?php echo $col['ativo'] ? '<span style="color:#38a169;font-weight:600;">Ativo</span>' : '<span style="color:#e53e3e;font-weight:600;">Inativo</span>'; ?>
                        </td>
                        <td>
                            <a href="../Colaborador/ficha_colaborador.php?id=<?php echo $col['id']; ?>" class="btn btn-sm">Ver</a>
                            <a href="#" class="btn btn-danger btn-sm">Remover</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
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