<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';

m5_requireStudent();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

$internship_id = filter_input(INPUT_POST, 'internship_id', FILTER_VALIDATE_INT);
$confirm = $_POST['confirm'] ?? '';

if (!$internship_id || $confirm !== 'yes') {
    header("Location: index.php");
    exit;
}

$user_id = (int)$_SESSION['user_id'];
$student_id = m5_getStudentId($conn, $user_id);

if (!$student_id) {
    die("Student profile not found. Please complete your student profile.");
}

$internship = m5_getInternship($conn, $internship_id);

if (!$internship) {
    die("This internship is no longer available.");
}

// Prevent duplicate applications.
$check_sql = "SELECT application_id FROM applications
              WHERE internship_id = ? AND student_id = ?
              LIMIT 1";

$check_stmt = mysqli_prepare($conn, $check_sql);

if (!$check_stmt) {
    die("Unable to check your application.");
}

mysqli_stmt_bind_param($check_stmt, "ii", $internship_id, $student_id);
mysqli_stmt_execute($check_stmt);

$check_result = mysqli_stmt_get_result($check_stmt);
$existing = mysqli_fetch_assoc($check_result);

mysqli_stmt_close($check_stmt);

if ($existing) {
    header("Location: my-applications.php?message=already_applied");
    exit;
}

$sql = "INSERT INTO applications
        (internship_id, student_id, application_date, status)
        VALUES (?, ?, NOW(), 'Pending')";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Unable to prepare application.");
}

mysqli_stmt_bind_param($stmt, "ii", $internship_id, $student_id);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    header("Location: my-applications.php?message=success");
    exit;
}

mysqli_stmt_close($stmt);
die("Application submission failed. Please try again.");