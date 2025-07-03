<?php
require_once __DIR__ . '/../../DAL/RH/DAL_dashboard_rh.php';

class RHDashboardManager {
    private $dal;
    public function __construct() {
        $this->dal = new DAL_DashboardRH();
    }
    public function getRHName($userId) {
        return $this->dal->getRHName($userId);
    }

    // Novo método para obter equipas e número de colaboradores
    public function getEquipasComMembros() {
        return $this->dal->getEquipasComMembros();
    }

    // Novo método para obter idades dos colaboradores por equipa
    public function getIdadesColaboradoresPorEquipa() {
        return $this->dal->getIdadesColaboradoresPorEquipa();
    }

    // Novo método para obter distribuição por nível hierárquico
    public function getDistribuicaoNivelHierarquico() {
        return $this->dal->getDistribuicaoNivelHierarquico();
    }

    // Junta cargos e contagem por nível hierárquico
    public function getCargosPorNivelHierarquico() {
        return $this->dal->getCargosPorNivelHierarquico();
    }

    // Adicionado: tempo médio na empresa por equipa
    public function getTempoMedioEmpresaPorEquipa() {
        return $this->dal->getTempoMedioEmpresaPorEquipa();
    }

    // Adicionado: remuneração média por equipa
    public function getRemuneracaoMediaPorEquipa() {
        return $this->dal->getRemuneracaoMediaPorEquipa();
    }

    // Adicionado: distribuição de género por equipa
    public function getDistribuicaoGeneroPorEquipa() {
        return $this->dal->getDistribuicaoGeneroPorEquipa();
    }

    public function getNomesColaboradoresPorEquipa() {
        return $this->dal->getNomesColaboradoresPorEquipa();
    }

    // Novo: distribuição geográfica
    public function getDistribuicaoGeografica() {
        return $this->dal->getDistribuicaoGeografica();
    }

    // Novo: localidades dos colaboradores por equipa
    public function getLocalidadesPorEquipa() {
        return $this->dal->getLocalidadesPorEquipa();
    }
}
?>