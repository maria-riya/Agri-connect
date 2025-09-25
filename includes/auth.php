<?php
// auth.php - Session & role check
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function isSeller() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'seller';
}

function isCustomer() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'customer';
}

// Redirect if not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit;
    }
}

// Redirect if not admin
function requireAdmin() {
    if (!isAdmin()) {
        header("Location: ../login.php");
        exit;
    }
}

// Redirect if not seller
function requireSeller() {
    if (!isSeller()) {
        header("Location: ../login.php");
        exit;
    }
}
?>
