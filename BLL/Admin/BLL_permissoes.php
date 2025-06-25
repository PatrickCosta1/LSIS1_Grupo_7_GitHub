<?php
require_once __DIR__ . '/../../DAL/Admin/DAL_permissoes.php';

class AdminPermissoesManager {
    private $dal;
    private $db;

    public function __construct() {
        $this->dal = new DAL_PermissoesAdmin();
        // Ajuste a ligação conforme a sua configuração
        $this->db = new PDO('mysql:host=localhost;dbname=lsis1_grupo7', 'root', '');
    }
    public function getAllPermissoes() {
        return $this->dal->getAllPermissoes();
    }
    public function updatePermissao($id, $valor) {
        return $this->dal->updatePermissao($id, $valor);
    }

    /**
     * Devolve as permissões de um perfil como array associativo: ['nome_permissao' => 1 ou 0, ...]
     */
    public function getPermissoesByPerfil($perfil_id) {
        $stmt = $this->db->prepare("SELECT permissao, valor FROM permissoes WHERE perfil_id = ?");
        $stmt->execute([$perfil_id]);
        $permissoes = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $permissoes[$row['permissao']] = (int)$row['valor'];
        }
        return $permissoes;
    }
}
?>
