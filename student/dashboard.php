
<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';

m2_requireStudent();

$profile = m2_getStudentProfile($conn, $_SESSION['user_id']);

if (!$profile) {
    die("Student profile could not be found.");
}

$page_css = "/University-Internship-and-Career-Portal-System/assets/css/student.css";

$photo = '';

if (!empty($profile['profile_photo'])) {
    $photo = 'uploads/' . rawurlencode(
        basename($profile['profile_photo'])
    );
}

$name = $profile['name'] ?? 'Student';
$has_cv = !empty($profile['cv_path']);

include __DIR__ . '/../includes/header.php';

?>

<div class="m2-dashboard-layout">

    <!-- SIDEBAR -->
    <aside class="m2-sidebar">

        <div class="m2-sidebar-brand">

            <span class="m2-brand-icon">C</span>

            <div>
                <strong>CareerConnect</strong>
                <small>Student Portal</small>
            </div>

        </div>

        <!-- STUDENT PROFILE -->
        <div class="m2-sidebar-profile">

            <?php if ($photo): ?>

                <img
                    src="<?php echo m2_escape($photo); ?>"
                    alt="Student Profile Photo"
                    class="m2-sidebar-avatar"
                >

            <?php else: ?>

                <div class="m2-sidebar-avatar m2-avatar-placeholder">
                    <?php echo m2_escape(strtoupper(substr($name, 0, 1))); ?>
                </div>

            <?php endif; ?>

            <h3><?php echo m2_escape($name); ?></h3>

            <p>Student</p>

        </div>

        <!-- NAVIGATION -->
        <nav class="m2-sidebar-nav">

            <a href="dashboard.php" class="active">
                <span>⌂</span>
                Dashboard
            </a>

            <a href="profile.php">
                <span>◉</span>
                My Profile
            </a>

            <a href="edit-profile.php">
                <span>✎</span>
                Edit Profile
            </a>

            <a href="upload-files.php">
                <span>⇧</span>
                Upload Photo / CV
            </a>

            <a href="../application/index.php">
                <span>▤</span>
                My Applications
            </a>

            <a href="../internship/index.php">
                <span>⌕</span>
                Explore Internships
            </a>

        </nav>

        <!-- LOGOUT -->
        <a href="logout.php" class="m2-sidebar-logout">
            ↪ Logout
        </a>

    </aside>

    <!-- MAIN DASHBOARD -->
    <main class="m2-dashboard-main">

        <!-- TOP BAR -->
        <div class="m2-dashboard-topbar">

            <div>

                <span class="m2-dashboard-label">
                    STUDENT PORTAL
                </span>

                <h1>My Dashboard</h1>

                <p>
                    Manage your profile and career opportunities.
                </p>

            </div>

            <a href="profile.php" class="m2-top-profile">
                View Profile →
            </a>

        </div>

        <!-- WELCOME SECTION -->
        <section class="m2-welcome-card">

            <div class="m2-welcome-content">

                <span>WELCOME BACK</span>

                <h2>
                    Hello, <?php echo m2_escape($name); ?>!
                </h2>

                <p>
                    Keep your profile updated and get ready
                    for your next career opportunity.
                </p>

                <a href="edit-profile.php" class="m2-dashboard-button">
                    Update My Profile
                </a>

            </div>

        </section>

        <!-- QUICK ACCESS -->
        <section class="m2-quick-links">

            <h2>Quick Access</h2>

            <div class="m2-quick-grid">

                <!-- MY APPLICATIONS -->
                <a href="../application/index.php"
                   class="m2-quick-card">

                    <span>▤</span>

                    <strong>My Applications</strong>

                    <small>Check your applications</small>

                </a>

                <!-- INTERNSHIPS -->
                <a href="../internship/index.php"
                   class="m2-quick-card">

                    <span>⌕</span>

                    <strong>Internships</strong>

                    <small>Explore opportunities</small>

                </a>

                <!-- MY CV -->
                <div class="m2-quick-cv">

                    <span>▣</span>

                    <strong>My CV</strong>

                    <?php if ($has_cv): ?>

                        <small>Your CV is uploaded</small>

                        <div class="m2-cv-buttons">

                            <a
                                href="view-cv.php"
                                target="_blank"
                                rel="noopener noreferrer"
                            >
                                View
                            </a>

                            <a href="download-cv.php?mode=download">
                                Download
                            </a>

                        </div>

                    <?php else: ?>

                        <small>No CV uploaded yet</small>

                        <a href="upload-files.php" class="m2-cv-upload">
                            Upload CV
                        </a>

                    <?php endif; ?>

                </div>

            </div>

        </section>

    </main>

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
