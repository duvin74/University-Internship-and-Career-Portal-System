
<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';

$page_css = "/University-Internship-and-Career-Portal-System/assets/css/member2.css";

include __DIR__ . '/../includes/header.php';

?>

<section class="m2-hero">

    <div class="m2-hero-content">

        <span class="m2-label">CAREERCONNECT STUDENT PORTAL</span>

        <h1>
            Your Career Journey
            <span>Starts Here</span>
        </h1>

        <p>
            Create your student account, manage your profile
            and get ready to explore internship opportunities
            and career development.
        </p>

        <?php if (m2_isStudentLoggedIn()) { ?>

            <a href="profile.php" class="m2-btn m2-btn-gold">
                My Profile
            </a>

            <a href="edit-profile.php" class="m2-btn m2-btn-outline">
                Edit Profile
            </a>

        <?php } else { ?>

            <a href="register.php" class="m2-btn m2-btn-gold">
                Register Now
            </a>

            <a href="login.php" class="m2-btn m2-btn-outline">
                Student Login
            </a>

        <?php } ?>

    </div>

    <div class="m2-hero-image">
        <img
            src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=900&q=85"
            alt="University students studying together"
        >
    </div>

</section>


<?php if (m2_isStudentLoggedIn()) { ?>

    <?php
    $profile = m2_getStudentProfile($conn, $_SESSION['user_id']);
    ?>

    <section class="m2-dashboard">

        <div class="m2-section-heading">
            <span>STUDENT DASHBOARD</span>
            <h2>Welcome, <?php echo m2_escape($_SESSION['user_name']); ?>!</h2>
            <p>Manage your student account and personal information.</p>
        </div>

        <div class="m2-dashboard-grid">

            <div class="m2-dashboard-card">
                <div class="m2-card-icon">P</div>
                <h3>My Profile</h3>
                <p>View your personal and academic details.</p>
                <a href="profile.php">View Profile →</a>
            </div>

            <div class="m2-dashboard-card">
                <div class="m2-card-icon">E</div>
                <h3>Edit Profile</h3>
                <p>Update your university, course and skills.</p>
                <a href="edit-profile.php">Edit Details →</a>
            </div>

            <div class="m2-dashboard-card">
                <div class="m2-card-icon">I</div>
                <h3>Internship Opportunities</h3>
                <p>Explore available internship opportunities.</p>
                <a href="../member4/index.php">Explore Internships →</a>
            </div>

        </div>

    </section>

<?php } else { ?>

    <section class="m2-info-section">

        <div class="m2-section-heading">
            <span>WHY JOIN CAREERCONNECT?</span>
            <h2>Build Your Professional Future</h2>
            <p>Start preparing for your future career with your student account.</p>
        </div>

        <div class="m2-dashboard-grid">

            <div class="m2-dashboard-card">
                <div class="m2-card-icon">01</div>
                <h3>Create Your Account</h3>
                <p>Register as a university student and create your account.</p>
            </div>

            <div class="m2-dashboard-card">
                <div class="m2-card-icon">02</div>
                <h3>Manage Your Profile</h3>
                <p>Add your academic details and skills to your profile.</p>
            </div>

            <div class="m2-dashboard-card">
                <div class="m2-card-icon">03</div>
                <h3>Explore Opportunities</h3>
                <p>Find internship opportunities through CareerConnect.</p>
            </div>

        </div>

    </section>

<?php } ?>


<?php include __DIR__ . '/../includes/footer.php'; ?>
