<?php
// Script para ser executado diariamente via cron job
// Adicionar ao crontab: 0 9 * * * /usr/bin/php /path/to/verificar_vouchers_diario.php

require_once __DIR__ . '/../BLL/Admin/BLL_alertas.php';

$alertasBLL = new AdminAlertasManager();
$alertasEnviados = $alertasBLL->processarAlertasAutomaticos();

// Log do resultado
$logMessage = date('Y-m-d H:i:s') . " - Processados {$alertasEnviados} alertas de vouchers expirados.\n";
file_put_contents(__DIR__ . '/logs/vouchers_alertas.log', $logMessage, FILE_APPEND | LOCK_EX);

echo $logMessage;
?>
