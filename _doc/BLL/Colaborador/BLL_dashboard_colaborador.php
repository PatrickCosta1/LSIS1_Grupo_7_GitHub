<?php
require_once __DIR__ . '/../../DAL/Colaborador/DAL_dashboard_colaborador.php';

class ColaboradorDashboardManager {
    private $dal;
    public function __construct() {
        $this->dal = new DAL_DashboardColaborador();
    }
    public function getColaboradorName($userId) {
        return $this->dal->getColaboradorName($userId);
    }

    public function getDashboardData($user_id) {
        // Exemplo: substitui por lógica real de BD
        return [
            'faltas' => 2,
            'ferias' => 10,
            'horas_extra' => 5,
            'proxima_folga' => '2024-07-01'
        ];
    }
}
?>