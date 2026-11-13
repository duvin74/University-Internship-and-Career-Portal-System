
<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* Escape output safely */
function m2_escape($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

/* Check student login */
function m2_isStudentLoggedIn() {
    return isset($_SESSION['user_id']) &&
           isset($_SESSION['user_role']) &&
           $_SESSION['user_role'] === 'Student';
}

/* Protect student pages */
function m2_requireStudent() {
    if (!m2_isStudentLoggedIn()) {
        header("Location: login.php");
        exit;
    }
}

/* Get logged-in student profile */
function m2_getStudentProfile($conn, $user_id) {

    $sql = "SELECT
                u.user_id,
                u.name,
                u.email,
                sp.student_id,
                sp.university,
                sp.course,
                sp.skills,
                sp.cv_path,
                sp.profile_photo
            FROM Users u
            LEFT JOIN student_profiles sp
                ON u.user_id = sp.user_id
            WHERE u.user_id = ?
            AND u.role = 'Student'
            LIMIT 1";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        return null;
    }

    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $profile = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    return $profile ?: null;
}

/* Get student's application count */
function m2_getApplicationCount($conn, $student_id) {

    if (!$student_id) {
        return 0;
    }

    $sql = "SELECT COUNT(*) AS total
            FROM applications
            WHERE student_id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        return 0;
    }

    mysqli_stmt_bind_param($stmt, "i", $student_id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    return (int)($row['total'] ?? 0);
}

/* Get safe uploaded filename */
function m2_safeFilePath($path) {

    if (empty($path)) {
        return null;
    }

    $path = str_replace('\\', '/', $path);

    if (strpos($path, 'student/uploads/') !== 0) {
        return null;
    }

    $filename = basename($path);

    if ($filename === '' || $filename === '.' || $filename === '..') {
        return null;
    }

    return $filename;
}

/* Get profile photo URL */
function m2_getProfilePhotoUrl($profile) {

    $filename = m2_safeFilePath($profile['profile_photo'] ?? '');

    if (!$filename) {
        return null;
    }

    return 'uploads/' . rawurlencode($filename);
}

/* Check whether student has uploaded a CV */
function m2_hasCV($profile) {

    return m2_safeFilePath($profile['cv_path'] ?? '') !== null;
}
