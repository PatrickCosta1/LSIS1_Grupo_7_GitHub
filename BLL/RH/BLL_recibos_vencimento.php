<?php
require_once __DIR__ . '/../../DAL/RH/DAL_recibos_vencimento.php';

class RHRecibosManager {
    private $dal;
    public function __construct() {
        $this->dal = new DAL_RecibosRH();
    }

    public function getAllColaboradores() {
        // Apenas colaboradores e coordenadores ativos
        return $this->dal->getAllColaboradores();
    }

    public function getColaboradorById($colaborador_id) {
        return $this->dal->getColaboradorById($colaborador_id);
    }

    public function submeterRecibo($colaborador_id, $mes, $ano, $nome_ficheiro, $caminho_ficheiro) {
        return $this->dal->submeterRecibo($colaborador_id, $mes, $ano, $nome_ficheiro, $caminho_ficheiro);
    }

    public function getRecibosPorColaborador($colaborador_id) {
        return $this->dal->getRecibosPorColaborador($colaborador_id);
    }
}
?>