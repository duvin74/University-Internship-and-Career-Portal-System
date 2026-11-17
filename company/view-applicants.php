
<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';

m3_requireCompany();

$page_css = "/University-Internship-and-Career-Portal-System/assets/css/company.css";

$company = m3_getCompany($conn, $_SESSION['user_id']);

if (!$company) {
    die("Company profile not found.");
}

$internship_id = isset($_GET['internship_id'])
    ? (int)$_GET['internship_id']
    : 0;

if ($internship_id <= 0) {
    header("Location: manage-internships.php");
    exit;
}

$internship = m3_getCompanyInternshipById(
    $conn,
    $internship_id,
    $company['company_id']
);

if (!$internship) {
    die("Internship not found or access denied.");
}

$applicants = m3_getInternshipApplicants(
    $conn,
    $internship_id,
    $company['company_id']
);

include __DIR__ . '/../includes/header.php';

?>

<section class="m3-dashboard m3-applicants-page">

    <h1>Student Applicants</h1>

    <h2>
        <?php echo m3_escape($internship['title']); ?>
    </h2>

    <p>
        Total Applicants:
        <strong><?php echo count($applicants); ?></strong>
    </p>

    <?php if (empty($applicants)): ?>

        <div class="m3-card">
            <p>No students have applied for this internship yet.</p>
        </div>

    <?php else: ?>

        <div class="m3-applicant-list">

            <?php foreach ($applicants as $student): ?>

                <div class="m3-applicant-card">

                    <h3>
                        <?php echo m3_escape($student['name']); ?>
                    </h3>

                    <p>
                        <strong>Email:</strong>
                        <?php echo m3_escape($student['email']); ?>
                    </p>

                    <p>
                        <strong>University:</strong>
                        <?php echo m3_escape($student['university'] ?: 'Not provided'); ?>
                    </p>

                    <p>
                        <strong>Course:</strong>
                        <?php echo m3_escape($student['course'] ?: 'Not provided'); ?>
                    </p>

                    <p>
                        <strong>Skills:</strong>
                        <?php echo nl2br(m3_escape($student['skills'] ?: 'Not provided')); ?>
                    </p>

                    <p>
                        <strong>Application Date:</strong>
                        <?php
                        echo m3_escape(
                            $student['application_date'] ?: 'Not available'
                        );
                        ?>
                    </p>

                    <p>
                        <strong>CV:</strong>

                        <?php if (!empty($student['cv_path'])): ?>

                            <span class="m3-cv-available">
                                CV Uploaded
                            </span>

                        <?php else: ?>

                            <span>CV Not Uploaded</span>

                        <?php endif; ?>

                    </p>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

    <a href="manage-internships.php" class="m3-secondary-btn">
        Back to Manage Internships
    </a>

</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
