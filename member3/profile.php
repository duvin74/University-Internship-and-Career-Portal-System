
<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';

m3_requireCompany();

$page_css = "/University-Internship-and-Career-Portal-System/assets/css/member3.css";

$company = m3_getCompany($conn, $_SESSION['user_id']);

if (!$company) {
    die("Company profile not found.");
}

include __DIR__ . '/../includes/header.php';
?>

<section class="m3-profile">
    <h1>Company Profile</h1>

    <div class="m3-profile-box">
        <p><strong>Contact Person:</strong> <?php echo m3_escape($company['name']); ?></p>
        <p><strong>Email:</strong> <?php echo m3_escape($company['email']); ?></p>
        <p><strong>Company Name:</strong> <?php echo m3_escape($company['company_name']); ?></p>

        <p>
            <strong>Website:</strong>
            <?php if ($company['website']): ?>
                <a href="<?php echo m3_escape($company['website']); ?>" target="_blank" rel="noopener noreferrer">
                    <?php echo m3_escape($company['website']); ?>
                </a>
            <?php else: ?>
                Not provided
            <?php endif; ?>
        </p>

        <p><strong>Description:</strong>
            <?php echo nl2br(m3_escape($company['description'])); ?>
        </p>

        <p><strong>Verification Status:</strong>
            <?php echo m3_escape($company['verification_status']); ?>
        </p>

        <a href="edit-profile.php" class="m3-btn">Edit Profile</a>
        <a href="dashboard.php" class="m3-secondary-btn">Dashboard</a>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
