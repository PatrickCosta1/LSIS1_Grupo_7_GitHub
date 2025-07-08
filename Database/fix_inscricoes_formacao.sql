-- Execute este script na sua base de dados MySQL

USE lsis1_grupo7;

-- Criar tabela de inscrições em formações
CREATE TABLE IF NOT EXISTS inscricoes_formacao (
    id INT AUTO_INCREMENT PRIMARY KEY,
    colaborador_id INT NOT NULL,
    formacao_id INT NOT NULL,
    data_inscricao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('inscrito', 'cancelado', 'concluido') DEFAULT 'inscrito',
    FOREIGN KEY (colaborador_id) REFERENCES colaboradores(id) ON DELETE CASCADE,
    FOREIGN KEY (formacao_id) REFERENCES formacoes(id) ON DELETE CASCADE,
    UNIQUE KEY unique_inscricao (colaborador_id, formacao_id)
);

-- Verificar se a tabela foi criada
SHOW TABLES LIKE 'inscricoes_formacao';
