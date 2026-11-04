
<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';

m2_requireStudent();

$profile = m2_getStudentProfile($conn, $_SESSION['user_id']);

$page_css = "/University-Internship-and-Career-Portal-System/assets/css/member2.css";

include __DIR__ . '/../includes/header.php';

?>

<section class="m2-profile-section">

    <div class="m2-profile-card">

        <div class="m2-section-heading">
            <span>MY ACCOUNT</span>
            <h2>Student Profile</h2>
            <p>Your personal and academic information.</p>
        </div>

        <?php if ($profile) { ?>

            <div class="m2-profile-details">

                <div class="m2-profile-item">
                    <span>Full Name</span>
                    <strong><?php echo m2_escape($profile['name']); ?></strong>
                </div>

                <div class="m2-profile-item">
                    <span>Email Address</span>
                    <strong><?php echo m2_escape($profile['email']); ?></strong>
                </div>

                <div class="m2-profile-item">
                    <span>University</span>
                    <strong><?php echo m2_escape($profile['university'] ?? 'Not added'); ?></strong>
                </div>

                <div class="m2-profile-item">
                    <span>Course / Degree</span>
                    <strong><?php echo m2_escape($profile['course'] ?? 'Not added'); ?></strong>
                </div>

                <div class="m2-profile-item">
                    <span>Skills</span>
                    <strong><?php echo nl2br(m2_escape($profile['skills'] ?? 'Not added')); ?></strong>
                </div>

            </div>

            <div class="m2-profile-actions">

                <a href="edit-profile.php" class="m2-btn m2-btn-gold">
                    Edit Profile
                </a>

                <a href="index.php" class="m2-btn m2-btn-dark">
                    Dashboard
                </a>

                <a href="logout.php" class="m2-btn m2-btn-outline-dark">
                    Logout
                </a>

            </div>

        <?php } else { ?>

            <div class="m2-message m2-error">
                Student profile could not be found.
            </div>

        <?php } ?>

    </div>

</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
