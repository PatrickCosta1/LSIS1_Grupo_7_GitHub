<?php
require_once __DIR__ . '/../Database.php';

class DAL_FichaColaborador {
    private $pdo;
    
    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    public function getColaboradorByUserId($userId) {
        $stmt = $this->pdo->prepare("SELECT * FROM colaboradores WHERE utilizador_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }

    public function getColaboradorById($colabId) {
        $stmt = $this->pdo->prepare("SELECT * FROM colaboradores WHERE id = ?");
        $stmt->execute([$colabId]);
        return $stmt->fetch();
    }

    public function updateColaboradorByUserId($userId, $dados) {
        $set = [];
        $params = [];
        // Filtrar apenas campos válidos (existentes na tabela)
        $validFields = [
            'nome','apelido','nome_abreviado','num_mecanografico','data_nascimento','email','telemovel','sexo','habilitacoes','curso','matricula_viatura','morada','localidade','codigo_postal','cc','nif','niss','iban','situacao_irs','dependentes','irs_jovem','primeiro_ano_descontos','cartao_continente','voucher_nos','nome_contacto_emergencia','grau_relacionamento','contacto_emergencia','cargo','data_inicio_contrato','data_fim_contrato','remuneracao','tipo_contrato','regime_horario','estado_civil','morada_fiscal'
        ];
        foreach ($dados as $campo => $valor) {
            if (in_array($campo, $validFields)) {
                $set[] = "$campo = ?";
                $params[] = $valor;
            }
        }
        if (empty($set)) return false; // Não há campos válidos para atualizar
        $params[] = $userId;
        $sql = "UPDATE colaboradores SET " . implode(', ', $set) . " WHERE utilizador_id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    // Novo método para atualizar pelo id do colaborador (usado por RH/Admin)
    public function updateColaboradorById($colabId, $dados) {
        $set = [];
        $params = [];
        foreach ($dados as $campo => $valor) {
            $set[] = "$campo = ?";
            $params[] = $valor;
        }
        $params[] = $colabId;
        $sql = "UPDATE colaboradores SET " . implode(', ', $set) . " WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public function criarPedidoAlteracao($colaboradorId, $campo, $valorNovo, $valorAntigo) {
        $stmt = $this->pdo->prepare("INSERT INTO pedidos_alteracao_ficha (colaborador_id, campo, valor_novo, valor_antigo, estado, data_pedido) VALUES (?, ?, ?, ?, 'pendente', NOW())");
        return $stmt->execute([$colaboradorId, $campo, $valorNovo, $valorAntigo]);
    }

    public function aplicarAlteracao($colaboradorId, $campo, $valorNovo) {
        $stmt = $this->pdo->prepare("UPDATE colaboradores SET $campo = ? WHERE id = ?");
        return $stmt->execute([$valorNovo, $colaboradorId]);
    }

    public function listarPedidosPendentes() {
        $stmt = $this->pdo->query("SELECT p.*, c.nome as colaborador_nome FROM pedidos_alteracao_ficha p INNER JOIN colaboradores c ON p.colaborador_id = c.id WHERE p.estado = 'pendente' ORDER BY p.data_pedido DESC");
        return $stmt->fetchAll();
    }

    public function atualizarEstadoPedido($pedidoId, $estado) {
        $stmt = $this->pdo->prepare("UPDATE pedidos_alteracao_ficha SET estado = ?, data_resposta = NOW() WHERE id = ?");
        return $stmt->execute([$estado, $pedidoId]);
    }

    public function getPedidoById($pedidoId) {
        $stmt = $this->pdo->prepare("SELECT * FROM pedidos_alteracao_ficha WHERE id = ?");
        $stmt->execute([$pedidoId]);
        return $stmt->fetch();
    }

    // --- PEDIDOS DE FÉRIAS ---
    public function criarPedidoFerias($colaboradorId, $dataInicio, $dataFim) {
        $stmt = $this->pdo->prepare("INSERT INTO pedidos_ferias (colaborador_id, data_inicio, data_fim, data_pedido, estado) VALUES (?, ?, ?, NOW(), 'pendente')");
        return $stmt->execute([$colaboradorId, $dataInicio, $dataFim]);
    }

    public function listarPedidosFeriasPendentes() {
        $stmt = $this->pdo->query("SELECT pf.*, c.nome as colaborador_nome FROM pedidos_ferias pf INNER JOIN colaboradores c ON pf.colaborador_id = c.id WHERE pf.estado = 'pendente' ORDER BY pf.data_pedido DESC");
        return $stmt->fetchAll();
    }

    public function atualizarEstadoPedidoFerias($pedidoId, $estado) {
        $stmt = $this->pdo->prepare("UPDATE pedidos_ferias SET estado = ? WHERE id = ?");
        return $stmt->execute([$estado, $pedidoId]);
    }

    public function getPedidoFeriasById($pedidoId) {
        $stmt = $this->pdo->prepare("SELECT * FROM pedidos_ferias WHERE id = ?");
        $stmt->execute([$pedidoId]);
        return $stmt->fetch();
    }

    public function getPedidosFeriasPorColaborador($colaboradorId) {
        try {
            // Debug: verificar o colaborador_id que está sendo usado
            error_log("Buscando pedidos de férias para colaborador_id: " . $colaboradorId);
            
            $stmt = $this->pdo->prepare("
                SELECT 
                    id,
                    colaborador_id,
                    data_inicio,
                    data_fim,
                    data_pedido,
                    estado,
                    observacoes
                FROM pedidos_ferias
                WHERE colaborador_id = ?
                ORDER BY data_pedido DESC
            ");
            $stmt->execute([$colaboradorId]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Debug: log do resultado
            error_log("Pedidos encontrados para colaborador_id " . $colaboradorId . ": " . count($result));
            if (!empty($result)) {
                error_log("Primeiro pedido: " . print_r($result[0], true));
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log("Erro ao buscar pedidos de férias: " . $e->getMessage());
            return [];
        }
    }

    public function criarPedidoComprovativo($colaboradorId, $tipoComprovativo, $comprovantivoAntigo, $comprovantivoNovo) {
        $stmt = $this->pdo->prepare("
            INSERT INTO pedidos_comprovativo 
            (colaborador_id, tipo_comprovativo, comprovativo_antigo, comprovativo_novo, status, data_pedido) 
            VALUES (?, ?, ?, ?, 'pendente', NOW())
        ");
        return $stmt->execute([$colaboradorId, $tipoComprovativo, $comprovantivoAntigo, $comprovantivoNovo]);
    }

    public function listarPedidosComprovantivosPendentes() {
        $stmt = $this->pdo->prepare("
            SELECT pc.*, c.nome as colaborador_nome 
            FROM pedidos_comprovativo pc
            INNER JOIN colaboradores c ON pc.colaborador_id = c.id
            WHERE pc.status = 'pendente'
            ORDER BY pc.data_pedido DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function aprovarPedidoComprovativo($pedidoId) {
        try {
            $this->pdo->beginTransaction();
            
            // Buscar dados do pedido
            $stmt = $this->pdo->prepare("SELECT * FROM pedidos_comprovativo WHERE id = ?");
            $stmt->execute([$pedidoId]);
            $pedido = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$pedido) {
                $this->pdo->rollBack();
                return false;
            }
            
            // Atualizar colaborador com novo comprovativo
            $campo = $pedido['tipo_comprovativo'];
            $stmt = $this->pdo->prepare("UPDATE colaboradores SET {$campo} = ? WHERE id = ?");
            $stmt->execute([$pedido['comprovativo_novo'], $pedido['colaborador_id']]);
            
            // Marcar pedido como aprovado
            $stmt = $this->pdo->prepare("UPDATE pedidos_comprovativo SET status = 'aprovado', data_resposta = NOW() WHERE id = ?");
            $stmt->execute([$pedidoId]);
            
            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Erro ao aprovar pedido de comprovativo: " . $e->getMessage());
            return false;
        }
    }

    public function recusarPedidoComprovativo($pedidoId) {
        $stmt = $this->pdo->prepare("UPDATE pedidos_comprovativo SET status = 'recusado', data_resposta = NOW() WHERE id = ?");
        return $stmt->execute([$pedidoId]);
    }

    public function getPedidoComprovantivoById($pedidoId) {
        $stmt = $this->pdo->prepare("
            SELECT pc.*, c.nome as colaborador_nome 
            FROM pedidos_comprovativo pc
            INNER JOIN colaboradores c ON pc.colaborador_id = c.id
            WHERE pc.id = ?
        ");
        $stmt->execute([$pedidoId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertColaboradorFromImport($fields, $values) {
        if (empty($fields) || empty($values)) return false;
        $sql = "INSERT INTO colaboradores (" . implode(',', $fields) . ") VALUES (" . implode(',', array_fill(0, count($fields), '?')) . ")";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($values);
    }

    // --- CAMPOS PERSONALIZADOS ---

    // Buscar valores dos campos personalizados para um colaborador
    public function getCamposPersonalizadosValores($colaborador_id) {
        $stmt = $this->pdo->prepare("
            SELECT cp.id as campo_id, cp.nome, cp.tipo, cpv.valor
            FROM campos_personalizados cp
            LEFT JOIN campos_personalizados_valores cpv
                ON cp.id = cpv.campo_id AND cpv.colaborador_id = ?
        ");
        $stmt->execute([$colaborador_id]);
        $result = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            error_log("Campo personalizado: " . print_r($row, true)); // <-- Adiciona isto para debug
            $result[$row['campo_id']] = $row;
        }
        return $result;
    }

    // Salvar ou atualizar valores dos campos personalizados para um colaborador
    public function salvarCamposPersonalizadosValores($colaborador_id, $valores) {
        foreach ($valores as $campo_id => $valor) {
            // Verifica se já existe valor
            $stmt = $this->pdo->prepare("SELECT id FROM campos_personalizados_valores WHERE colaborador_id = ? AND campo_id = ?");
            $stmt->execute([$colaborador_id, $campo_id]);
            if ($stmt->fetchColumn()) {
                $upd = $this->pdo->prepare("UPDATE campos_personalizados_valores SET valor = ? WHERE colaborador_id = ? AND campo_id = ?");
                $upd->execute([$valor, $colaborador_id, $campo_id]);
            } else {
                $ins = $this->pdo->prepare("INSERT INTO campos_personalizados_valores (colaborador_id, campo_id, valor) VALUES (?, ?, ?)");
                $ins->execute([$colaborador_id, $campo_id, $valor]);
            }
        }
        return true;
    }

    // Método para registar logs de alterações contratuais
    public function registarLogAlteracaoContratual($colaboradorId, $campo, $valorAntigo, $valorNovo, $alteradoPorUserId) {
        try {
            // Buscar nome do utilizador que fez a alteração
            $stmt = $this->pdo->prepare("SELECT nome FROM colaboradores WHERE utilizador_id = ?");
            $stmt->execute([$alteradoPorUserId]);
            $nomeAlterador = $stmt->fetchColumn();
            
            // Inserir log
            $stmt = $this->pdo->prepare("
                INSERT INTO logs_alteracoes_contratuais 
                (colaborador_id, campo, valor_antigo, valor_novo, data_alteracao, alterado_por_user_id, alterado_por_nome) 
                VALUES (?, ?, ?, ?, NOW(), ?, ?)
            ");
            return $stmt->execute([
                $colaboradorId, 
                $campo, 
                $valorAntigo, 
                $valorNovo, 
                $alteradoPorUserId, 
                $nomeAlterador
            ]);
        } catch (PDOException $e) {
            error_log("Erro ao registar log de alteração contratual: " . $e->getMessage());
            return false;
        }
    }
}