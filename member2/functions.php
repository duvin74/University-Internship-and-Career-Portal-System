
<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function m2_escape($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function m2_isStudentLoggedIn() {
    return isset($_SESSION['user_id']) &&
           isset($_SESSION['user_role']) &&
           $_SESSION['user_role'] === 'Student';
}

function m2_requireStudent() {
    if (!m2_isStudentLoggedIn()) {
        header("Location: login.php");
        exit;
    }
}

function m2_getStudentProfile($conn, $user_id) {

    $sql = "SELECT
                u.user_id,
                u.name,
                u.email,
                sp.student_id,
                sp.university,
                sp.course,
                sp.skills,
                sp.cv_path
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
