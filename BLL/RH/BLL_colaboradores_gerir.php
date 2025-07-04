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

    public function criarUtilizadorConvidado($username, $perfil_convidado, $password) {
        return $this->dal->criarUtilizadorConvidado($username, $perfil_convidado, $password);
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