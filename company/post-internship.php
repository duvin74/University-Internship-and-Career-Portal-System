
<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';

m3_requireCompany();

$page_css = "/University-Internship-and-Career-Portal-System/assets/css/company.css";

$company = m3_getCompany($conn, $_SESSION['user_id']);

if (!$company) {
    die("Company profile not found.");
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($company['verification_status'] !== 'Verified') {
        $error = "Your company must be verified before posting internships.";
    } else {
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $requirements = trim($_POST['requirements'] ?? '');
        $deadline = trim($_POST['deadline'] ?? '');

        if ($title === '' || $description === '' || $deadline === '') {
            $error = "Please complete all required fields.";
        } elseif ($deadline < date('Y-m-d')) {
            $error = "Deadline must be today or a future date.";
        } else {
            $stmt = mysqli_prepare(
                $conn,
                "INSERT INTO internships
                 (company_id, title, description, requirements, deadline)
                 VALUES (?, ?, ?, ?, ?)"
            );

            mysqli_stmt_bind_param(
                $stmt, "issss",
                $company['company_id'],
                $title,
                $description,
                $requirements,
                $deadline
            );

            if (mysqli_stmt_execute($stmt)) {
                $success = "Internship posted successfully.";
            } else {
                $error = "Unable to post internship. Please try again.";
            }

            mysqli_stmt_close($stmt);
        }
    }
}

include __DIR__ . '/../includes/header.php';
?>

<section class="m3-form-section">
    <div class="m3-form-box">
        <h2>Post Internship</h2>

        <?php if ($error): ?>
            <div class="m3-error"><?php echo m3_escape($error); ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="m3-success"><?php echo m3_escape($success); ?></div>
        <?php endif; ?>

        <?php if ($company['verification_status'] !== 'Verified'): ?>
            <div class="m3-error">
                Your company verification is still pending.
                You can post internships after admin verification.
            </div>
        <?php else: ?>
            <form method="POST">
                <label>Internship Title *</label>
                <input type="text" name="title" required>

                <label>Description *</label>
                <textarea name="description" rows="5" required></textarea>

                <label>Requirements</label>
                <textarea name="requirements" rows="4"></textarea>

                <label>Application Deadline *</label>
                <input type="date" name="deadline"
                       min="<?php echo date('Y-m-d'); ?>" required>

                <button type="submit" class="m3-btn">Publish Internship</button>
            </form>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
