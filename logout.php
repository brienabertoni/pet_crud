<?php
// Inicia a sessão se ainda não tiver sido iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Destrói a sessão
session_destroy();

// Redireciona o usuário para a página de login
header('Location: login.php');
exit;
?>
