<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['profile'] !== 'rh') {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/RH/BLL_recibos_vencimento.php';
require_once '../../BLL/Comuns/BLL_notificacoes.php';

$recibosBLL = new RHRecibosManager();
$notBLL = new NotificacoesManager();
$colaboradores = $recibosBLL->getAllColaboradores();

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $colaborador_id = $_POST['colaborador_id'] ?? '';
    $mes = $_POST['mes'] ?? '';
    $ano = $_POST['ano'] ?? '';
    $ficheiro = $_FILES['recibo_pdf'] ?? null;

    // Validação básica
    if (!$colaborador_id || !$mes || !$ano || !$ficheiro || $ficheiro['error'] !== UPLOAD_ERR_OK) {
        $error = "Preencha todos os campos e selecione um ficheiro PDF válido.";
    } else {
        // Buscar colaborador para obter nº mecanográfico e utilizador_id
        $colab = $recibosBLL->getColaboradorById($colaborador_id);
        if (!$colab || empty($colab['num_mecanografico']) || empty($colab['utilizador_id'])) {
            $error = "Colaborador inválido.";
        } else {
            $num_mec = preg_replace('/\D/', '', $colab['num_mecanografico']);
            $nome_base = $num_mec . '' . $mes . '' . $ano;
            $ext = strtolower(pathinfo($ficheiro['name'], PATHINFO_EXTENSION));
            if ($ext !== 'pdf') {
                $error = "O ficheiro deve ser PDF.";
            } else {
                $upload_dir = __DIR__ . '/../../Uploads/Recibos/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
                $nome_ficheiro = $nome_base . '.pdf';
                $caminho_ficheiro = $upload_dir . $nome_ficheiro;
                // Evitar overwrite
                $i = 1;
                while (file_exists($caminho_ficheiro)) {
                    $nome_ficheiro = $nome_base . "_$i.pdf";
                    $caminho_ficheiro = $upload_dir . $nome_ficheiro;
                    $i++;
                }
                if (move_uploaded_file($ficheiro['tmp_name'], $caminho_ficheiro)) {
                    // Guardar na BD
                    $ok = $recibosBLL->submeterRecibo(
                        $colaborador_id,
                        $mes,
                        $ano,
                        'Recibo ' . $mes . '/' . $ano,
                        'Recibos/' . $nome_ficheiro // <-- este valor deve ser exatamente o caminho relativo ao ficheiro guardado
                    );
                    if ($ok) {
                        // Notificar colaborador
                        $notBLL->enviarNotificacao(null, $colab['utilizador_id'], "Foi submetido um novo recibo de vencimento referente a $mes/$ano. Consulte em 'Recibos'.");
                        $success = "Recibo submetido com sucesso!";
                    } else {
                        $error = "Erro ao guardar recibo na base de dados.";
                        @unlink($caminho_ficheiro);
                    }
                } else {
                    $error = "Erro ao guardar o ficheiro.";
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Submeter Recibo de Vencimento</title>
    <link rel="stylesheet" href="../../assets/CSS/RH/gerir_recibos.css">
</head>
<div class="portal-brand">
    <div class="color-bar">
        <div class="color-segment"></div>
        <div class="color-segment"></div>
        <div class="color-segment"></div>
    </div>
    <span class="portal-text">Portal Do Colaborador</span>
</div>
<body>
    <header>
        <img src="../../assets/tlantic-logo2.png" alt="Logo Tlantic" class="logo-header" style="cursor:pointer;" onclick="window.location.href='equipas.php';">
    <nav>
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
    </nav>
    </header>
    <div class="main-container">
        <h1>Submeter Recibo de Vencimento</h1>
        <div class="recibo-form-card">
        <?php if ($success): ?><div class="success-message"><?php echo $success; ?></div><?php endif; ?>
        <?php if ($error): ?><div class="error-message"><?php echo $error; ?></div><?php endif; ?>
        <form method="post" enctype="multipart/form-data" class="recibo-form">
            <div class="form-group">
                <label for="colaborador_id">
                    <span style="color:#299cf3;vertical-align:middle;">👤</span>
                    Colaborador:
                </label>
                <select name="colaborador_id" id="colaborador_id" required>
                    <option value="">Selecione...</option>
                    <?php foreach ($colaboradores as $c): ?>
                        <option value="<?php echo $c['id']; ?>">
                            <?php echo htmlspecialchars($c['nome'] . ' (' . $c['num_mecanografico'] . ')'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="mes">
                    <span style="color:#299cf3;vertical-align:middle;">📅</span>
                    Mês:
                </label>
                <select name="mes" id="mes" required>
                    <option value="">Selecione...</option>
                    <?php
                    $meses = [
                        '01' => 'Janeiro', '02' => 'Fevereiro', '03' => 'Março', '04' => 'Abril',
                        '05' => 'Maio', '06' => 'Junho', '07' => 'Julho', '08' => 'Agosto',
                        '09' => 'Setembro', '10' => 'Outubro', '11' => 'Novembro', '12' => 'Dezembro'
                    ];
                    foreach ($meses as $num => $nome) {
                        echo "<option value=\"$num\">$nome</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="ano">
                    <span style="color:#299cf3;vertical-align:middle;">📆</span>
                    Ano:
                </label>
                <select name="ano" id="ano" required>
                    <option value="">Selecione...</option>
                    <?php
                    $anoAtual = date('Y');
                    for ($a = $anoAtual; $a >= $anoAtual - 10; $a--) {
                        echo "<option value=\"$a\">$a</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="recibo_pdf">
                    <span style="color:#299cf3;vertical-align:middle;">📄</span>
                    Ficheiro PDF:
                </label>
                <input type="file" name="recibo_pdf" id="recibo_pdf" accept="application/pdf" required>
            </div>
            <button type="submit" class="btn">Submeter Recibo</button>
        </form>
        </div>
    </div>
</body>
</html>