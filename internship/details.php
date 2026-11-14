
<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';

$page_css = "/University-Internship-and-Career-Portal-System/assets/css/internship.css";

$internship_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$internship = null;

if ($internship_id && $internship_id > 0) {
    $internship = m4_getInternshipDetails($conn, $internship_id);
}

include __DIR__ . '/../includes/header.php';

?>

<section class="m4-details-section">

    <?php if (!$internship): ?>

        <div class="m4-empty">

            <h2>Internship Not Found</h2>

            <p>
                This internship is unavailable, expired or does not exist.
            </p>

            <a href="index.php" class="m4-btn">
                Back to Internships
            </a>

        </div>

    <?php else: ?>

        <div class="m4-details-box">

            <span class="m4-label">INTERNSHIP DETAILS</span>

            <h1>
                <?php echo m4_escape($internship['title']); ?>
            </h1>

            <h3>
                <?php echo m4_escape($internship['company_name']); ?>
            </h3>

            <div class="m4-detail-info">

                <p>
                    <strong>Application Deadline:</strong>
                    <?php echo m4_escape($internship['deadline']); ?>
                </p>

                <p>
                    <strong>Company Website:</strong>

                    <?php if (!empty($internship['website'])): ?>

                        <a
                            href="<?php echo m4_escape($internship['website']); ?>"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            Visit Company Website
                        </a>

                    <?php else: ?>

                        Not provided

                    <?php endif; ?>

                </p>

            </div>

            <div class="m4-detail-content">

                <h2>Internship Description</h2>

                <p>
                    <?php echo nl2br(m4_escape($internship['description'])); ?>
                </p>

            </div>

            <div class="m4-detail-content">

                <h2>Requirements</h2>

                <p>
                    <?php
                    if (!empty($internship['requirements'])) {
                        echo nl2br(m4_escape($internship['requirements']));
                    } else {
                        echo "No specific requirements provided.";
                    }
                    ?>
                </p>

            </div>

            <div class="m4-detail-content">

                <h2>About the Company</h2>

                <p>
                    <?php
                    if (!empty($internship['company_description'])) {
                        echo nl2br(m4_escape($internship['company_description']));
                    } else {
                        echo "Company description is not available.";
                    }
                    ?>
                </p>

            </div>

            <div class="m4-detail-buttons">

                <a
                    href="../application/index.php?internship_id=<?php echo (int)$internship['internship_id']; ?>"
                    class="m4-btn"
                >
                    Apply for Internship
                </a>

                <a href="index.php" class="m4-back-btn">
                    Back to Internships
                </a>

            </div>

        </div>

    <?php endif; ?>

</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>