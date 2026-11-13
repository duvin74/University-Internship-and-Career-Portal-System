
<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';

$page_css = "/University-Internship-and-Career-Portal-System/assets/css/student.css";

include __DIR__ . '/../includes/header.php';

?>

<!-- ==========================================
     STUDENT HERO SECTION
========================================== -->

<section class="m2-hero"
    style="background: linear-gradient(120deg, #102744, #1d426b) !important;">

    <div class="m2-hero-content">

        <span class="m2-label">
            CAREERCONNECT STUDENT PORTAL
        </span>

        <h1>
            Your Career Journey
            <span>Starts Here</span>
        </h1>

        <p>
            Create your student account, manage your academic profile,
            upload your CV and prepare for internship opportunities
            through CareerConnect.
        </p>

        <!-- REGISTER AND LOGIN BUTTONS -->

        <div class="m2-hero-buttons"
             style="display:flex !important; gap:15px; flex-wrap:wrap; align-items:center;">

            <a href="register.php"
               style="display:inline-block !important; visibility:visible !important; opacity:1 !important; background:#d5aa45; color:#102744; padding:13px 27px; text-decoration:none; border-radius:5px; font-weight:bold; font-size:14px;">
                Register Now
            </a>

            <a href="login.php"
               style="display:inline-block !important; visibility:visible !important; opacity:1 !important; background:transparent; color:#ffffff; padding:13px 27px; text-decoration:none; border:1px solid #ffffff; border-radius:5px; font-weight:bold; font-size:14px;">
                Login
            </a>

        </div>

    </div>


    <!-- HERO IMAGE -->

    <div class="m2-hero-image">

        <img
            src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=900&q=85"
            alt="University students studying together"
        >

    </div>

</section>


<!-- ==========================================
     STUDENT INFORMATION SECTION
========================================== -->

<section class="m2-info-section">

    <div class="m2-section-heading">

        <span>WHY JOIN CAREERCONNECT?</span>

        <h2>Build Your Professional Future</h2>

        <p>
            Create your student profile and get ready for your career journey.
        </p>

    </div>


    <div class="m2-dashboard-grid">

        <!-- CARD 01 -->

        <div class="m2-dashboard-card">

            <div class="m2-card-icon">01</div>

            <h3>Create Your Account</h3>

            <p>
                Register as a university student and create your
                personal CareerConnect account.
            </p>

        </div>


        <!-- CARD 02 -->

        <div class="m2-dashboard-card">

            <div class="m2-card-icon">02</div>

            <h3>Upload Your Profile and CV</h3>

            <p>
                Add your profile photo, university details and CV
                during student registration.
            </p>

        </div>


        <!-- CARD 03 -->

        <div class="m2-dashboard-card">

            <div class="m2-card-icon">03</div>

            <h3>Manage Your Profile</h3>

            <p>
                View and update your personal information and
                uploaded CV through your student dashboard.
            </p>

        </div>

    </div>

</section>


<?php

include __DIR__ . '/../includes/footer.php';

?>
