<?php
require_once __DIR__ . '/../../DAL/Coordenador/DAL_dashboard_coordenador.php';

class CoordenadorDashboardManager {
    private $dal;
    
    public function __construct() {
        $this->dal = new DAL_DashboardCoordenador();
    }

    public function getEquipasByCoordenador($userId) {
        return $this->dal->getEquipasByCoordenador($userId);
    }

    public function getEquipasComMembros($userId) {
        return $this->dal->getEquipasComMembros($userId);
    }

    public function getIdadesColaboradoresPorEquipa($userId) {
        return $this->dal->getIdadesColaboradoresPorEquipa($userId);
    }

    public function getTemposNaEmpresaPorEquipa($userId) {
        return $this->dal->getTemposNaEmpresaPorEquipa($userId);
    }

    public function getRemuneracaoMediaPorEquipa($userId) {
        return $this->dal->getRemuneracaoMediaPorEquipa($userId);
    }

    public function getDistribuicaoGeneroPorEquipa($userId) {
        return $this->dal->getDistribuicaoGeneroPorEquipa($userId);
    }

    public function getColaboradoresLocalidadePorEquipa($userId) {
        return $this->dal->getColaboradoresLocalidadePorEquipa($userId);
    }

    public function getDistribuicaoNivelHierarquico($userId) {
        return $this->dal->getDistribuicaoNivelHierarquico($userId);
    }

    public function getCargosPorNivelHierarquico($userId) {
        return $this->dal->getCargosPorNivelHierarquico($userId);
    }

    public function getCoordenadorName($userId) {
        return $this->dal->getCoordenadorName($userId);
    }

    public function getTaxaRetencaoPorEquipa($userId, $ano = null) {
        return $this->dal->getTaxaRetencaoPorEquipa($userId, $ano);
    }

    public function getTaxaRetencaoGlobal($userId, $ano = null) {
        return $this->dal->getTaxaRetencaoGlobal($userId, $ano);
    }

    public function getColaboradoresByEquipa($equipaId) {
        return $this->dal->getColaboradoresByEquipa($equipaId);
    }
}
?>