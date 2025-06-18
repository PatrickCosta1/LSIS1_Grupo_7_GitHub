<?php
require_once __DIR__ . '/../../DAL/Convidado/DAL_onboarding_convidado.php';

class OnboardingConvidadoManager {
    private $dal;
    public function __construct() {
        $this->dal = new DAL_OnboardingConvidado();
    }
    public function getConvidadoByUserId($userId) {
        return $this->dal->getConvidadoByUserId($userId);
    }
}
?>
