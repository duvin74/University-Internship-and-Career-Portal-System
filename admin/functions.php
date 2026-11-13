<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function m6_escape($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function m6_isAdminLoggedIn() {
    $role = $_SESSION['user_role'] ?? $_SESSION['role'] ?? '';

    return isset($_SESSION['user_id']) && $role === 'Admin';
}

function m6_requireAdmin() {
    if (!m6_isAdminLoggedIn()) {
        header("Location: login.php");
        exit;
    }
}

function m6_getCount($conn, $sql) {
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        return 0;
    }

    $row = mysqli_fetch_assoc($result);

    return (int)($row['total'] ?? 0);
}

function m6_getCompany($conn, $company_id) {
    $sql = "SELECT c.*, u.name, u.email
            FROM companies c
            INNER JOIN Users u ON c.user_id = u.user_id
            WHERE c.company_id = ?
            LIMIT 1";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) return null;

    mysqli_stmt_bind_param($stmt, "i", $company_id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $company = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    return $company ?: null;
}