
<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';

$companies = m1_getVerifiedCompanies($conn, 3);

$page_css = "/University-Internship-and-Career-Portal-System/assets/css/home.css";

require_once __DIR__ . '/../includes/header.php';
?>

<!-- HERO SECTION -->
<section class="m1-hero">

    <div class="m1-hero-content">

        <span class="m1-tag">YOUR FUTURE STARTS HERE</span>

        <h1>
            Build Your Future With
            <span>CareerConnect</span>
        </h1>

        <p>
            Discover internship opportunities, connect with
            trusted companies and take the next step towards
            your professional career.
        </p>

        <div class="m1-hero-buttons">

            <a href="../internship/index.php" class="m1-btn m1-btn-gold">
                Explore Internships
            </a>

            <a href="companies.php" class="m1-btn m1-btn-outline">
                Explore Companies
            </a>

        </div>

        <div class="m1-hero-stats">

            <div>
                <h2>Career</h2>
                <p>Opportunities</p>
            </div>

            <div>
                <h2>Student</h2>
                <p>Development</p>
            </div>

            <div>
                <h2>Future</h2>
                <p>Success</p>
            </div>

        </div>

    </div>

    <div class="m1-hero-image">
        <img
            src="https://images.unsplash.com/photo-1521737711867-e3b97375f902?w=1000"
            alt="Students working together"
        >
    </div>

</section>

<!-- INTRO SECTION -->
<section class="m1-intro-section">

    <span class="m1-small-title">WELCOME TO CAREERCONNECT</span>

    <h2>Your Gateway to a Successful Career</h2>

    <p>
        CareerConnect helps university students discover
        internship opportunities, connect with companies
        and develop their professional skills.
    </p>

</section>

<!-- FEATURES SECTION -->
<section class="m1-section m1-features">

    <div class="m1-heading">
        <span>WHAT WE OFFER</span>
        <h2>Explore Our Platform</h2>
        <p>Everything you need to move forward in your career journey.</p>
    </div>

    <div class="m1-grid">

        <div class="m1-feature-card">
            <div class="m1-icon">01</div>
            <h3>Company Connections</h3>
            <p>
                Discover companies and learn about
                their career opportunities.
            </p>
            <a href="companies.php">Explore Companies →</a>
        </div>

        <div class="m1-feature-card">
            <div class="m1-icon">02</div>
            <h3>Internship Opportunities</h3>
            <p>
                Find internship opportunities that
                match your interests and skills.
            </p>
            <a href="../internship/index.php">Find Internships →</a>
        </div>

        <div class="m1-feature-card">
            <div class="m1-icon">03</div>
            <h3>Career Development</h3>
            <p>
                Build your skills and prepare
                yourself for your future career.
            </p>
            <a href="about.php">Learn More →</a>
        </div>

    </div>

</section>

<!-- COMPANIES SECTION -->
<section class="m1-section m1-company-section">

    <div class="m1-heading">
        <span>OUR PARTNERS</span>
        <h2>Featured Companies</h2>
        <p>Explore verified companies connected with our platform.</p>
    </div>

    <div class="m1-grid">

        <?php if (!empty($companies)): ?>

            <?php foreach ($companies as $company): ?>

                <div class="m1-company-card">

                    <div class="m1-company-icon">C</div>

                    <h3>
                        <?php echo htmlspecialchars($company['company_name']); ?>
                    </h3>

                    <p>
                        <?php
                        echo htmlspecialchars(
                            substr($company['description'] ?? '', 0, 120)
                        );
                        ?>
                    </p>

                    <a href="company-details.php?id=<?php echo (int)$company['company_id']; ?>">
                        View Company →
                    </a>

                </div>

            <?php endforeach; ?>

        <?php else: ?>

            <p class="m1-empty">
                No verified companies are available yet.
            </p>

        <?php endif; ?>

    </div>

    <div class="m1-center">
        <a href="companies.php" class="m1-btn m1-btn-dark">
            View All Companies
        </a>
    </div>

</section>

<!-- ABOUT SECTION -->
<section class="m1-about-section">

    <div class="m1-about-content">

        <span>ABOUT CAREERCONNECT</span>

        <h2>Connecting Students With Their Future</h2>

        <p>
            Our platform creates a connection between
            university students and companies. We aim to
            make internship discovery and career development
            easier and more accessible.
        </p>

        <a href="about.php" class="m1-btn m1-btn-gold">
            Discover More
        </a>

    </div>

    <div class="m1-about-image">
        <img
            src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=1000"
            alt="University students collaborating"
        >
    </div>

</section>

<!-- FINAL CTA -->
<section class="m1-cta">

    <span>TAKE THE NEXT STEP</span>

    <h2>Ready to Start Your Career Journey?</h2>

    <p>
        Explore opportunities and take the first step
        towards building your professional future.
    </p>

    <a href="../internship/index.php" class="m1-btn m1-btn-gold">
        Explore Opportunities
    </a>

</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
