
<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function m3_escape($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function m3_isCompanyLoggedIn() {
    $role = $_SESSION['user_role'] ?? $_SESSION['role'] ?? '';

    return isset($_SESSION['user_id']) && $role === 'Company';
}

function m3_requireCompany() {
    if (!m3_isCompanyLoggedIn()) {
        header("Location: login.php");
        exit;
    }
}

function m3_getCompany($conn, $user_id) {
    $sql = "SELECT u.user_id, u.name, u.email,
                   c.company_id, c.company_name,
                   c.website, c.description, c.verification_status
            FROM Users u
            INNER JOIN companies c ON u.user_id = c.user_id
            WHERE u.user_id = ?
            LIMIT 1";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) return null;

    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $company = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    return $company ?: null;
}

function m3_getCompanyInternships($conn, $company_id) {
    $sql = "SELECT internship_id, title, description,
                   requirements, deadline
            FROM internships
            WHERE company_id = ?
            ORDER BY internship_id DESC";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) return [];

    mysqli_stmt_bind_param($stmt, "i", $company_id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $items = mysqli_fetch_all($result, MYSQLI_ASSOC);

    mysqli_stmt_close($stmt);

    return $items;
}

/* GET INTERNSHIPS WITH APPLICANT COUNT */

function m3_getCompanyInternshipsWithCount($conn, $company_id) {

    $sql = "SELECT i.internship_id,
                   i.title,
                   i.description,
                   i.requirements,
                   i.deadline,
                   (
                       SELECT COUNT(*)
                       FROM applications a
                       WHERE a.internship_id = i.internship_id
                   ) AS applicants_count
            FROM internships i
            WHERE i.company_id = ?
            ORDER BY i.internship_id DESC";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) return [];

    mysqli_stmt_bind_param($stmt, "i", $company_id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $items = mysqli_fetch_all($result, MYSQLI_ASSOC);

    mysqli_stmt_close($stmt);

    return $items;
}

/* GET ONE INTERNSHIP BELONGING TO THIS COMPANY */

function m3_getCompanyInternshipById($conn, $internship_id, $company_id) {

    $sql = "SELECT internship_id, title, description, deadline
            FROM internships
            WHERE internship_id = ?
            AND company_id = ?
            LIMIT 1";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) return null;

    mysqli_stmt_bind_param(
        $stmt,
        "ii",
        $internship_id,
        $company_id
    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $internship = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    return $internship ?: null;
}

/* GET STUDENTS WHO APPLIED FOR THIS COMPANY'S INTERNSHIP */

function m3_getInternshipApplicants($conn, $internship_id, $company_id) {

    $sql = "SELECT
                a.application_id,
                a.application_date,
                sp.student_id,
                sp.university,
                sp.course,
                sp.skills,
                sp.profile_photo,
                sp.cv_path,
                u.name,
                u.email

            FROM applications a

            INNER JOIN internships i
                ON a.internship_id = i.internship_id

            INNER JOIN student_profiles sp
                ON a.student_id = sp.student_id

            INNER JOIN users u
                ON sp.user_id = u.user_id

            WHERE a.internship_id = ?
            AND i.company_id = ?
            AND u.role = 'Student'

            ORDER BY a.application_date DESC";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) return [];

    mysqli_stmt_bind_param(
        $stmt,
        "ii",
        $internship_id,
        $company_id
    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $applicants = mysqli_fetch_all($result, MYSQLI_ASSOC);

    mysqli_stmt_close($stmt);

    return $applicants;
}
