<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';

$page_css = "/University-Internship-and-Career-Portal-System/assets/css/member6.css";

include __DIR__ . '/../includes/header.php';
?>

<section class="m6-hero">
    <div class="m6-container">

        <span class="m6-tag">CAREERCONNECT ADMINISTRATION</span>

        <h1>Manage Your Career Portal</h1>

        <p>
            Monitor students, verify companies, manage internships
            and oversee applications through one administration system.
        </p>

        <a href="login.php" class="m6-btn">
            Admin Login
        </a>

    </div>
</section>

<section class="m6-section">
    <div class="m6-container">

        <h2>Administration Features</h2>

        <div class="m6-grid">

            <div class="m6-card">
                <h3>Company Verification</h3>
                <p>Review company registrations and verify companies.</p>
            </div>

            <div class="m6-card">
                <h3>Student Management</h3>
                <p>View registered students and their basic details.</p>
            </div>

            <div class="m6-card">
                <h3>Internship Monitoring</h3>
                <p>Monitor internships posted by registered companies.</p>
            </div>

            <div class="m6-card">
                <h3>Application Management</h3>
                <p>View student applications and their current status.</p>
            </div>

        </div>

    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>