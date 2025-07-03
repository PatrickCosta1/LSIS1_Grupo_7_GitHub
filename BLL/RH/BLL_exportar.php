<?php
require_once __DIR__ . '/../../DAL/RH/DAL_exportar.php';

class RHExportarManager {
    private $dal;
    
    public function __construct() {
        $this->dal = new DAL_ExportarRH();
    }
    
    public function getAllColaboradores() {
        return $this->dal->getAllColaboradores();
    }
    
    public function getColaboradoresPorEquipa($equipaId) {
        error_log("BLL - Iniciando busca para equipa ID: $equipaId");
        
        // PASSO 1: Buscar todos os colaborador_id onde equipa_id = $equipaId
        $colaboradorIds = $this->dal->getColaboradorIdsByEquipaId($equipaId);
        error_log("BLL - Colaborador IDs encontrados: " . implode(', ', $colaboradorIds));
        
        if (empty($colaboradorIds)) {
            error_log("BLL - Nenhum colaborador_id encontrado para equipa $equipaId");
            return [];
        }
        
        // PASSO 2: Buscar todos os dados da tabela colaboradores onde colaborador.id IN ($colaboradorIds)
        $colaboradores = $this->dal->getColaboradoresByIds($colaboradorIds);
        error_log("BLL - Total de colaboradores retornados: " . count($colaboradores));
        
        return $colaboradores;
    }
    
    public function getColaboradoresPorPerfil($perfilId) {
        return $this->dal->getColaboradoresPorPerfil($perfilId);
    }
    
    public function getAllEquipas() {
        return $this->dal->getAllEquipas();
    }
    
    public function getAllPerfis() {
        return $this->dal->getAllPerfis();
    }
    
    private function formatarData($data) {
        if (!$data || $data === '0000-00-00' || $data === '') {
            return '';
        }
        return date('d/m/Y', strtotime($data));
    }
    
    public function formatarColaboradorParaCSV($colaborador) {
        return [
            $colaborador['num_mecanografico'] ?? '',
            $colaborador['nome'] ?? '',
            $colaborador['apelido'] ?? '',
            $colaborador['nome_abreviado'] ?? '',
            $colaborador['email'] ?? '',
            $colaborador['telemovel'] ?? '',
            $this->formatarData($colaborador['data_nascimento'] ?? ''),
            $colaborador['sexo'] ?? '',
            $colaborador['estado_civil'] ?? '',
            $colaborador['nacionalidade'] ?? '',
            $colaborador['habilitacoes'] ?? '',
            $colaborador['curso'] ?? '',
            $colaborador['matricula_viatura'] ?? '',
            $colaborador['morada'] ?? '',
            $colaborador['localidade'] ?? '',
            $colaborador['codigo_postal'] ?? '',
            $colaborador['morada_fiscal'] ?? '',
            $colaborador['cc'] ?? '',
            $colaborador['nif'] ?? '',
            $colaborador['niss'] ?? '',
            $colaborador['iban'] ?? '',
            $colaborador['situacao_irs'] ?? '',
            $colaborador['dependentes'] ?? '',
            $colaborador['irs_jovem'] ?? '',
            $colaborador['primeiro_ano_descontos'] ?? '',
            $colaborador['cartao_continente'] ?? '',
            $colaborador['voucher_nos'] ?? '',
            $colaborador['nome_contacto_emergencia'] ?? '',
            $colaborador['grau_relacionamento'] ?? '',
            $colaborador['contacto_emergencia'] ?? '',
            $colaborador['cargo'] ?? '',
            $colaborador['nivel_hierarquico'] ?? '',
            $this->formatarData($colaborador['data_inicio_contrato'] ?? ''),
            $this->formatarData($colaborador['data_fim_contrato'] ?? ''),
            $colaborador['remuneracao'] ?? '',
            $colaborador['tipo_contrato'] ?? '',
            $colaborador['regime_horario'] ?? '',
            isset($colaborador['ativo']) ? ($colaborador['ativo'] ? 'Ativo' : 'Inativo') : 'Ativo'
        ];
    }
    
    public function getCabecalhosCSV() {
        return [
            'Nº Mecanográfico',
            'Nome',
            'Apelido', 
            'Nome Abreviado',
            'Email',
            'Telemóvel',
            'Data Nascimento',
            'Sexo',
            'Estado Civil',
            'Nacionalidade',
            'Habilitações',
            'Curso',
            'Matrícula Viatura',
            'Morada',
            'Localidade',
            'Código Postal',
            'Morada Fiscal',
            'CC',
            'NIF',
            'NISS',
            'IBAN',
            'Situação IRS',
            'Dependentes',
            'IRS Jovem',
            'Primeiro Ano Descontos',
            'Cartão Continente',
            'Voucher NOS',
            'Nome Contacto Emergência',
            'Grau Relacionamento',
            'Contacto Emergência',
            'Cargo',
            'Nível Hierárquico',
            'Data Início Contrato',
            'Data Fim Contrato',
            'Remuneração',
            'Tipo Contrato',
            'Regime Horário',
            'Estado'
        ];
    }
}
?>