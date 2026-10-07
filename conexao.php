<?php
require_once __DIR__ . "/env_loader.php";

date_default_timezone_set("America/Sao_Paulo");

class Conexao {
    static public function criar() {
        $envPath = __DIR__ . "/.env";
        loadEnv($envPath);
        
        $host = getenv("DB_HOST") ?: "localhost";
        $dbname = getenv("DB_NAME") ?: "cadastro_clientes";
        $user = getenv("DB_USER") ?: "root";
        $password = getenv("DB_PASSWORD") ?: "";
        $charset = getenv("DB_CHARSET") ?: "utf8mb4";
        
        $conn = new PDO(
            "mysql:host=".$host.";dbname=".$dbname.";charset=".$charset,
            $user,
            $password
        );
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    }
}

