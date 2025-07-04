<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['profile'], ['rh', 'admin'])) {
    header('Location: ../Comuns/erro.php');
    exit();
}

require_once '../../BLL/RH/BLL_exportar.php';
$exportBLL = new RHExportarManager();

// Carregar equipas e perfis para o formulário
$equipas = $exportBLL->getAllEquipas();
$perfis = $exportBLL->getAllPerfis();

// Exportação
if (isset($_GET['export'])) {
    $tipo = $_GET['export'];
    $filtro = isset($_GET['filtro']) ? $_GET['filtro'] : null;

    $colaboradores = [];
    $nomeArquivo = 'colaboradores';
    
    // Debug: log dos parâmetros recebidos
    error_log("Exportação - Tipo: $tipo, Filtro: $filtro");
    
    if ($tipo === 'colaboradores') {
        $colaboradores = $exportBLL->getAllColaboradores();
        $nomeArquivo = 'todos_colaboradores';
    } elseif ($tipo === 'equipa' && $filtro !== null && $filtro !== '') {
        if (is_numeric($filtro) && (int)$filtro > 0) {
            $colaboradores = $exportBLL->getColaboradoresPorEquipa((int)$filtro);
            $nomeArquivo = 'colaboradores_equipa_' . $filtro;
            
            // Debug: verificar se encontrou colaboradores
            error_log("Colaboradores encontrados para equipa $filtro: " . count($colaboradores));
        }
    } elseif ($tipo === 'perfil' && $filtro !== null && $filtro !== '') {
        if (is_numeric($filtro) && (int)$filtro > 0) {
            $colaboradores = $exportBLL->getColaboradoresPorPerfil((int)$filtro);
            $nomeArquivo = 'colaboradores_perfil_' . $filtro;
        }
    }

    // Log final para debug
    error_log("Total de colaboradores para exportar: " . count($colaboradores));

    // Limpar qualquer output anterior
    if (ob_get_level()) {
        ob_end_clean();
    }

    // Headers para download
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $nomeArquivo . '_' . date('Y-m-d') . '.csv"');
    header('Pragma: no-cache');
    header('Expires: 0');
    
    // Abrir output stream
    $output = fopen('php://output', 'w');
    
    // BOM para Excel UTF-8
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
    
    // Cabeçalhos CSV
    fputcsv($output, $exportBLL->getCabecalhosCSV(), ';');
    
    // Dados
    if (!empty($colaboradores)) {
        foreach ($colaboradores as $col) {
            fputcsv($output, $exportBLL->formatarColaboradorParaCSV($col), ';');
        }
    } else {
        // Linha indicando que não há dados
        fputcsv($output, ['Nenhum colaborador encontrado para os critérios selecionados'], ';');
    }
    
    fclose($output);
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Exportar Dados - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/CSS/RH/exportar.css">
    <script>
    // Mostra/esconde selects conforme opção
    function onTipoChange() {
        var tipo = document.getElementById('tipo-export').value;
        document.getElementById('filtro-equipa').style.display = (tipo === 'equipa') ? 'block' : 'none';
        document.getElementById('filtro-perfil').style.display = (tipo === 'perfil') ? 'block' : 'none';
        
        // Limpar selects quando mudamos o tipo
        var selectEquipa = document.querySelector('#filtro-equipa select[name="filtro"]');
        var selectPerfil = document.querySelector('#filtro-perfil select[name="filtro"]');
        if (selectEquipa) selectEquipa.value = '';
        if (selectPerfil) selectPerfil.value = '';
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
               <div class="dropdown-equipas">
                <a href="equipas.php" class="equipas-link">
                    Equipas
                    <span class="seta-baixo">&#9662;</span>
                </a>
                <div class="dropdown-menu">
                    <a href="relatorios.php">Relatórios</a>
                    <a href="dashboard_rh.php">Dashboard</a>
                </div>
            </div>
            <div class="dropdown-colaboradores">
                <a href="colaboradores_gerir.php" class="colaboradores-link">
                    Colaboradores
                    <span class="seta-baixo">&#9662;</span>
                </a>
                <div class="dropdown-menu">
                    <a href="exportar.php">Exportar</a>
                </div>
            </div>
            <div class="dropdown-gestao">
                <a href="#" class="gestao-link">
                    Gestão
                    <span class="seta-baixo">&#9662;</span>
                </a>
                <div class="dropdown-menu">
                    <a href="gerir_beneficios.php">Gerir Benefícios</a>
                    <a href="gerir_formacoes.php">Gerir Formações</a>
                    <a href="gerir_recibos.php">Submeter Recibos</a>
                </div>
            </div>
            <a href="../Comuns/notificacoes.php">Notificações</a>
            <div class="dropdown-perfil">
                <a href="../Comuns/perfil.php" class="perfil-link">
                    Perfil
                    <span class="seta-baixo">&#9662;</span>
                </a>
                <div class="dropdown-menu">
                    <a href="../Colaborador/ficha_colaborador.php">Perfil Colaborador</a>
                </div>
            </div>
            <a href="../Comuns/logout.php">Sair</a>
            <?php endif; ?>
        </nav>
    </header>
    <div class="portal-brand">
        <div class="color-bar">
            <div class="color-segment"></div>
            <div class="color-segment"></div>
            <div class="color-segment"></div>
        </div>
        <span class="portal-text">Portal Do Colaborador</span>
    </div>
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
                        <option value="">Selecione...</option>
                        <?php foreach ($equipas as $e): ?>
                            <option value="<?php echo $e['id']; ?>"><?php echo htmlspecialchars($e['nome']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
            </div>
            <div id="filtro-perfil" style="display:none;">
                <label>Selecione o perfil:
                    <select name="filtro">
                        <option value="">Selecione...</option>
                        <?php foreach ($perfis as $p): ?>
                            <option value="<?php echo $p['id']; ?>"><?php echo htmlspecialchars($p['nome']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
            </div>
            <button type="submit" class="btn">Exportar para Excel</button>
        </form>
    </main>
    <script>
        // Inicializa selects corretos ao recarregar
        onTipoChange();
        
        // Validar formulário antes de enviar
        document.querySelector('form').addEventListener('submit', function(e) {
            const tipo = document.getElementById('tipo-export').value;
            const filtro = document.querySelector('select[name="filtro"]');
            
            if ((tipo === 'equipa' || tipo === 'perfil') && filtro && filtro.value === '') {
                e.preventDefault();
                alert('Por favor, selecione uma opção de filtro.');
                return false;
            }
        });
    </script>
</body>
</html>