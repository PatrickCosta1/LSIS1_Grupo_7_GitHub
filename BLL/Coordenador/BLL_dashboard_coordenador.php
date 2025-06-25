<?php
require_once __DIR__ . '/../../DAL/Coordenador/DAL_dashboard_coordenador.php';

class CoordenadorDashboardManager {
    private $dal;
    public function __construct() {
        $this->dal = new DAL_DashboardCoordenador();
    }
    public function getCoordenadorName($userId) {
        return $this->dal->getCoordenadorName($userId);
    }
    public function getEquipasByCoordenador($userId) {
        return $this->dal->getEquipasByCoordenador($userId);
    }
    public function getColaboradoresByEquipa($equipaId) {
        return $this->dal->getColaboradoresByEquipa($equipaId);
    }
    public function getIndicadoresEquipa($equipaId) {
        return $this->dal->getIndicadoresEquipa($equipaId);
    }
    public function getIdadesPorEquipa($equipaId) {
        return $this->dal->getIdadesPorEquipa($equipaId);
    }
    public function getAniversariantesEquipaMes($equipaId) {
        return $this->dal->getAniversariantesEquipaMes($equipaId);
    }
    public function getEmailsTipoPorEquipa($equipaId) {
        return $this->dal->getEmailsTipoPorEquipa($equipaId);
    }
    public function getGenerosPorEquipa($equipaId) {
        return $this->dal->getGenerosPorEquipa($equipaId);
    }
    public function getEvolucaoColaboradores($equipaId) {
        return $this->dal->getEvolucaoColaboradores($equipaId);
    }
    public function getFaltasPorColaborador($equipaId) {
        return $this->dal->getFaltasPorColaborador($equipaId);
    }
    public function getAvaliacoesDesempenhoPorEquipa($equipaId) {
        return $this->dal->getAvaliacoesDesempenhoPorEquipa($equipaId);
    }
}
?>
