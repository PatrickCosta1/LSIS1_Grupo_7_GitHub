<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Gestão de Equipas - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/teste.css">
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
            <a href="notificacoes.php">Notificações</a>
            <a href="perfil.php">Perfil</a>
            <a href="logout.php">Sair</a>
        </nav>
    </header>
    <main>
        <h1>Gestão de Equipas</h1>
        <table class="tabela-colaboradores">
            <thead>
                <tr>
                    <th>Equipa</th>
                    <th>Coordenador</th>
                    <th>Nº Colaboradores</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Equipa A</td>
                    <td>Joana Lima</td>
                    <td>8</td>
                    <td>
                        <a href="equipa.php?id=1" class="btn">Ver</a>
                        <a href="#" class="btn btn-danger">Remover</a>
                    </td>
                </tr>
                <tr>
                    <td>Equipa B</td>
                    <td>João Costa</td>
                    <td>5</td>
                    <td>
                        <a href="equipa.php?id=2" class="btn">Ver</a>
                        <a href="#" class="btn btn-danger">Remover</a>
                    </td>
                </tr>
            </tbody>
        </table>
        <a href="equipa_nova.php" class="btn">Adicionar Nova Equipa</a>
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