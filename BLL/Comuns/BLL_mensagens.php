<?php
require_once '../../BLL/Comuns/BLL_mensagens.php';
require_once __DIR__ . '/../../DAL/Comuns/DAL_mensagens.php';


class MensagensManager {
    public function getMensagensParaUtilizador($userId) {
        $dal = new MensagensDAL();
        return $dal->buscarMensagensPorDestinatario($userId);
    }

    public function enviarMensagem($remetenteId, $destinatarioId, $assunto, $mensagem, $anexo = null) {
        $dal = new MensagensDAL();
        return $dal->inserirMensagem($remetenteId, $destinatarioId, $assunto, $mensagem, $anexo);
    }

    public function marcarComoLida($mensagemId) {
        $dal = new MensagensDAL();
        return $dal->marcarMensagemComoLida($mensagemId);
    }
}