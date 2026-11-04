
<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';

$page_css = "/University-Internship-and-Career-Portal-System/assets/css/member3.css";

include __DIR__ . '/../includes/header.php';

$loggedIn = m3_isCompanyLoggedIn();
$companyName = $_SESSION['company_name'] ?? $_SESSION['user_name'] ?? 'Company';
?>

<section class="m3-hero">
    <div class="m3-hero-content">
        <span class="m3-tag">COMPANY PORTAL</span>

        <h1>Build Your Future With CareerConnect</h1>

        <p>
            Connect with talented university students,
            discover future professionals and find the right
            candidates for your organization.
        </p>

        <?php if ($loggedIn): ?>
            <h3>Welcome, <?php echo m3_escape($companyName); ?>!</h3>
            <a href="dashboard.php" class="m3-btn">Go to Dashboard</a>
        <?php else: ?>
            <div class="m3-buttons">
                <a href="register.php" class="m3-btn">Register Your Company</a>
                <a href="login.php" class="m3-btn m3-btn-outline">Company Login</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="m3-intro">
    <h2>Grow Your Organization With Us</h2>
    <p>
        CareerConnect helps companies connect with university
        students and provide valuable internship opportunities.
    </p>
</section>

<section class="m3-features">
    <h2>What You Can Do</h2>

    <div class="m3-card-container">
        <div class="m3-card">
            <h3>Company Registration</h3>
            <p>Create your company account and introduce your organization.</p>
            <a href="register.php">Register Now</a>
        </div>

        <div class="m3-card">
            <h3>Post Internships</h3>
            <p>Publish internship opportunities for university students.</p>
            <a href="login.php">Get Started</a>
        </div>

        <div class="m3-card">
            <h3>Manage Opportunities</h3>
            <p>Manage your internship opportunities through your dashboard.</p>
            <a href="login.php">Company Login</a>
        </div>
    </div>
</section>

<section class="m3-cta">
    <h2>Connect With Future Professionals</h2>
    <p>Join CareerConnect and discover new opportunities for your organization.</p>
    <a href="register.php" class="m3-btn">Join CareerConnect</a>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
