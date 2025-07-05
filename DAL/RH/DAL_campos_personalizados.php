<?php
require_once __DIR__ . '/../Database.php';

class DAL_CamposPersonalizados {
    // Lista os campos da tabela 'colaboradores' (exceto id e chaves técnicas)
    public function listarCampos() {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SHOW FULL COLUMNS FROM colaboradores");
        $campos = [];
        while ($row = $stmt->fetch()) {
            if (!in_array($row['Field'], ['id', 'utilizador_id'])) {
                $campos[] = [
                    'nome' => $row['Field'],
                    'tipo' => $row['Type'],
                    'comentario' => $row['Comment']
                ];
            }
        }
        return $campos;
    }

    // Adiciona um novo campo à tabela 'colaboradores'
    public function adicionarCampo($nome, $tipo) {
        $pdo = Database::getConnection();
        $tipoSQL = $this->mapTipo($tipo);
        $sql = "ALTER TABLE colaboradores ADD `$nome` $tipoSQL NULL";
        return $pdo->exec($sql) !== false;
    }

    // Edita o nome de um campo existente
    public function editarCampo($nome_antigo, $nome_novo, $tipo) {
        $pdo = Database::getConnection();
        $tipoSQL = $this->mapTipo($tipo);
        $sql = "ALTER TABLE colaboradores CHANGE `$nome_antigo` `$nome_novo` $tipoSQL NULL";
        return $pdo->exec($sql) !== false;
    }

    // Remove um campo da tabela 'colaboradores'
    public function removerCampo($nome) {
        $pdo = Database::getConnection();
        $sql = "ALTER TABLE colaboradores DROP COLUMN `$nome`";
        return $pdo->exec($sql) !== false;
    }

    // Mapeia tipos lógicos para tipos SQL
    private function mapTipo($tipo) {
        switch ($tipo) {
            case 'numero': return 'INT';
            case 'data': return 'DATE';
            case 'email': return 'VARCHAR(120)';
            case 'texto':
            default: return 'VARCHAR(255)';
        }
    }
}
