
<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';

$page_css = "/University-Internship-and-Career-Portal-System/assets/css/home.css";

$company_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$company = $company_id
    ? m1_getCompanyDetails($conn, $company_id)
    : null;

require_once __DIR__ . '/../includes/header.php';

?>

<section class="m1-section">

    <?php if ($company): ?>

        <div class="m1-details-card">

            <h1><?php echo htmlspecialchars($company['company_name']); ?></h1>

            <h3>Company Description</h3>

            <p>
                <?php echo nl2br(htmlspecialchars($company['description'] ?? 'No description available.')); ?>
            </p>

            <?php if (!empty($company['website'])): ?>

                <p>
                    Website:
                    <a href="<?php echo htmlspecialchars($company['website']); ?>"
                       target="_blank" rel="noopener noreferrer">
                        Visit Company Website
                    </a>
                </p>

            <?php endif; ?>

            <a href="../internship/index.php" class="m1-btn">
                Explore Internships
            </a>

        </div>

    <?php else: ?>

        <h2>Company Not Found</h2>

        <p>This company does not exist or is not verified.</p>

        <a href="companies.php" class="m1-btn">
            Back to Companies
        </a>

    <?php endif; ?>

</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
