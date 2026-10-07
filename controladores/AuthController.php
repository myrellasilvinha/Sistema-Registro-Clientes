<?php
require_once __DIR__ . '/../session_safe.php';

class AuthController {
    private $usersFile;
    
    public function __construct() {
        $this->usersFile = __DIR__ . '/../users.json';
        $this->ensureDefaultUser();
    }
    
    private function ensureDefaultUser() {
        if (!file_exists($this->usersFile)) {
            $defaultUser = [
                'id' => 1,
                'username' => 'admin',
                'password_hash' => password_hash('admin123', PASSWORD_DEFAULT),
                'role' => 'admin',
                'nome' => 'Administrador'
            ];
            file_put_contents($this->usersFile, json_encode([$defaultUser], JSON_PRETTY_PRINT));
        }
    }
    
    public function login($username, $password) {
        if (empty($username) || empty($password)) {
            throw new Exception("Usuario e senha sao obrigatorios");
        }
        $users = json_decode(file_get_contents($this->usersFile), true) ?: [];
        foreach ($users as $user) {
            if ($user['username'] === $username && password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['nome'] = $user['nome'];
                return true;
            }
        }
        throw new Exception("Credenciais invalidas");
    }
    
    public function logout() {
        session_destroy();
        header('Location: ../views/login.php');
        exit;
    }
}
