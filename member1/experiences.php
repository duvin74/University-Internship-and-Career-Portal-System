
<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';

$page_css = "/University-Internship-and-Career-Portal-System/assets/css/member1.css";

$experiences = m1_getExperiences($conn);

require_once __DIR__ . '/../includes/header.php';

?>

<section class="m1-section">

    <h1 class="m1-title">Student Internship Experiences</h1>

    <p class="m1-intro">
        Read about the real internship experiences
        shared by university students.
    </p>

    <div class="m1-grid">

        <?php if (count($experiences) > 0): ?>

            <?php foreach ($experiences as $item): ?>

                <div class="m1-card">

                    <h3>
                        <?php echo htmlspecialchars($item['student_name']); ?>
                    </h3>

                    <p>
                        <?php echo nl2br(htmlspecialchars($item['content'])); ?>
                    </p>

                    <p>
                        <strong>Company:</strong>

                        <?php echo htmlspecialchars(
                            $item['company_name'] ?? 'Not specified'
                        ); ?>
                    </p>

                    <small>
                        <?php echo htmlspecialchars($item['created_at']); ?>
                    </small>

                </div>

            <?php endforeach; ?>

        <?php else: ?>

            <p>No student experiences have been shared yet.</p>

        <?php endif; ?>

    </div>

</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
