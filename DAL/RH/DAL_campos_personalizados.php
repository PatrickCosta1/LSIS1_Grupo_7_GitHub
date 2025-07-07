<?php
require_once __DIR__ . '/../Database.php';

class DAL_CamposPersonalizados {

    private $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }
    // Lista todos os campos personalizados
    public function listarCampos() {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT id, nome, tipo FROM campos_personalizados");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Adiciona um novo campo personalizado
    public function adicionarCampo($nome, $tipo) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("INSERT INTO campos_personalizados (nome, tipo) VALUES (?, ?)");
        return $stmt->execute([$nome, $tipo]);
    }

    // Edita o nome/tipo de um campo personalizado existente
    public function editarCampo($id, $nome_novo, $tipo) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("UPDATE campos_personalizados SET nome = ?, tipo = ? WHERE id = ?");
        return $stmt->execute([$nome_novo, $tipo, $id]);
    }

    // Remove um campo personalizado e os seus valores associados
    public function removerCampo($id_ou_nome) {
        $pdo = Database::getConnection();
        $pdo->beginTransaction();
        try {
            // Debug: log o valor recebido
            // file_put_contents('debug_remover.txt', "Remover: " . print_r($id_ou_nome, true), FILE_APPEND);

            if (is_numeric($id_ou_nome)) {
                $id = (int)$id_ou_nome;
                // Verifica se existe antes de remover
                $check = $pdo->prepare("SELECT id FROM campos_personalizados WHERE id = ?");
                $check->execute([$id]);
                if (!$check->fetch()) {
                    $pdo->rollBack();
                    return false;
                }
                $pdo->prepare("DELETE FROM campos_personalizados_valor WHERE campo_id = ?")->execute([$id]);
                $stmt = $pdo->prepare("DELETE FROM campos_personalizados WHERE id = ?");
                $stmt->execute([$id]);
            } else {
                $nome = trim($id_ou_nome);
                // Busca o id correspondente ao nome
                $stmtBusca = $pdo->prepare("SELECT id FROM campos_personalizados WHERE nome = ?");
                $stmtBusca->execute([$nome]);
                $row = $stmtBusca->fetch(\PDO::FETCH_ASSOC);
                if ($row && isset($row['id'])) {
                    $id = (int)$row['id'];
                    $pdo->prepare("DELETE FROM campos_personalizados_valor WHERE campo_id = ?")->execute([$id]);
                    $stmt = $pdo->prepare("DELETE FROM campos_personalizados WHERE id = ?");
                    $stmt->execute([$id]);
                } else {
                    $pdo->rollBack();
                    return false;
                }
            }
            if (isset($stmt) && $stmt->rowCount() === 0) {
                $pdo->rollBack();
                return false;
            }
            $pdo->commit();
            return true;
        } catch (\Exception $e) {
            $pdo->rollBack();
            return false;
        }
    }

    // Busca os valores dos campos personalizados para um colaborador
    public function getValoresPorColaborador($colaborador_id) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            SELECT cp.id as campo_id, cp.nome, cp.tipo, cpv.valor
            FROM campos_personalizados cp
            LEFT JOIN campos_personalizados_valor cpv
                ON cp.id = cpv.campo_id AND cpv.colaborador_id = ?
        ");
        $stmt->execute([$colaborador_id]);
        $result = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $result[$row['campo_id']] = $row;
        }
        return $result;
    }

    public function getCamposPersonalizados() {
        $stmt = $this->pdo->query("SELECT id, nome, tipo FROM campos_personalizados ORDER BY id ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}