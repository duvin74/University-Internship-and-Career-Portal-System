<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';

m5_requireStudent();

$internship_id = filter_input(INPUT_GET, 'internship_id', FILTER_VALIDATE_INT);

if (!$internship_id) {
    header("Location: ../member4/index.php");
    exit;
}

$internship = m5_getInternship($conn, $internship_id);

$page_css = "/University-Internship-and-Career-Portal-System/assets/css/member5.css";

include __DIR__ . '/../includes/header.php';
?>

<section class="m5-section">
    <div class="m5-container">

        <?php if (!$internship): ?>

            <div class="m5-message">
                <h2>Internship Not Available</h2>
                <p>This internship may have expired or is not available.</p>
                <a href="../member4/index.php" class="m5-btn">
                    Browse Internships
                </a>
            </div>

        <?php else: ?>

            <div class="m5-form-wrapper">

                <h2>Submit Your Application</h2>
                <p class="m5-subtitle">
                    Review the internship details before applying.
                </p>

                <div class="m5-internship-info">
                    <h3><?php echo m5_escape($internship['title']); ?></h3>

                    <p>
                        Company:
                        <strong><?php echo m5_escape($internship['company_name']); ?></strong>
                    </p>

                    <p>
                        Deadline:
                        <strong><?php echo m5_escape($internship['deadline']); ?></strong>
                    </p>

                    <p><?php echo nl2br(m5_escape($internship['description'])); ?></p>
                </div>

                <form action="submit-application.php" method="POST">

                    <input type="hidden" name="internship_id"
                           value="<?php echo (int)$internship['internship_id']; ?>">

                    <label>
                        <input type="checkbox" name="confirm" value="yes" required>
                        I confirm that I want to apply for this internship.
                    </label>

                    <button type="submit" class="m5-btn">
                        Submit Application
                    </button>

                    <a href="../member4/details.php?id=<?php echo (int)$internship_id; ?>"
                       class="m5-cancel-link">
                        Cancel
                    </a>

                </form>

            </div>

        <?php endif; ?>

    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>