<?php
// BLL/Comuns/BLL_login.php

// Carregar dependências do Composer para Google2FA e QRCode
require_once __DIR__ . '/../../vendor/autoload.php';
use PragmaRX\Google2FA\Google2FA;

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
     * @param string|null $otp Código 2FA (opcional)
     * @return array|false|mixed
     */
    public function login($username, $password, $otp = null)
    {
        $user = $this->dal->getUserByUsername($username);
        if ($user && $user['ativo']) {
            // Verificação de password segura (hash)
            if (!$this->verifyPassword($password, $user['password'])) {
                return false;
            }

            // 2FA obrigatório para todos
            if (empty($user['google2fa_secret'])) {
                // Gerar secret e pedir configuração
                $google2fa = new Google2FA();
                $secret = $google2fa->generateSecretKey();
                $this->dal->setGoogle2FASecret($user['id'], $secret);
                $user['google2fa_secret'] = $secret;
                return [
                    '2fa_setup' => true,
                    'secret' => $secret,
                    'user_id' => $user['id'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                ];
            } else {
                // Se já tem secret, validar o OTP
                if ($otp === null) {
                    // Solicitar OTP
                    return ['2fa_required' => true, 'user_id' => $user['id']];
                }
                $google2fa = new Google2FA();
                if (!$google2fa->verifyKey($user['google2fa_secret'], $otp)) {
                    return false;
                }
            }
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
     * Verifica a password usando hash seguro (compatível com password_hash/password_verify)
     */
    private function verifyPassword($password, $hash)
    {
        // Compatível com password_hash, mas aceita texto simples para retrocompatibilidade
        if (strlen($hash) === 60 && preg_match('/^\$2y\$/', $hash)) {
            return password_verify($password, $hash);
        }
        // Fallback para sistemas antigos (NÃO recomendado em produção)
        return $password === $hash;
    }

    /**
     * Logout do utilizador e destruição da sessão
     */
    public function logout($userId)
    {
        session_unset();
        session_destroy();
        return true;
    }

    /**
     * Alterar palavra-passe do utilizador
     */
    public function changePassword($userId, $oldPassword, $newPassword)
    {
        $user = $this->dal->getUserById($userId);
        if (!$user) return false;
        if (!$this->verifyPassword($oldPassword, $user['password'])) return false;
        if (!$this->isPasswordStrong($newPassword)) return false;
        $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
        return $this->dal->updatePassword($userId, $newHash);
    }

    /**
     * Verificar se a palavra-passe cumpre requisitos de segurança
     */
    private function isPasswordStrong($password)
    {
        // Pelo menos 6 caracteres, pelo menos 1 número e 1 letra
        return strlen($password) >= 6 && preg_match('/[A-Za-z]/', $password) && preg_match('/\d/', $password);
    }

    /**
     * Obter permissões do perfil de utilizador a partir da base de dados
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