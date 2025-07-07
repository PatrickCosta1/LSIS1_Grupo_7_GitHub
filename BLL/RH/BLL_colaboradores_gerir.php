<?php
require_once __DIR__ . '/../../DAL/RH/DAL_colaboradores_gerir.php';

class RHColaboradoresManager {
    private $dal;
    public function __construct() {
        $this->dal = new DAL_ColaboradoresGerir();
    }

    public function getAllColaboradores($excludeUserId = null) {
        return $this->dal->getAllColaboradores($excludeUserId);
    }

    public function addColaborador($dados) {
        return $this->dal->addColaborador($dados);
    }

    public function getAllEquipas() {
        return $this->dal->getAllEquipas();
    }

    public function getAllPerfis() {
        return $this->dal->getAllPerfis();
    }

    public function getColaboradoresPorEquipa($equipaId) {
        return $this->dal->getColaboradoresPorEquipa($equipaId);
    }

    public function getColaboradoresPorPerfil($perfilId) {
        return $this->dal->getColaboradoresPorPerfil($perfilId);
    }

    public function criarUtilizadorConvidado($nome, $emailPessoal, $dataInicioContrato, $perfilDestinoId) {
        try {
            $token = bin2hex(random_bytes(24)); // Token mais longo para segurança
            
            // Criar utilizador temporário
            $utilizadorId = $this->dal->criarUtilizador($nome, $emailPessoal, $dataInicioContrato, $perfilDestinoId, $token);
            
            if ($utilizadorId) {
                // Criar entrada na tabela onboarding_temp
                $onboardingData = [
                    'nome' => $nome,
                    'email_pessoal' => $emailPessoal,
                    'data_inicio_contrato' => $dataInicioContrato,
                    'perfil_destino_id' => $perfilDestinoId,
                    'token' => $token,
                    'utilizador_id' => $utilizadorId
                ];
                
                $onboardingId = $this->dal->criarOnboardingTemp($onboardingData);
                
                if ($onboardingId) {
                    // Enviar email de onboarding
                    require_once __DIR__ . '/../Comuns/BLL_notificacoes.php';
                    $notBLL = new NotificacoesManager();
                    
                    $linkOnboarding = "http://localhost/LSIS/LSIS1_Grupo_7_GitHub/UI/Convidado/onboarding_convidado.php?token=" . $token;
                    
                    $assunto = "Bem-vindo à Tlantic - Complete o seu registo";
                    $mensagem = "Olá $nome,\n\nBem-vindo à equipa Tlantic!\n\nPara completar o seu processo de integração, aceda ao seguinte link:\n\n$linkOnboarding\n\nEste link é válido por 7 dias.\n\nCumprimentos,\nEquipa Tlantic";
                    
                    $emailEnviado = $notBLL->enviarEmailSimples($emailPessoal, $assunto, $mensagem);
                    
                    if ($emailEnviado) {
                        error_log("Email de onboarding enviado para: $emailPessoal");
                    } else {
                        error_log("Falha no envio do email para: $emailPessoal");
                    }
                    
                    return true;
                }
            }
            
            return false;
            
        } catch (Exception $e) {
            error_log("Erro no processo de onboarding: " . $e->getMessage());
            return false;
        }
    }

    public function criarOnboardingTemp($dados) {
        return $this->dal->criarOnboardingTemp($dados);
    }

    public function getOnboardingTempByToken($token) {
        return $this->dal->getOnboardingTempByToken($token);
    }

    public function submeterOnboardingTemp($token, $dados) {
        return $this->dal->submeterOnboardingTemp($token, $dados);
    }

    public function listarOnboardingsPendentes() {
        return $this->dal->listarOnboardingsPendentes();
    }

    public function aprovarOnboarding($token) {
        return $this->dal->aprovarOnboarding($token);
    }

    public function recusarOnboarding($token) {
        return $this->dal->recusarOnboarding($token);
    }

    public function removerColaboradorComUtilizador($colaboradorId) {
        return $this->dal->removerColaboradorComUtilizador($colaboradorId);
    }
}
?>