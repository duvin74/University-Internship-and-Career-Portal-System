
<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';

$page_css = "/University-Internship-and-Career-Portal-System/assets/css/home.css";

$achievements = m1_getAchievements($conn);

require_once __DIR__ . '/../includes/header.php';

?>

<section class="m1-section">

    <h1 class="m1-title">Company Achievements</h1>

    <p class="m1-intro">
        Discover the achievements and success stories
        of our verified companies.
    </p>

    <div class="m1-grid">

        <?php if (count($achievements) > 0): ?>

            <?php foreach ($achievements as $item): ?>

                <div class="m1-card">

                    <h3>
                        <?php echo htmlspecialchars($item['title']); ?>
                    </h3>

                    <p>
                        <?php echo htmlspecialchars($item['description']); ?>
                    </p>

                    <p>
                        <strong>Company:</strong>
                        <?php echo htmlspecialchars($item['company_name']); ?>
                    </p>

                    <small>
                        <?php echo htmlspecialchars($item['date']); ?>
                    </small>

                </div>

            <?php endforeach; ?>

        <?php else: ?>

            <p>No achievements available yet.</p>

        <?php endif; ?>

    </div>

</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
