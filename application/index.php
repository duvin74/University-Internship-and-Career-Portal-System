
<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';

$page_css = "/University-Internship-and-Career-Portal-System/assets/css/application.css";

$internship_id = filter_input(INPUT_GET, 'internship_id', FILTER_VALIDATE_INT);

include __DIR__ . '/../includes/header.php';
?>

<section class="m5-hero">
    <div class="m5-container">
        <span class="m5-tag">CAREERCONNECT APPLICATION PORTAL</span>

        <h1>Take the Next Step in Your Career</h1>

        <p>
            Apply for internship opportunities, track your applications
            and move closer to your career goals.
        </p>

        <div class="m5-hero-buttons">
            <a href="../internship/index.php" class="m5-btn">
                Browse Internships
            </a>

            <a href="my-applications.php" class="m5-btn m5-outline">
                My Applications
            </a>
        </div>
    </div>
</section>

<section class="m5-section">
    <div class="m5-container">
        <h2>Application Management</h2>
        <p class="m5-subtitle">
            Manage your internship applications easily through CareerConnect.
        </p>

        <div class="m5-grid">

            <div class="m5-card">
                <div class="m5-card-icon">01</div>
                <h3>Apply for Internships</h3>
                <p>
                    Select an internship and submit your application.
                </p>
                <a href="../internship/index.php">Find Internships</a>
            </div>

            <div class="m5-card">
                <div class="m5-card-icon">02</div>
                <h3>Track Applications</h3>
                <p>
                    Check the current status of your submitted applications.
                </p>
                <a href="my-applications.php">View Applications</a>
            </div>

            <div class="m5-card">
                <div class="m5-card-icon">03</div>
                <h3>Application Details</h3>
                <p>
                    View the details of your internship applications.
                </p>
                <a href="my-applications.php">View Details</a>
            </div>

        </div>

        <?php if ($internship_id): ?>
            <div class="m5-notice">
                You selected an internship. Continue to submit your application.
                <a href="apply.php?internship_id=<?php echo (int)$internship_id; ?>">
                    Apply Now
                </a>
            </div>
        <?php endif; ?>

    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
