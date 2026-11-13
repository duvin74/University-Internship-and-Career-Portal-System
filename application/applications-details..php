<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';

m5_requireStudent();

$application_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$user_id = (int)$_SESSION['user_id'];
$student_id = m5_getStudentId($conn, $user_id);

$application = null;

if ($application_id && $student_id) {
    $application = m5_getApplication($conn, $application_id, $student_id);
}

$page_css = "/University-Internship-and-Career-Portal-System/assets/css/member5.css";

include __DIR__ . '/../includes/header.php';
?>

<section class="m5-section">
    <div class="m5-container">

        <?php if (!$application): ?>

            <div class="m5-message">
                <h2>Application Not Found</h2>
                <p>The requested application could not be found.</p>
                <a href="my-applications.php" class="m5-btn">
                    Back to My Applications
                </a>
            </div>

        <?php else: ?>

            <div class="m5-details-card">

                <h2>Application Details</h2>

                <div class="m5-detail-row">
                    <strong>Application ID</strong>
                    <span><?php echo (int)$application['application_id']; ?></span>
                </div>

                <div class="m5-detail-row">
                    <strong>Internship Title</strong>
                    <span><?php echo m5_escape($application['title']); ?></span>
                </div>

                <div class="m5-detail-row">
                    <strong>Company</strong>
                    <span><?php echo m5_escape($application['company_name']); ?></span>
                </div>

                <div class="m5-detail-row">
                    <strong>Application Date</strong>
                    <span><?php echo m5_escape($application['application_date']); ?></span>
                </div>

                <div class="m5-detail-row">
                    <strong>Deadline</strong>
                    <span><?php echo m5_escape($application['deadline']); ?></span>
                </div>

                <div class="m5-detail-row">
                    <strong>Application Status</strong>
                    <span class="m5-status">
                        <?php echo m5_escape($application['status']); ?>
                    </span>
                </div>

                <div class="m5-description">
                    <h3>Internship Description</h3>
                    <p><?php echo nl2br(m5_escape($application['description'])); ?></p>
                </div>

                <?php if ($application['status'] === 'Pending'): ?>
                    <form action="withdraw-application.php" method="POST"
                          onsubmit="return confirm('Are you sure you want to withdraw this application?');">

                        <input type="hidden" name="application_id"
                               value="<?php echo (int)$application['application_id']; ?>">

                        <button type="submit" class="m5-withdraw-btn">
                            Withdraw Application
                        </button>
                    </form>
                <?php endif; ?>

                <a href="my-applications.php" class="m5-cancel-link">
                    Back to My Applications
                </a>

            </div>

        <?php endif; ?>

    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>