
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
    $name = trim($_POST['name'] ?? '');
    $company_name = trim($_POST['company_name'] ?? '');
    $website = trim($_POST['website'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($name === '' || $company_name === '') {
        $error = "Name and company name are required.";
    } elseif ($website !== '' &&
        !filter_var($website, FILTER_VALIDATE_URL)) {
        $error = "Enter a valid website URL.";
    } else {
        mysqli_begin_transaction($conn);

        try {
            $stmt = mysqli_prepare(
                $conn,
                "UPDATE Users SET name = ? WHERE user_id = ? AND role = 'Company'"
            );
            mysqli_stmt_bind_param($stmt, "si", $name, $_SESSION['user_id']);

            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Unable to update contact name.");
            }
            mysqli_stmt_close($stmt);

            $stmt = mysqli_prepare(
                $conn,
                "UPDATE companies
                 SET company_name = ?, website = ?, description = ?
                 WHERE user_id = ?"
            );
            mysqli_stmt_bind_param(
                $stmt, "sssi",
                $company_name, $website, $description, $_SESSION['user_id']
            );

            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Unable to update company details.");
            }
            mysqli_stmt_close($stmt);

            mysqli_commit($conn);

            $_SESSION['user_name'] = $name;
            $_SESSION['company_name'] = $company_name;

            $success = "Company profile updated successfully.";
            $company = m3_getCompany($conn, $_SESSION['user_id']);

        } catch (Throwable $e) {
            mysqli_rollback($conn);
            $error = $e->getMessage();
        }
    }
}

include __DIR__ . '/../includes/header.php';
?>

<section class="m3-form-section">
    <div class="m3-form-box">
        <h2>Edit Company Profile</h2>

        <?php if ($error): ?>
            <div class="m3-error"><?php echo m3_escape($error); ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="m3-success"><?php echo m3_escape($success); ?></div>
        <?php endif; ?>

        <form method="POST">
            <label>Contact Person Name</label>
            <input type="text" name="name"
                   value="<?php echo m3_escape($company['name']); ?>" required>

            <label>Company Name</label>
            <input type="text" name="company_name"
                   value="<?php echo m3_escape($company['company_name']); ?>" required>

            <label>Website</label>
            <input type="url" name="website"
                   value="<?php echo m3_escape($company['website']); ?>">

            <label>Description</label>
            <textarea name="description" rows="5"><?php echo m3_escape($company['description']); ?></textarea>

            <button type="submit" class="m3-btn">Save Changes</button>
            <a href="profile.php" class="m3-secondary-btn">Cancel</a>
        </form>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
