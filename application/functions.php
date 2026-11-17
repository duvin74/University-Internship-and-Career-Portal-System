
<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function m5_escape($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function m5_isStudentLoggedIn() {
    $role = $_SESSION['user_role'] ?? $_SESSION['role'] ?? '';

    return isset($_SESSION['user_id']) && $role === 'Student';
}

function m5_requireStudent() {
    if (!m5_isStudentLoggedIn()) {
        header("Location: ../student/login.php");
        exit;
    }
}

function m5_getStudentId($conn, $user_id) {
    $sql = "SELECT student_id FROM student_profiles WHERE user_id = ? LIMIT 1";

    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        return null;
    }

    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    return $row['student_id'] ?? null;
}

function m5_getInternship($conn, $internship_id) {
    $sql = "SELECT i.internship_id, i.title, i.description,
                   i.requirements, i.deadline,
                   c.company_name
            FROM internships i
            INNER JOIN companies c ON i.company_id = c.company_id
            WHERE i.internship_id = ?
            AND c.verification_status = 'Verified'
            AND i.deadline >= CURDATE()
            LIMIT 1";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        return null;
    }

    mysqli_stmt_bind_param($stmt, "i", $internship_id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $internship = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    return $internship ?: null;
}

function m5_getApplication($conn, $application_id, $student_id) {
    $sql = "SELECT a.application_id, a.application_date, a.status,
                   i.title, i.description, i.deadline,
                   c.company_name
            FROM applications a
            INNER JOIN internships i ON a.internship_id = i.internship_id
            INNER JOIN companies c ON i.company_id = c.company_id
            WHERE a.application_id = ? AND a.student_id = ?
            LIMIT 1";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        return null;
    }

    mysqli_stmt_bind_param($stmt, "ii", $application_id, $student_id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $application = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    return $application ?: null;
}
