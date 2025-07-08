<?php
require_once __DIR__ . '/../../DAL/Coordenador/DAL_dashboard_coordenador.php';

class CoordenadorDashboardManager {
    private $dal;
    
    public function __construct() {
        $this->dal = new DAL_DashboardCoordenador();
    }

    public function getEquipasByCoordenador($user_id) {
        require_once __DIR__ . '/../../DAL/Database.php';
        $pdo = Database::getConnection();
        // Buscar o id do colaborador correspondente ao utilizador logado
        $stmt = $pdo->prepare("SELECT id FROM colaboradores WHERE utilizador_id = ?");
        $stmt->execute([$user_id]);
        $colab = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$colab) return [];
        $colaborador_id = $colab['id'];
        // Buscar equipas onde o responsável é este colaborador
        $stmt = $pdo->prepare("SELECT id, nome FROM equipas WHERE responsavel_id = ?");
        $stmt->execute([$colaborador_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    // Retorna aniversários dos colaboradores de uma equipa do coordenador
    public function getAniversariosPorEquipaCoordenador($equipa_id, $user_id) {
        require_once __DIR__ . '/../../DAL/Database.php';
        $pdo = Database::getConnection();
        // Buscar o id do colaborador correspondente ao utilizador logado
        $stmt = $pdo->prepare("SELECT id FROM colaboradores WHERE utilizador_id = ?");
        $stmt->execute([$user_id]);
        $colab = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$colab) return [];
        $colaborador_id = $colab['id'];
        // Garantir que a equipa pertence ao coordenador
        $stmt = $pdo->prepare("
            SELECT c.nome, c.data_nascimento
            FROM equipa_colaboradores ec
            INNER JOIN colaboradores c ON ec.colaborador_id = c.id
            INNER JOIN equipas e ON ec.equipa_id = e.id
            WHERE ec.equipa_id = ? AND e.responsavel_id = ?
            ORDER BY MONTH(c.data_nascimento), DAY(c.data_nascimento)
        ");
        $stmt->execute([$equipa_id, $colaborador_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Retorna alterações contratuais dos colaboradores das equipas do coordenador
    public function getAlteracoesContratuaisCoordenador($user_id) {
        require_once __DIR__ . '/../../DAL/Database.php';
        $pdo = Database::getConnection();
        // Buscar o id do colaborador correspondente ao utilizador logado
        $stmt = $pdo->prepare("SELECT id FROM colaboradores WHERE utilizador_id = ?");
        $stmt->execute([$user_id]);
        $colab = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$colab) return [];
        $colaborador_id = $colab['id'];
        $stmt = $pdo->prepare("
            SELECT paf.*, c.nome as colaborador_nome
            FROM pedidos_alteracao_ficha paf
            INNER JOIN colaboradores c ON paf.colaborador_id = c.id
            INNER JOIN equipa_colaboradores ec ON c.id = ec.colaborador_id
            INNER JOIN equipas e ON ec.equipa_id = e.id
            WHERE e.responsavel_id = ?
              AND paf.estado = 'aprovado'
            ORDER BY paf.data_resposta DESC
        ");
        $stmt->execute([$colaborador_id]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as &$row) {
            $row['data_alteracao'] = $row['data_resposta'];
        }
        return $result;
    }

    // Retorna vouchers dos colaboradores das equipas do coordenador
    public function getVouchersCoordenador($user_id) {
        require_once __DIR__ . '/../../DAL/Database.php';
        $pdo = Database::getConnection();
        // Buscar o id do colaborador correspondente ao utilizador logado
        $stmt = $pdo->prepare("SELECT id FROM colaboradores WHERE utilizador_id = ?");
        $stmt->execute([$user_id]);
        $colab = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$colab) return [];
        $colaborador_id = $colab['id'];
        $stmt = $pdo->prepare("
            SELECT c.nome as colaborador_nome, v.tipo, v.data_emissao
            FROM vouchers v
            INNER JOIN colaboradores c ON v.colaborador_id = c.id
            INNER JOIN equipa_colaboradores ec ON c.id = ec.colaborador_id
            INNER JOIN equipas e ON ec.equipa_id = e.id
            WHERE e.responsavel_id = ?
            ORDER BY v.data_emissao DESC
        ");
        $stmt->execute([$colaborador_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>