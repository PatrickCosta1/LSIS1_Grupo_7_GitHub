<?php
require_once __DIR__ . '/../Database.php';

class DAL_AlertasAdmin {
    public function getAllAlertas() {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT * FROM alertas");
        return $stmt->fetchAll();
    }

    public function criarAlerta($tipo, $descricao, $periodicidade_meses, $ativo, $destinatario) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("INSERT INTO alertas (tipo, descricao, periodicidade_meses, ativo, destinatario) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$tipo, $descricao, $periodicidade_meses, $ativo, $destinatario]);
    }

    public function getAlertasParaNotificar() {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT * FROM alertas WHERE ativo = 1");
        return $stmt->fetchAll();
    }

    public function verificarVouchersExpirados() {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            SELECT c.id, c.nome, c.voucher_nos, c.utilizador_id, u.email,
                   DATEDIFF(CURDATE(), c.voucher_nos) as dias_expirado
            FROM colaboradores c
            INNER JOIN utilizadores u ON c.utilizador_id = u.id
            WHERE c.voucher_nos IS NOT NULL 
            AND c.voucher_nos != '0000-00-00'
            AND c.voucher_nos < DATE_SUB(CURDATE(), INTERVAL 23 MONTH)
            ORDER BY c.voucher_nos ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEstatisticasVouchers() {
        $pdo = Database::getConnection();
        
        // Total de colaboradores com voucher
        $stmt1 = $pdo->query("
            SELECT COUNT(*) as total_com_voucher 
            FROM colaboradores 
            WHERE voucher_nos IS NOT NULL AND voucher_nos != '0000-00-00'
        ");
        $totalComVoucher = $stmt1->fetchColumn();
        
        // Vouchers expirados (>23 meses)
        $stmt2 = $pdo->query("
            SELECT COUNT(*) as expirados 
            FROM colaboradores 
            WHERE voucher_nos IS NOT NULL 
            AND voucher_nos != '0000-00-00'
            AND voucher_nos < DATE_SUB(CURDATE(), INTERVAL 23 MONTH)
        ");
        $expirados = $stmt2->fetchColumn();
        
        // Vouchers próximos do vencimento (20-23 meses)
        $stmt3 = $pdo->query("
            SELECT COUNT(*) as proximos_vencimento 
            FROM colaboradores 
            WHERE voucher_nos IS NOT NULL 
            AND voucher_nos != '0000-00-00'
            AND voucher_nos BETWEEN DATE_SUB(CURDATE(), INTERVAL 23 MONTH) 
            AND DATE_SUB(CURDATE(), INTERVAL 20 MONTH)
        ");
        $proximosVencimento = $stmt3->fetchColumn();
        
        return [
            'total_com_voucher' => $totalComVoucher,
            'expirados' => $expirados,
            'proximos_vencimento' => $proximosVencimento,
            'validos' => $totalComVoucher - $expirados - $proximosVencimento
        ];
    }

    public function getEmailColaborador($utilizadorId) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT email FROM utilizadores WHERE id = ?");
        $stmt->execute([$utilizadorId]);
        return $stmt->fetchColumn();
    }

    public function verificarVoucherPorUtilizador($utilizadorId) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            SELECT c.id, c.nome, c.voucher_nos, c.utilizador_id, u.email,
                   DATEDIFF(CURDATE(), c.voucher_nos) as dias_expirado
            FROM colaboradores c
            INNER JOIN utilizadores u ON c.utilizador_id = u.id
            WHERE c.utilizador_id = ?
            AND c.voucher_nos IS NOT NULL 
            AND c.voucher_nos != '0000-00-00'
            AND c.voucher_nos < DATE_SUB(CURDATE(), INTERVAL 23 MONTH)
        ");
        $stmt->execute([$utilizadorId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function alertaEnviadoHoje($utilizadorId, $tipo) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            SELECT id FROM alertas_enviados 
            WHERE utilizador_id = ? AND tipo = ? AND DATE(data_envio) = CURDATE()
        ");
        $stmt->execute([$utilizadorId, $tipo]);
        return $stmt->fetch() !== false;
    }
    
    public function registarAlertaEnviado($utilizadorId, $tipo) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            INSERT INTO alertas_enviados (utilizador_id, tipo, data_envio) 
            VALUES (?, ?, NOW())
            ON DUPLICATE KEY UPDATE data_envio = NOW()
        ");
        return $stmt->execute([$utilizadorId, $tipo]);
    }

    // Novos métodos para alertas fiscais
    public function verificarAlteracoesFiscaisPendentes($utilizadorId) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            SELECT laf.*, c.utilizador_id, c.nome as colaborador_nome, u.email
            FROM logs_alteracoes_fiscais laf
            INNER JOIN colaboradores c ON laf.colaborador_id = c.id
            INNER JOIN utilizadores u ON c.utilizador_id = u.id
            WHERE c.utilizador_id = ?
            AND laf.data_alteracao >= DATE_SUB(NOW(), INTERVAL 1 DAY)
            AND NOT EXISTS (
                SELECT 1 FROM alertas_enviados ae 
                WHERE ae.utilizador_id = c.utilizador_id 
                AND ae.tipo = 'alteracao_fiscal'
                AND ae.campo_alterado = laf.campo
                AND DATE(ae.data_envio) = DATE(laf.data_alteracao)
            )
            ORDER BY laf.data_alteracao DESC
        ");
        $stmt->execute([$utilizadorId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function registarAlertaFiscalEnviado($utilizadorId, $campo, $valorAntigo, $valorNovo) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            INSERT INTO alertas_enviados (utilizador_id, tipo, campo_alterado, valor_antigo, valor_novo, data_envio) 
            VALUES (?, 'alteracao_fiscal', ?, ?, ?, NOW())
        ");
        return $stmt->execute([$utilizadorId, $campo, $valorAntigo, $valorNovo]);
    }

    public function getDocumentoNecessario($campo) {
        $documentos = [
            'cc' => 'Comprovativo do Cartão de Cidadão',
            'iban' => 'Comprovativo IBAN (extrato bancário)',
            'morada_fiscal' => 'Modelo 99 (Declaração de IRS)',
            'estado_civil' => 'Modelo 99 + Certidão de Estado Civil',
            'nif' => 'Modelo 99 (Declaração de IRS)',
            'situacao_irs' => 'Modelo 99 (Declaração de IRS)',
            'dependentes' => 'Modelo 99 + Documentos dos dependentes',
            'irs_jovem' => 'Modelo 99 (Declaração de IRS)',
            'primeiro_ano_descontos' => 'Modelo 99 (Declaração de IRS)',
            'cartao_continente' => 'Comprovativo do Cartão Continente'
        ];
        
        return $documentos[$campo] ?? 'Documentação fiscal';
    }
}
?>