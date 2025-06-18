<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Minha Ficha - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/teste.css">
</head>
<body>
    <header>
        <img src="../../assets/tlantic-logo.png" alt="Logo Tlantic" class="logo-header">
        <nav>
            <a href="dashboard_colaborador.php">Dashboard</a>
            <a href="ficha_colaborador.php">Minha Ficha</a>
            <a href="notificacoes.php">Notificações</a>
            <a href="perfil.php">Perfil</a>
            <a href="logout.php">Sair</a>
        </nav>
    </header>
    <main>
        <h1>Minha Ficha de Colaborador</h1>
        <form class="ficha-form">
            <label>Nome: <input type="text" value="Maria Silva"></label><br><br>
            <label>Morada: <input type="text" value="Rua Exemplo, 123"></label><br><br>
            <label>Estado Civil: <input type="text" value="Solteiro(a)"></label><br><br>
            <label>Habilitações: <input type="text" value="Licenciatura"></label><br><br>
            <label>Contacto Emergência: <input type="text" value="912345678"></label><br><br>
            <label>Matrícula Viatura: <input type="text" value="AA-00-BB"></label><br><br>
            <label>Data de Nascimento: <input type="date" value="1990-01-01"></label><br><br>
            <label>Função: <input type="text" value="Analista"></label><br><br>
            <label>Geografia: <input type="text" value="Porto"></label><br><br>
            <label>Nível Hierárquico: <input type="text" value="Júnior"></label><br><br>
            <label>Remuneração: <input type="number" value="1200"></label><br><br>
            <button type="submit" class="btn">Guardar Alterações</button>
        </form>
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