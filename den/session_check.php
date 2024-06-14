<?php
session_start();

// Oturum süresi kontrolü
function check_session_timeout($redirect_url = 'login.php') {
    if (isset($_SESSION['login_time']) && isset($_SESSION['session_timeout'])) {
        $elapsed_time = time() - $_SESSION['login_time'];
        if ($elapsed_time >= $_SESSION['session_timeout']) {
            session_unset();
            session_destroy();
            header("Location: $redirect_url?timeout=true");
            exit;
        }
    } else {
        session_unset();
        session_destroy();
        header("Location: $redirect_url?timeout=true");
        exit;
    }
}
?>
