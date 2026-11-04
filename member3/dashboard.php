
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
    <h1>Company Dashboard</h1>

    <p>Welcome, <strong><?php echo m3_escape($company['company_name']); ?></strong></p>

    <div class="m3-status">
        Verification Status:
        <strong><?php echo m3_escape($company['verification_status']); ?></strong>
    </div>

    <div class="m3-card-container">
        <div class="m3-card">
            <h3>Company Profile</h3>
            <p>View your company information.</p>
            <a href="profile.php">View Profile</a>
        </div>

        <div class="m3-card">
            <h3>Post Internship</h3>
            <p>Create a new internship opportunity.</p>
            <a href="post-internship.php">Post Internship</a>
        </div>

        <div class="m3-card">
            <h3>Manage Internships</h3>
            <p>View your posted internship opportunities.</p>
            <a href="manage-internships.php">Manage</a>
        </div>
    </div>

    <h2>Your Internship Posts</h2>

    <p>Total Posts: <?php echo count($internships); ?></p>

    <div class="m3-action-links">
        <a href="edit-profile.php">Edit Profile</a>
        <a href="logout.php">Logout</a>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
