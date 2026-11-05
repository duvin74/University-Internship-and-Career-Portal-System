
<?php

// Get all verified companies
function m1_getVerifiedCompanies($conn, $limit = 0)
{
    $limit = max(0, (int)$limit);

    $sql = "SELECT company_id, company_name, website, description
            FROM companies
            WHERE verification_status = 'Verified'
            ORDER BY company_id DESC";

    if ($limit > 0) {
        $sql .= " LIMIT " . $limit;
    }

    $result = mysqli_query($conn, $sql);

    return $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];
}


// Get one company by ID
function m1_getCompanyDetails($conn, $company_id)
{
    $sql = "SELECT company_id, company_name, website,
                   description, verification_status
            FROM companies
            WHERE company_id = ?
            AND verification_status = 'Verified'";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        return null;
    }

    mysqli_stmt_bind_param($stmt, "i", $company_id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $company = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    return $company ?: null;
}


// Get latest company achievements
function m1_getAchievements($conn, $limit = 0)
{
    $limit = max(0, (int)$limit);

    $sql = "SELECT a.achievement_id, a.title,
                   a.description, a.date,
                   c.company_name
            FROM company_achievements a
            INNER JOIN companies c
                ON a.company_id = c.company_id
            WHERE c.verification_status = 'Verified'
            ORDER BY a.date DESC";

    if ($limit > 0) {
        $sql .= " LIMIT " . $limit;
    }

    $result = mysqli_query($conn, $sql);

    return $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];
}


// Get student internship experiences
function m1_getExperiences($conn)
{
    $sql = "SELECT e.experience_id, e.content,
                   e.created_at, u.name AS student_name,
                   c.company_name
            FROM experiences e
            INNER JOIN student_profiles sp
                ON e.student_id = sp.student_id
            INNER JOIN Users u
                ON sp.user_id = u.user_id
            LEFT JOIN companies c
                ON e.company_id = c.company_id
            ORDER BY e.created_at DESC";

    $result = mysqli_query($conn, $sql);

    return $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];
}

?>
