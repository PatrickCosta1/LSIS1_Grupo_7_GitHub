<?php
// BLL/Comuns/BLL_login.php

/**
 * Camada de Lógica de Negócio para Autenticação
 * Responsável por autenticação, gestão de sessão e validação de permissões
 * 
 * @author LSIS1 Group
 * @version 1.0
 */
class Authenticator
{
    private $dal;

    public function __construct()
    {
        require_once __DIR__ . '/../../DAL/Comuns/DAL_login.php';
        $this->dal = new DAL_Login();
    }

    /**
     * Autenticar login do utilizador
     * 
     * @param string $username Nome de utilizador
     * @param string $password Palavra-passe (texto simples)
     * @return array|false Dados do utilizador se sucesso, false se falhar
     */
    public function login($username, $password)
    {
        $user = $this->dal->getUserByUsername($username);
        if ($user && $user['ativo'] && $password === $user['password']) {
            $colabName = $this->dal->getColaboradorName($user['id']);
            if (!$colabName) {
                $colabName = $user['username'];
            }
            $profile = strtolower($this->dal->getProfileName($user['perfil_id']));
            return [
                'id' => $user['id'],
                'username' => $user['username'],
                'profile' => $profile,
                'name' => $colabName
            ];
        }
        return false;
    }

    /**
     * Logout do utilizador e destruição da sessão
     * 
     * @param int $userId ID do utilizador
     * @return bool Sucesso
     */
    public function logout($userId)
    {
        session_unset();
        session_destroy();
        return true;
    }

    /**
     * Alterar palavra-passe do utilizador
     * 
     * @param int $userId ID do utilizador
     * @param string $oldPassword Palavra-passe atual
     * @param string $newPassword Nova palavra-passe
     * @return bool
     */
    public function changePassword($userId, $oldPassword, $newPassword)
    {
        $user = $this->dal->getUserById($userId);
        if (!$user) return false;
        if ($user['password'] !== $oldPassword) return false;
        if (!$this->isPasswordStrong($newPassword)) return false;
        return $this->dal->updatePassword($userId, $newPassword);
    }

    /**
     * Verificar se a palavra-passe cumpre requisitos de segurança
     * 
     * @param string $password Palavra-passe
     * @return bool
     */
    private function isPasswordStrong($password)
    {
        // Pelo menos 6 caracteres
        return strlen($password) >= 6;
    }

    /**
     * Obter permissões do perfil de utilizador a partir da base de dados
     * 
     * @param int $perfil_id
     * @return array
     */
    public function getProfilePermissions($perfil_id)
    {
        $pdo = \Database::getConnection();
        $stmt = $pdo->prepare("SELECT permissao, valor FROM permissoes WHERE perfil_id = ?");
        $stmt->execute([$perfil_id]);
        $perms = [];
        while ($row = $stmt->fetch()) {
            $perms[$row['permissao']] = (bool)$row['valor'];
        }
        return $perms;
    }
}
?>