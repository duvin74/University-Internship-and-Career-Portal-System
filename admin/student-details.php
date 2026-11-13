
<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';

m6_requireAdmin();

$user_id = (int)($_GET['id'] ?? 0);

if ($user_id <= 0) {
    die("Invalid student ID.");
}

$sql = "SELECT 
            u.user_id,
            u.name,
            u.email,
            s.student_id,
            s.university,
            s.course
        FROM Users u
        LEFT JOIN student_profiles s
        ON u.user_id = s.user_id
        WHERE u.user_id = ?
        AND u.role = 'Student'
        LIMIT 1";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Database query failed.");
}

mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$student = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$student) {
    die("Student not found.");
}

$page_css = "/University-Internship-and-Career-Portal-System/assets/css/admin.css";

require_once __DIR__ . '/../includes/header.php';

?>

<section class="m6-section">

    <div class="m6-container">

        <h1>Student Details</h1>

        <p class="m6-subtitle">
            View registered student information.
        </p>

        <div class="m6-card">

            <h2><?php echo m6_escape($student['name']); ?></h2>

            <p>
                <strong>Student ID:</strong>
                <?php echo m6_escape($student['student_id'] ?? 'Not provided'); ?>
            </p>

            <p>
                <strong>Email:</strong>
                <?php echo m6_escape($student['email']); ?>
            </p>

            <p>
                <strong>University:</strong>
                <?php echo m6_escape($student['university'] ?? 'Not provided'); ?>
            </p>

            <p>
                <strong>Course:</strong>
                <?php echo m6_escape($student['course'] ?? 'Not provided'); ?>
            </p>

            <a href="students.php" class="m6-view-button">
                Back to Students
            </a>

        </div>

    </div>

</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
