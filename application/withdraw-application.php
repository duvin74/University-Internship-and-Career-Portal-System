<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';

m5_requireStudent();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: my-applications.php");
    exit;
}

$application_id = filter_input(INPUT_POST, 'application_id', FILTER_VALIDATE_INT);

$user_id = (int)$_SESSION['user_id'];
$student_id = m5_getStudentId($conn, $user_id);

if (!$application_id || !$student_id) {
    header("Location: my-applications.php");
    exit;
}

$sql = "UPDATE applications
        SET status = 'Withdrawn'
        WHERE application_id = ?
        AND student_id = ?
        AND status = 'Pending'";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Unable to process your request.");
}

mysqli_stmt_bind_param($stmt, "ii", $application_id, $student_id);
mysqli_stmt_execute($stmt);

$updated = mysqli_stmt_affected_rows($stmt);

mysqli_stmt_close($stmt);

if ($updated > 0) {
    header("Location: my-applications.php?message=withdrawn");
} else {
    header("Location: application-details.php?id=" . $application_id);
}

exit;