<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['profile'], ['rh', 'admin'])) {
    header('Location: ../Comuns/erro.php');
    exit();
}

require_once '../../BLL/RH/BLL_colaboradores_gerir.php';
$colabBLL = new RHColaboradoresManager();

// Carregar equipas e perfis para o formulário
$equipas = method_exists($colabBLL, 'getAllEquipas') ? $colabBLL->getAllEquipas() : [];
$perfis = method_exists($colabBLL, 'getAllPerfis') ? $colabBLL->getAllPerfis() : [];

// Exportação
if (isset($_GET['export'])) {
    $tipo = $_GET['export'];
    $filtro = isset($_GET['filtro']) ? $_GET['filtro'] : null;

    $colaboradores = [];
    if ($tipo === 'colaboradores') {
        $colaboradores = $colabBLL->getAllColaboradores();
    } elseif ($tipo === 'equipa' && $filtro !== null && $filtro !== '') {
        // O select envia sempre string, pode ser '0', por isso valida se é numérico e maior que zero
        if (is_numeric($filtro) && (int)$filtro > 0) {
            $colaboradores = $colabBLL->getColaboradoresPorEquipa((int)$filtro);
        }
    } elseif ($tipo === 'perfil' && $filtro !== null && $filtro !== '') {
        if (is_numeric($filtro) && (int)$filtro > 0) {
            $colaboradores = $colabBLL->getColaboradoresPorPerfil((int)$filtro);
        }
    }

    // Para debug: descomente para ver o resultado no log do PHP
    // error_log("Tipo: $tipo | Filtro: $filtro | Resultado: " . print_r($colaboradores, true));

    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment;filename="colaboradores.csv"');
    $out = fopen('php://output', 'w');
    // BOM para Excel abrir corretamente em UTF-8
    fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));
    fputcsv($out, ['Nome', 'Cargo', 'Equipas', 'Email', 'Estado']);
    if (!empty($colaboradores)) {
        foreach ($colaboradores as $col) {
            fputcsv($out, [
                $col['nome'] ?? '',
                $col['cargo'] ?? '',
                $col['equipas'] ?? '',
                $col['email'] ?? '',
                (isset($col['ativo']) && $col['ativo'] ? 'Ativo' : 'Inativo')
            ]);
        }
    }
    fclose($out);
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Exportar Dados - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/CSS/RH/exportar.css">
    <link rel="stylesheet" href="../../assets/CSS/Comuns/header.css">
    <script>
    // Mostra/esconde selects conforme opção
    function onTipoChange() {
        var tipo = document.getElementById('tipo-export').value;
        document.getElementById('filtro-equipa').style.display = (tipo === 'equipa') ? 'block' : 'none';
        document.getElementById('filtro-perfil').style.display = (tipo === 'perfil') ? 'block' : 'none';
    }
    </script>
</head>
<body>
    <header>
        <img src="../../assets/tlantic-logo2.png" alt="Logo Tlantic" class="logo-header" style="cursor:pointer;" onclick="window.location.href='pagina_inicial_RH.php';">
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
    <main>
        <h1>Exportar Dados</h1>
        <form method="get" action="exportar.php" style="max-width:400px;margin:0 auto;">
            <label>Escolha o tipo de exportação:
                <select name="export" id="tipo-export" onchange="onTipoChange()" required>
                    <option value="colaboradores">Todos os colaboradores</option>
                    <option value="equipa">Por equipa</option>
                    <option value="perfil">Por perfil</option>
                </select>
            </label>
            <div id="filtro-equipa" style="display:none;">
                <label>Selecione a equipa:
                    <select name="filtro">
                        <?php foreach ($equipas as $e): ?>
                            <option value="<?php echo $e['id']; ?>"><?php echo htmlspecialchars($e['nome']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
            </div>
            <div id="filtro-perfil" style="display:none;">
                <label>Selecione o perfil:
                    <select name="filtro">
                        <?php foreach ($perfis as $p): ?>
                            <option value="<?php echo $p['id']; ?>"><?php echo htmlspecialchars($p['nome']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
            </div>
            <button type="submit" class="btn">Exportar para Excel</button>
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
    <script>
        // Inicializa selects corretos ao recarregar
        onTipoChange();
    </script>
</body>
</html>