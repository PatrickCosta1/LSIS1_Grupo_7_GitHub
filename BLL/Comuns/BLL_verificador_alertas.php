<?php

class VerificadorAlertas {
    
    public static function verificarAlertasColaborador($utilizadorId) {
        // Verificar alertas de voucher NOS
        require_once __DIR__ . '/../Admin/BLL_alertas.php';
        $alertasBLL = new AdminAlertasManager();
        
        try {
            // Verificar vouchers expirados
            $alertasBLL->verificarVoucherColaborador($utilizadorId);
            
            // Verificar alterações fiscais
            $alertasBLL->verificarAlteracoesFiscaisColaborador($utilizadorId);
            
        } catch (Exception $e) {
            error_log("Erro ao verificar alertas para utilizador {$utilizadorId}: " . $e->getMessage());
        }
    }
}
?>
