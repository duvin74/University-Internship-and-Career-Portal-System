<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';

m6_requireAdmin();

$total_students = m6_getCount(
    $conn,
    "SELECT COUNT(*) AS total FROM Users WHERE role='Student'"
);

$total_companies = m6_getCount(
    $conn,
    "SELECT COUNT(*) AS total FROM companies"
);

$pending_companies = m6_getCount(
    $conn,
    "SELECT COUNT(*) AS total FROM companies WHERE verification_status='Pending'"
);

$total_internships = m6_getCount(
    $conn,
    "SELECT COUNT(*) AS total FROM internships"
);

$total_applications = m6_getCount(
    $conn,
    "SELECT COUNT(*) AS total FROM applications"
);

$page_css = "/University-Internship-and-Career-Portal-System/assets/css/member6.css";

include __DIR__ . '/../includes/header.php';
?>

<section class="m6-section">

    <div class="m6-container">

        <div class="m6-dashboard-heading">
            <div>
                <h1>Admin Dashboard</h1>
                <p>Welcome, <?php echo m6_escape($_SESSION['user_name'] ?? 'Admin'); ?>!</p>
            </div>

            <a href="logout.php" class="m6-logout-btn">Logout</a>
        </div>

        <div class="m6-stats">

            <div class="m6-stat-card">
                <h3>Total Students</h3>
                <strong><?php echo $total_students; ?></strong>
            </div>

            <div class="m6-stat-card">
                <h3>Total Companies</h3>
                <strong><?php echo $total_companies; ?></strong>
            </div>

            <div class="m6-stat-card">
                <h3>Pending Companies</h3>
                <strong><?php echo $pending_companies; ?></strong>
            </div>

            <div class="m6-stat-card">
                <h3>Total Internships</h3>
                <strong><?php echo $total_internships; ?></strong>
            </div>

            <div class="m6-stat-card">
                <h3>Total Applications</h3>
                <strong><?php echo $total_applications; ?></strong>
            </div>

        </div>

        <div class="m6-admin-links">

            <a href="companies.php">Manage Companies</a>
            <a href="students.php">Manage Students</a>
            <a href="internships.php">Manage Internships</a>
            <a href="applications.php">Manage Applications</a>

        </div>

    </div>

</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>