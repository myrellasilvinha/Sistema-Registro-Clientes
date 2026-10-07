<?php
function loadEnv($path) {
    if (!file_exists($path)) {
        return;
    }
    $data = @parse_ini_file($path, false, INI_SCANNER_RAW);
    if (!$data) {
        $data = array();
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $t = trim($line);
            if (strlen($t) > 0 && $t[0] === "#") { continue; }
            if (strpos($line, "=") !== false) {
                list($k, $v) = explode("=", $line, 2);
                $k = trim($k); $v = trim($v);
                $data[$k] = $v;
            }
        }
    }
    foreach ($data as $k => $v) {
        if (!getenv($k) && !array_key_exists($k, $_ENV)) {
            putenv($k . "=" . $v);
            $_ENV[$k] = $v;
            $_SERVER[$k] = $v;
        }
    }
}
