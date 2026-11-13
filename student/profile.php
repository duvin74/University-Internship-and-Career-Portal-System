
<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';

m2_requireStudent();

$profile = m2_getStudentProfile($conn, $_SESSION['user_id']);

if (!$profile) {
    die("Student profile not found.");
}

$page_css = "/University-Internship-and-Career-Portal-System/assets/css/student.css";

$photo_url = m2_getProfilePhotoUrl($profile);
$has_cv = m2_hasCV($profile);

include __DIR__ . '/../includes/header.php';

?>

<section class="m2-new-profile-section">

    <div class="m2-new-profile-heading">
        <span>STUDENT ACCOUNT</span>
        <h1>My Profile</h1>
        <p>View your personal and academic information.</p>
    </div>

    <div class="m2-new-profile-card">

        <div class="m2-new-profile-top">

            <?php if ($photo_url): ?>

                <img src="<?php echo m2_escape($photo_url); ?>"
                     alt="Profile Photo"
                     class="m2-large-avatar">

            <?php else: ?>

                <div class="m2-large-avatar m2-avatar-placeholder">
                    <?php echo m2_escape(strtoupper(substr($profile['name'], 0, 1))); ?>
                </div>

            <?php endif; ?>

            <div>
                <h2><?php echo m2_escape($profile['name']); ?></h2>
                <p><?php echo m2_escape($profile['email']); ?></p>
                <span class="m2-student-badge">Student Account</span>
            </div>

        </div>

        <div class="m2-new-profile-details">

            <div>
                <span>University</span>
                <strong>
                    <?php echo m2_escape($profile['university'] ?: 'Not added'); ?>
                </strong>
            </div>

            <div>
                <span>Course</span>
                <strong>
                    <?php echo m2_escape($profile['course'] ?: 'Not added'); ?>
                </strong>
            </div>

            <div class="m2-profile-full-row">
                <span>Skills</span>
                <strong>
                    <?php echo nl2br(m2_escape($profile['skills'] ?: 'Not added')); ?>
                </strong>
            </div>

        </div>

        <div class="m2-profile-cv-area">

            <h3>Curriculum Vitae (CV)</h3>

            <?php if ($has_cv): ?>

                <p>Your CV has been uploaded successfully.</p>

                <a href="view-cv.php">
                    View CV
                </a>

                <a href="download-cv.php?mode=download">
                    Download CV
                </a>

            <?php else: ?>

                <p>You have not uploaded a CV yet.</p>

            <?php endif; ?>

        </div>

        <div class="m2-new-profile-actions">

            <a href="index.php" class="m2-profile-secondary">
                Back to Dashboard
            </a>

        </div>

    </div>

</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
