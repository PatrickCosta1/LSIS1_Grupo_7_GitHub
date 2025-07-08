<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['profile'] !== 'admin') {
    header('Location: ../Comuns/erro.php');
    exit();
}

require_once '../../BLL/Admin/BLL_alertas.php';
$alertasBLL = new AdminAlertasManager();

$vouchersExpirados = $alertasBLL->verificarVouchersExpirados();
$estatisticas = $alertasBLL->getEstatisticasVouchers();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Alertas Vouchers - Portal Tlantic</title>
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
        <h1>🚨 Gestão de Alertas - Vouchers NOS</h1>
        
        <div class="vouchers-container">
            <div class="alert-info">
                <strong>ℹ️ Sistema Automático:</strong> Os colaboradores com vouchers expirados há mais de 23 meses recebem automaticamente alertas quando acedem ao portal.
                <ul>
                    <li>📱 <strong>Notificação no portal</strong> (aba Notificações)</li>
                    <li>📧 <strong>Email de alerta urgente</strong></li>
                    <li>🔔 <strong>Notificação também enviada para o RH</strong></li>
                    <li>🔄 <strong>Um alerta por dia por colaborador</strong> (evita spam)</li>
                </ul>
            </div>

            <?php if (!empty($vouchersExpirados)): ?>
            <div class="debug-info">
                <strong>🔍 Colaboradores com vouchers expirados (receberão alertas automáticos):</strong>
                <br>
                <?php foreach ($vouchersExpirados as $v): ?>
                    <strong><?= htmlspecialchars($v['nome']) ?></strong> 
                    (User ID: <?= $v['utilizador_id'] ?>, Email: <?= htmlspecialchars($v['email']) ?>, 
                    Expirado há <span style="color: #d32f2f; font-weight: bold;"><?= floor($v['dias_expirado'] / 30) ?> meses</span>)
                    <br>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?= $estatisticas['total_com_voucher'] ?></div>
                    <div class="stat-label">Total com Voucher</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number expired"><?= $estatisticas['expirados'] ?></div>
                    <div class="stat-label">Expirados (>23 meses)</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number warning"><?= $estatisticas['proximos_vencimento'] ?></div>
                    <div class="stat-label">Próximos Vencimento (20-23 meses)</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number valid"><?= $estatisticas['validos'] ?></div>
                    <div class="stat-label">Válidos (<20 meses)</div>
                </div>
            </div>
            
            <h2>📋 Vouchers Expirados - Monitorização Ativa</h2>
            
            <table class="tabela-colaboradores">
                <thead>
                    <tr>
                        <th>👤 Colaborador</th>
                        <th>📧 Email</th>
                        <th>📅 Última Emissão</th>
                        <th>⏰ Dias Expirado</th>
                        <th>📊 Meses Expirado</th>
                        <th>🔔 Status Alerta</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($vouchersExpirados as $voucher): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($voucher['nome']) ?></strong></td>
                        <td><?= htmlspecialchars($voucher['email']) ?></td>
                        <td><?= date('d/m/Y', strtotime($voucher['voucher_nos'])) ?></td>
                        <td class="expired"><?= $voucher['dias_expirado'] ?> dias</td>
                        <td class="expired"><?= floor($voucher['dias_expirado'] / 30) ?> meses</td>
                        <td style="color: #ff8c00; font-weight: 600;">⚠️ Alerta automático ativo</td>
                    </tr>
                    <?php endforeach; ?>
                    
                    <?php if (empty($vouchersExpirados)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: #38a169; padding: 40px; font-size: 1.2rem;">
                            ✅ <strong>Excelente!</strong> Nenhum voucher expirado encontrado!
                            <br><small style="opacity: 0.8;">Todos os vouchers estão dentro da validade.</small>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
</html>
