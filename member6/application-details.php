<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';

m6_requireAdmin();

$applicationId = (int)($_GET['id'] ?? 0);

$sql = "SELECT a.application_id, a.application_date, a.status,
               u.name AS student_name, u.email,
               i.title AS internship_title,
               c.company_name
        FROM applications a
        INNER JOIN student_profiles s ON a.student_id = s.student_id
        INNER JOIN Users u ON s.user_id = u.user_id
        INNER JOIN internships i ON a.internship_id = i.internship_id
        INNER JOIN companies c ON i.company_id = c.company_id
        WHERE a.application_id = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $applicationId);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$application = mysqli_fetch_assoc($result);

$page_css = "/University-Internship-and-Career-Portal-System/assets/css/member6.css";

include __DIR__ . '/../includes/header.php';
?>

<section class="m6-section">
    <div class="m6-container">

        <h1>Application Details</h1>

        <?php if ($application): ?>

            <div class="m6-details-card">

                <p><strong>Student:</strong>
                    <?php echo m6_escape($application['student_name']); ?>
                </p>

                <p><strong>Email:</strong>
                    <?php echo m6_escape($application['email']); ?>
                </p>

                <p><strong>Internship:</strong>
                    <?php echo m6_escape($application['internship_title']); ?>
                </p>

                <p><strong>Company:</strong>
                    <?php echo m6_escape($application['company_name']); ?>
                </p>

                <p><strong>Applied Date:</strong>
                    <?php echo m6_escape($application['application_date']); ?>
                </p>

                <p><strong>Status:</strong>
                    <?php echo m6_escape($application['status']); ?>
                </p>

                <a href="applications.php" class="m6-back-link">
                    Back to Applications
                </a>

            </div>

        <?php else: ?>

            <div class="m6-message error">
                Application not found.
            </div>

            <a href="applications.php" class="m6-back-link">
                Back to Applications
            </a>

        <?php endif; ?>

    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>