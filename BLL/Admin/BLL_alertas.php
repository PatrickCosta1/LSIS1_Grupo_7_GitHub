<?php
require_once __DIR__ . '/../../DAL/Admin/DAL_alertas.php';

class AdminAlertasManager {
    private $dal;
    public function __construct() {
        $this->dal = new DAL_AlertasAdmin();
    }
    
    public function getAllAlertas() {
        return $this->dal->getAllAlertas();
    }
    
    public function criarAlerta($tipo, $descricao, $periodicidade_meses, $ativo, $destinatario) {
        return $this->dal->criarAlerta($tipo, $descricao, $periodicidade_meses, $ativo, $destinatario);
    }
    
    public function verificarVouchersExpirados() {
        return $this->dal->verificarVouchersExpirados();
    }
    
    public function getEstatisticasVouchers() {
        return $this->dal->getEstatisticasVouchers();
    }
    
    // Método para verificar automaticamente vouchers do utilizador logado
    public function verificarVoucherColaborador($utilizadorId) {
        require_once __DIR__ . '/../Comuns/BLL_notificacoes.php';
        require_once __DIR__ . '/../Comuns/BLL_email.php';
        
        $voucher = $this->dal->verificarVoucherPorUtilizador($utilizadorId);
        
        if ($voucher && $voucher['dias_expirado'] > 0) {
            $mesesExpirado = floor($voucher['dias_expirado'] / 30);
            
            // Verificar se já foi enviado alerta hoje
            if (!$this->dal->alertaEnviadoHoje($utilizadorId, 'voucher_expirado')) {
                $notBLL = new NotificacoesManager();
                $emailBLL = new EmailManager();
                
                // Notificar colaborador (portal)
                $mensagemColab = "🚨 ALERTA URGENTE: O seu voucher NOS expirou há {$mesesExpirado} meses (última emissão: " . date('d/m/Y', strtotime($voucher['voucher_nos'])) . "). Contacte IMEDIATAMENTE os Recursos Humanos para renovação.";
                $notBLL->criarNotificacao($utilizadorId, $mensagemColab, 'voucher_expirado');
                
                // Notificar colaborador (email)
                if (!empty($voucher['email'])) {
                    $emailBLL->enviarEmail(
                        $voucher['email'],
                        "🚨 URGENTE: Voucher NOS Expirado - Ação Necessária",
                        "Caro(a) {$voucher['nome']},<br><br><strong>O seu voucher NOS expirou há {$mesesExpirado} meses.</strong><br><br>📅 Última emissão: " . date('d/m/Y', strtotime($voucher['voucher_nos'])) . "<br><br>⚠️ <strong>É necessário contactar os Recursos Humanos URGENTEMENTE para renovação.</strong><br><br>Esta situação pode afetar os seus benefícios corporativos e requer atenção imediata."
                    );
                }
                
                // Notificar RH também
                $mensagemRH = "Voucher NOS do colaborador {$voucher['nome']} expirou há {$mesesExpirado} meses (última emissão: " . date('d/m/Y', strtotime($voucher['voucher_nos'])) . "). Renovação necessária.";
                $notBLL->notificarRH($mensagemRH, 'voucher_expirado');
                $emailBLL->enviarEmailRH("Alerta: Voucher NOS Expirado - {$voucher['nome']}", $mensagemRH);
                
                // Registar que o alerta foi enviado hoje
                $this->dal->registarAlertaEnviado($utilizadorId, 'voucher_expirado');
                
                return true;
            }
        }
        
        return false;
    }
    
    // Método para verificar automaticamente alterações fiscais do utilizador logado
    public function verificarAlteracoesFiscaisColaborador($utilizadorId) {
        require_once __DIR__ . '/../Comuns/BLL_notificacoes.php';
        require_once __DIR__ . '/../Comuns/BLL_email.php';
        
        $alteracoes = $this->dal->verificarAlteracoesFiscaisPendentes($utilizadorId);
        
        if (!empty($alteracoes)) {
            $notBLL = new NotificacoesManager();
            $emailBLL = new EmailManager();
            
            foreach ($alteracoes as $alteracao) {
                $campo = $alteracao['campo'];
                $valorAntigo = $alteracao['valor_antigo'] ?? 'N/A';
                $valorNovo = $alteracao['valor_novo'] ?? 'N/A';
                $colaboradorNome = $alteracao['colaborador_nome'];
                $email = $alteracao['email'];
                $documento = $this->dal->getDocumentoNecessario($campo);
                
                // Traduzir nome do campo para português
                $camposPT = [
                    'cc' => 'Cartão de Cidadão',
                    'iban' => 'IBAN',
                    'morada_fiscal' => 'Morada Fiscal',
                    'estado_civil' => 'Estado Civil',
                    'nif' => 'NIF',
                    'situacao_irs' => 'Situação IRS',
                    'dependentes' => 'Número de Dependentes',
                    'irs_jovem' => 'IRS Jovem',
                    'primeiro_ano_descontos' => 'Primeiro Ano de Descontos',
                    'cartao_continente' => 'Cartão Continente'
                ];
                
                $campoNome = $camposPT[$campo] ?? $campo;
                
                // Notificar colaborador (portal)
                $mensagemColab = "📋 ATUALIZAÇÃO FISCAL: O seu campo '{$campoNome}' foi alterado na ficha. É necessário entregar o documento: {$documento}. Anexe o comprovativo na sua ficha do colaborador.";
                $notBLL->criarNotificacao($utilizadorId, $mensagemColab, 'alteracao_fiscal');
                
                // Notificar colaborador (email)
                if (!empty($email)) {
                    $emailSubject = "📋 Atualização Fiscal Necessária - {$campoNome}";
                    $emailBody = "Caro(a) {$colaboradorNome},<br><br>";
                    $emailBody .= "<strong>Foi detetada uma alteração no campo '{$campoNome}' da sua ficha de colaborador.</strong><br><br>";
                    $emailBody .= "📄 <strong>Documento necessário:</strong> {$documento}<br>";
                    $emailBody .= "📅 <strong>Data da alteração:</strong> " . date('d/m/Y H:i', strtotime($alteracao['data_alteracao'])) . "<br>";
                    
                    if ($valorAntigo !== 'N/A' && $valorNovo !== 'N/A') {
                        $emailBody .= "🔄 <strong>Alteração:</strong> '{$valorAntigo}' → '{$valorNovo}'<br>";
                    }
                    
                    $emailBody .= "<br>⚠️ <strong>É necessário anexar a documentação fiscal atualizada na sua ficha do colaborador.</strong><br>";
                    $emailBody .= "<br>Esta regularização é obrigatória para manter os seus dados fiscais em conformidade.";
                    
                    $emailBLL->enviarEmail($email, $emailSubject, $emailBody);
                }
                
                // Notificar RH também
                $mensagemRH = "Alteração fiscal detetada: Campo '{$campoNome}' do colaborador {$colaboradorNome} foi alterado. Documento necessário: {$documento}.";
                $notBLL->notificarRH($mensagemRH, 'alteracao_fiscal');
                $emailBLL->enviarEmailRH("Alerta Fiscal: {$campoNome} - {$colaboradorNome}", $mensagemRH);
                
                // Registar que o alerta foi enviado
                $this->dal->registarAlertaFiscalEnviado($utilizadorId, $campo, $valorAntigo, $valorNovo);
            }
            
            return count($alteracoes);
        }
        
        return 0;
    }
}
?>