<?php
// Script para verificação automática de vouchers expirados
// Para executar via cron job diário: 0 9 * * * /usr/bin/php /path/to/verificar_vouchers_automatico.php

require_once __DIR__ . '/../BLL/Admin/BLL_alertas.php';

$alertasBLL = new AdminAlertasManager();

try {
    $alertasEnviados = $alertasBLL->processarAlertasAutomaticos();
    
    // Log do resultado
    $logMessage = date('Y-m-d H:i:s') . " - [CRON] Processados {$alertasEnviados} alertas de vouchers expirados automaticamente.\n";
    $logDir = __DIR__ . '/../logs';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0777, true);
    }
    file_put_contents($logDir . '/vouchers_alertas_automaticos.log', $logMessage, FILE_APPEND | LOCK_EX);
    
    echo $logMessage;
    
} catch (Exception $e) {
    $errorMessage = date('Y-m-d H:i:s') . " - [ERRO CRON] " . $e->getMessage() . "\n";
    file_put_contents($logDir . '/vouchers_alertas_automaticos.log', $errorMessage, FILE_APPEND | LOCK_EX);
    echo $errorMessage;
}
?>
