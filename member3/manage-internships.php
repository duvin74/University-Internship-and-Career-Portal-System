
<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';

m3_requireCompany();

$page_css = "/University-Internship-and-Career-Portal-System/assets/css/member3.css";

$company = m3_getCompany($conn, $_SESSION['user_id']);

if (!$company) {
    die("Company profile not found.");
}

$internships = m3_getCompanyInternships($conn, $company['company_id']);

include __DIR__ . '/../includes/header.php';
?>

<section class="m3-dashboard">
    <h1>Manage Internships</h1>

    <a href="post-internship.php" class="m3-btn">Post New Internship</a>

    <?php if (empty($internships)): ?>
        <div class="m3-card">
            <p>You have not posted any internships yet.</p>
        </div>
    <?php else: ?>

        <div class="m3-internship-list">
            <?php foreach ($internships as $item): ?>
                <div class="m3-internship-card">

                    <h3><?php echo m3_escape($item['title']); ?></h3>

                    <p><?php echo nl2br(m3_escape($item['description'])); ?></p>

                    <p>
                        <strong>Requirements:</strong>
                        <?php echo nl2br(m3_escape($item['requirements'])); ?>
                    </p>

                    <p>
                        <strong>Deadline:</strong>
                        <?php echo m3_escape($item['deadline']); ?>
                    </p>

                </div>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>

    <a href="dashboard.php" class="m3-secondary-btn">Back to Dashboard</a>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
