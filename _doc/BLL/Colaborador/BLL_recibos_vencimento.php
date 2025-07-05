<?php
require_once __DIR__ . '/../../DAL/Colaborador/DAL_recibos_vencimento.php';

class RecibosVencimentoManager {
    public function getRecibosPorColaborador($colaboradorId) {
        $dal = new RecibosVencimentoDAL();
        return $dal->listarRecibosPorColaborador($colaboradorId);
    }
}