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
    <link rel="stylesheet" href="../../assets/CSS/RH/colaboradores_gerir.css">
</head>
<body>
    <header>
        <img src="../../assets/tlantic-logo2.png" alt="Logo Tlantic" class="logo-header">
        <nav>
            <?php if ($_SESSION['profile'] === 'admin'): ?>
                <a href="../Admin/utilizadores.php">Utilizadores</a>
                <a href="../Admin/permissoes.php">Permissões</a>
                <a href="../Admin/campos_personalizados.php">Campos Personalizados</a>
                <a href="../Admin/alertas.php">Alertas</a>
                <a href="colaboradores_gerir.php">Colaboradores</a>
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
    <main>
        <h1>Gestão de Colaboradores</h1>
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
                        <td><?php echo htmlspecialchars($col['cargo']); ?></td>
                        <td><?php echo htmlspecialchars($col['equipa']); ?></td>
                        <td><?php echo $col['ativo'] ? 'Ativo' : 'Inativo'; ?></td>
                        <td>
                            <a href="../Colaborador/ficha_colaborador.php?id=<?php echo $col['id']; ?>" class="btn btn-sm">Ver</a>
                            <a href="#" class="btn btn-danger btn-sm">Remover</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <a href="colaborador_novo.php" class="btn add-colab-btn">Adicionar Novo Colaborador</a>
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