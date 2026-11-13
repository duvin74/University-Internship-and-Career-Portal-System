
<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';

m6_requireAdmin();

$company_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$company = $company_id
    ? m6_getCompany($conn, $company_id)
    : null;

$page_css = "/University-Internship-and-Career-Portal-System/assets/css/admin.css";

include __DIR__ . '/../includes/header.php';
?>

<section class="m6-section">

    <div class="m6-container">

        <?php if (!$company): ?>

            <div class="m6-message">
                <h2>Company Not Found</h2>
                <a href="companies.php" class="m6-btn">Back to Companies</a>
            </div>

        <?php else: ?>

            <div class="m6-details-card">

                <h2><?php echo m6_escape($company['company_name']); ?></h2>

                <p><strong>Contact Person:</strong>
                    <?php echo m6_escape($company['name']); ?>
                </p>

                <p><strong>Email:</strong>
                    <?php echo m6_escape($company['email']); ?>
                </p>

                <p><strong>Website:</strong>
                    <?php echo m6_escape($company['website'] ?: 'Not provided'); ?>
                </p>

                <p><strong>Description:</strong>
                    <?php echo nl2br(m6_escape($company['description'])); ?>
                </p>

                <p><strong>Verification Status:</strong>
                    <?php echo m6_escape($company['verification_status']); ?>
                </p>

                <?php if ($company['verification_status'] === 'Pending'): ?>

                    <form action="verify-company.php" method="POST">

                        <input type="hidden" name="company_id"
                               value="<?php echo (int)$company['company_id']; ?>">

                        <button type="submit" name="action" value="Verified"
                                class="m6-btn">
                            Verify Company
                        </button>

                        <button type="submit" name="action" value="Rejected"
                                class="m6-reject-btn">
                            Reject Company
                        </button>

                    </form>

                <?php endif; ?>

                <a href="companies.php" class="m6-back-link">
                    Back to Companies
                </a>

            </div>

        <?php endif; ?>

    </div>

</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
