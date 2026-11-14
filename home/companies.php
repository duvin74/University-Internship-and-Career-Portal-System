
<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';

// HOME CSS CONNECTION
$page_css = "/University-Internship-and-Career-Portal-System/assets/css/home.css";

$companies = m1_getVerifiedCompanies($conn);

require_once __DIR__ . '/../includes/header.php';

?>

<section class="m1-section">

    <h1 class="m1-title">Our Verified Companies</h1>

    <p class="m1-intro">
        Explore companies connected with CareerConnect.
    </p>

    <div class="m1-grid">

        <?php if (!empty($companies)): ?>

            <?php foreach ($companies as $company): ?>

                <div class="m1-card">

                    <h3>
                        <?php echo htmlspecialchars($company['company_name']); ?>
                    </h3>

                    <p>
                        <?php
                        echo htmlspecialchars(
                            $company['description'] ?? 'No description available.'
                        );
                        ?>
                    </p>

                    <?php if (!empty($company['website'])): ?>

                        <p>
                            <a href="<?php echo htmlspecialchars($company['website']); ?>"
                               target="_blank"
                               rel="noopener noreferrer">
                                Company Website
                            </a>
                        </p>

                    <?php endif; ?>

                    <a class="m1-btn"
                       href="company-details.php?id=<?php echo (int)$company['company_id']; ?>">
                        View Details
                    </a>

                </div>

            <?php endforeach; ?>

        <?php else: ?>

            <p class="m1-empty">No verified companies found.</p>

        <?php endif; ?>

    </div>

</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>