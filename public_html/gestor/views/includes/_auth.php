<?php
// =====================================================
// Filhos da Fé — views/includes/_auth.php
// Verificação simples de sessão admin
// =====================================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['admin_logado'])) {
    header('Location: login.php');
    exit;
}
