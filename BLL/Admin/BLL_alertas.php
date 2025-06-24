<?php
require_once __DIR__ . '/../../DAL/Admin/DAL_alertas.php';

class AdminAlertasManager {
    private $dal;
    public function __construct() {
        $this->dal = new AdminAlertasDAL();
    }
    public function getAllAlertas() {
        return $this->dal->getAllAlertas();
    }
    public function addAlerta($tipo, $descricao, $periodicidade_meses, $ativo, $perfis = []) {
        $alerta_id = $this->dal->addAlerta($tipo, $descricao, $periodicidade_meses, $ativo);
        if ($alerta_id && is_array($perfis)) {
            $this->dal->updateAlertasPerfis($alerta_id, $perfis);
        }
        return $alerta_id;
    }
    public function updateAlerta($id, $tipo, $descricao, $periodicidade_meses, $ativo) {
        return $this->dal->updateAlerta($id, $tipo, $descricao, $periodicidade_meses, $ativo);
    }
    public function removeAlerta($id) {
        return $this->dal->removeAlerta($id);
    }
    public function addAlertaPerfil($alerta_id, $perfil_id) {
        return $this->dal->addAlertaPerfil($alerta_id, $perfil_id);
    }
    public function getAlertasParaUtilizador($perfil_id) {
        return $this->dal->getAlertasParaUtilizador($perfil_id);
    }
    public function marcarComoLido($alerta_id, $user_id) {
        return $this->dal->marcarComoLido($alerta_id, $user_id);
    }
    public function isAlertaLido($alerta_id, $utilizador_id) {
        return $this->dal->isAlertaLido($alerta_id, $utilizador_id);
    }
    public function getPerfisByAlerta($alerta_id) {
        return $this->dal->getPerfisByAlerta($alerta_id);
    }
    public function updateAlertasPerfis($alerta_id, $perfis_ids) {
        return $this->dal->updateAlertasPerfis($alerta_id, $perfis_ids);
    }
}
?>
