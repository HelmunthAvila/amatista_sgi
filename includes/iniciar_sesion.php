<?php
// Inicia la sesión con cookies endurecidas (httponly + samesite) y token CSRF de sesión (AM-007 / AM-008).
// Debe incluirse ANTES de cualquier salida HTML.
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'httponly' => true,          // la cookie no es legible por JavaScript (mitiga robo vía XSS)
        'samesite' => 'Lax',         // no se envía en peticiones cross-site (mitiga CSRF)
        'secure'   => !empty($_SERVER['HTTPS']) // solo por HTTPS cuando exista
    ]);
    session_start();
}

// Token CSRF único por sesión (AM-008)
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function csrf_token() {
    return $_SESSION['csrf_token'] ?? '';
}

function csrf_verificar() {
    $t = $_POST['csrf_token'] ?? '';
    return !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $t);
}
?>
