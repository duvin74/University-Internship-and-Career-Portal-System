<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';

m6_requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: companies.php");
    exit;
}

$company_id = filter_input(INPUT_POST, 'company_id', FILTER_VALIDATE_INT);
$action = $_POST['action'] ?? '';

$allowed_statuses = ['Verified', 'Rejected'];

if (!$company_id || !in_array($action, $allowed_statuses, true)) {
    header("Location: companies.php");
    exit;
}

$sql = "UPDATE companies
        SET verification_status = ?
        WHERE company_id = ?";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Unable to process company verification.");
}

mysqli_stmt_bind_param($stmt, "si", $action, $company_id);
mysqli_stmt_execute($stmt);

$updated = mysqli_stmt_affected_rows($stmt);

mysqli_stmt_close($stmt);

if ($updated >= 0) {
    header("Location: company-details.php?id=" . $company_id);
    exit;
}

die("Company verification failed.");