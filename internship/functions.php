
<?php

function m4_escape($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

// Get all available internships
function m4_getInternships($conn, $keyword = '') {

    $sql = "SELECT i.internship_id,
                   i.title,
                   i.description,
                   i.requirements,
                   i.deadline,
                   c.company_name,
                   c.website
            FROM internships i
            INNER JOIN companies c
                ON i.company_id = c.company_id
            WHERE c.verification_status = 'Verified'
              AND i.deadline >= CURDATE()";

    if ($keyword !== '') {
        $sql .= " AND (
                    i.title LIKE ?
                    OR i.description LIKE ?
                    OR i.requirements LIKE ?
                    OR c.company_name LIKE ?
                  )";
    }

    $sql .= " ORDER BY i.internship_id DESC";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        return [];
    }

    if ($keyword !== '') {
        $search = '%' . $keyword . '%';

        mysqli_stmt_bind_param(
            $stmt,
            "ssss",
            $search,
            $search,
            $search,
            $search
        );
    }

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $internships = mysqli_fetch_all($result, MYSQLI_ASSOC);

    mysqli_stmt_close($stmt);

    return $internships;
}


// Get one internship by ID
function m4_getInternshipDetails($conn, $internship_id) {

    $sql = "SELECT i.internship_id,
                   i.title,
                   i.description,
                   i.requirements,
                   i.deadline,
                   c.company_id,
                   c.company_name,
                   c.website,
                   c.description AS company_description
            FROM internships i
            INNER JOIN companies c
                ON i.company_id = c.company_id
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
