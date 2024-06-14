<?php
// csrf_token.php

// CSRF token oluşturma
function generateCSRFToken() {
    $token = bin2hex(random_bytes(32)); // Rastgele bir token oluştur
    $_SESSION['csrf_token'] = $token; // Session içinde token'ı sakla
    return $token;
}

// CSRF token doğrulama
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && $_SESSION['csrf_token'] === $token;
}
?>
