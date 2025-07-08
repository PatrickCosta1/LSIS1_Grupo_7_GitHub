<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['profile'] !== 'admin') {
    header('Location: ../Comuns/erro.php');
    exit();
}

require_once '../../DAL/Database.php';

// Buscar logs de alterações fiscais recentes
$pdo = Database::getConnection();
$stmt = $pdo->query("
    SELECT laf.*, c.nome as colaborador_nome, u.email,
           CASE 
               WHEN laf.campo = 'cc' THEN 'Cartão de Cidadão'
               WHEN laf.campo = 'iban' THEN 'IBAN'
               WHEN laf.campo = 'morada_fiscal' THEN 'Morada Fiscal'
               WHEN laf.campo = 'estado_civil' THEN 'Estado Civil'
               WHEN laf.campo = 'nif' THEN 'NIF'
               WHEN laf.campo = 'situacao_irs' THEN 'Situação IRS'
               WHEN laf.campo = 'dependentes' THEN 'Nº Dependentes'
               WHEN laf.campo = 'irs_jovem' THEN 'IRS Jovem'
               WHEN laf.campo = 'primeiro_ano_descontos' THEN 'Primeiro Ano Descontos'
               WHEN laf.campo = 'cartao_continente' THEN 'Cartão Continente'
               ELSE laf.campo
           END as campo_nome,
           CASE 
               WHEN laf.tipo_documento = 'mod_99' THEN 'Modelo 99 (Declaração IRS)'
               WHEN laf.tipo_documento = 'comprovativo_cc' THEN 'Comprovativo CC'
               WHEN laf.tipo_documento = 'comprovativo_iban' THEN 'Comprovativo IBAN'
               WHEN laf.tipo_documento = 'comprovativo_cartao_continente' THEN 'Comprovativo Cartão Continente'
               ELSE laf.tipo_documento
           END as documento_necessario
    FROM logs_alteracoes_fiscais laf
    INNER JOIN colaboradores c ON laf.colaborador_id = c.id
    INNER JOIN utilizadores u ON c.utilizador_id = u.id
    WHERE laf.data_alteracao >= DATE_SUB(NOW(), INTERVAL 30 DAY)
    ORDER BY laf.data_alteracao DESC
    LIMIT 50
");
$alteracoesFiscais = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Estatísticas
$stmtStats = $pdo->query("
    SELECT 
        COUNT(*) as total_alteracoes,
        COUNT(DISTINCT laf.colaborador_id) as colaboradores_afetados,
        SUM(CASE WHEN laf.tipo_documento = 'mod_99' THEN 1 ELSE 0 END) as requerem_mod99,
        SUM(CASE WHEN laf.tipo_documento LIKE 'comprovativo_%' THEN 1 ELSE 0 END) as requerem_comprovativos
    FROM logs_alteracoes_fiscais laf
    WHERE laf.data_alteracao >= DATE_SUB(NOW(), INTERVAL 30 DAY)
");
$stats = $stmtStats->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Alertas Fiscais - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/CSS/Admin/base.css">
    <link rel="stylesheet" href="../../assets/CSS/Admin/alertas_vouchers.css">
    <link rel="stylesheet" href="../../assets/CSS/Comuns/header.css">
</head>
<body>
    <header>
        <a href="pagina_inicial_admin.php">
            <img src="../../assets/tlantic-logo2.png" alt="Logo Tlantic" class="logo-header">
        </a>
        <nav>
            <a href="utilizadores.php">Utilizadores</a>
            <a href="permissoes.php">Permissões</a>
            <a href="alertas.php">Alertas</a>
            <a href="../Comuns/perfil.php">Perfil</a>
            <a href="../Comuns/logout.php">Sair</a>
        </nav>
    </header>
    
    <main>
        <h1>📋 Gestão de Alertas - Alterações Fiscais</h1>
        
        <div class="vouchers-container">
            <div class="alert-info">
                <strong>ℹ️ Sistema Automático:</strong> Os colaboradores que alteraram campos fiscais importantes recebem automaticamente alertas sobre documentação necessária.
                <ul>
                    <li>📱 <strong>Notificação no portal</strong> (aba Notificações)</li>
                    <li>📧 <strong>Email informativo</strong> sobre documentos necessários</li>
                    <li>🔔 <strong>Notificação também enviada para o RH</strong></li>
                    <li>📄 <strong>Documentos especificados automaticamente</strong> (Mod. 99, Comprovativos, etc.)</li>
                </ul>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?= $stats['total_alteracoes'] ?></div>
                    <div class="stat-label">Alterações (30 dias)</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number warning"><?= $stats['colaboradores_afetados'] ?></div>
                    <div class="stat-label">Colaboradores Afetados</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number expired"><?= $stats['requerem_mod99'] ?></div>
                    <div class="stat-label">Requerem Modelo 99</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number valid"><?= $stats['requerem_comprovativos'] ?></div>
                    <div class="stat-label">Requerem Comprovativos</div>
                </div>
            </div>
            
            <h2>📋 Alterações Fiscais Recentes - Monitorização Ativa</h2>
            
            <table class="tabela-colaboradores">
                <thead>
                    <tr>
                        <th>👤 Colaborador</th>
                        <th>📧 Email</th>
                        <th>📄 Campo Alterado</th>
                        <th>📅 Data Alteração</th>
                        <th>📋 Documento Necessário</th>
                        <th>🔔 Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($alteracoesFiscais as $alteracao): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($alteracao['colaborador_nome']) ?></strong></td>
                        <td><?= htmlspecialchars($alteracao['email']) ?></td>
                        <td style="color: #0360e9; font-weight: 600;"><?= htmlspecialchars($alteracao['campo_nome']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($alteracao['data_alteracao'])) ?></td>
                        <td style="color: #ff8c00; font-weight: 600;"><?= htmlspecialchars($alteracao['documento_necessario']) ?></td>
                        <td style="color: #ff8c00; font-weight: 600;">⚠️ Alerta automático enviado</td>
                    </tr>
                    <?php endforeach; ?>
                    
                    <?php if (empty($alteracoesFiscais)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: #38a169; padding: 40px; font-size: 1.2rem;">
                            ✅ <strong>Sem alterações fiscais recentes!</strong>
                            <br><small style="opacity: 0.8;">Nenhuma alteração fiscal nos últimos 30 dias.</small>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <?php if (!empty($alteracoesFiscais)): ?>
            <div class="debug-info">
                <strong>🔍 Campos monitorados automaticamente:</strong>
                <br>• <strong>CC, IBAN, Cartão Continente:</strong> Requerem comprovativos específicos
                <br>• <strong>Morada Fiscal, Estado Civil, NIF, Situação IRS, Dependentes, IRS Jovem, Primeiro Ano Descontos:</strong> Requerem Modelo 99 (Declaração IRS)
                <br>• <strong>Alertas enviados:</strong> Portal + Email para colaborador + Notificação para RH
            </div>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
