
<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';

$page_css = "/University-Internship-and-Career-Portal-System/assets/css/company.css";

$errors = [];
$success = '';

$name = '';
$email = '';
$company_name = '';
$website = '';
$description = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $company_name = trim($_POST['company_name'] ?? '');
    $website = trim($_POST['website'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($name === '' || $email === '' || $password === '' ||
        $company_name === '') {
        $errors[] = "Please fill in all required fields.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Enter a valid email address.";
    }

    if (strlen($password) < 8) {
        $errors[] = "Password must contain at least 8 characters.";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    if ($website !== '' &&
        !filter_var($website, FILTER_VALIDATE_URL)) {
        $errors[] = "Enter a valid website URL including https://.";
    }

    if (!$errors) {
        $check = mysqli_prepare($conn, "SELECT user_id FROM Users WHERE email = ?");
        mysqli_stmt_bind_param($check, "s", $email);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        if (mysqli_stmt_num_rows($check) > 0) {
            $errors[] = "This email is already registered.";
        }

        mysqli_stmt_close($check);
    }

    if (!$errors) {
        mysqli_begin_transaction($conn);

        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $role = 'Company';

            $stmt = mysqli_prepare(
                $conn,
                "INSERT INTO Users (name, email, password, role)
                 VALUES (?, ?, ?, ?)"
            );

            if (!$stmt) {
                throw new Exception("Unable to prepare user registration.");
            }

            mysqli_stmt_bind_param(
                $stmt, "ssss", $name, $email, $hashedPassword, $role
            );

            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Unable to create user account.");
            }

            $user_id = mysqli_insert_id($conn);
            mysqli_stmt_close($stmt);

            $status = 'Pending';

            $stmt = mysqli_prepare(
                $conn,
                "INSERT INTO companies
                 (user_id, company_name, website, description, verification_status)
                 VALUES (?, ?, ?, ?, ?)"
            );

            if (!$stmt) {
                throw new Exception("Unable to prepare company registration.");
            }

            mysqli_stmt_bind_param(
                $stmt, "issss",
                $user_id, $company_name, $website, $description, $status
            );

            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Unable to save company details.");
            }

            mysqli_stmt_close($stmt);

            mysqli_commit($conn);
            $success = "Company registration successful. Your account is waiting for admin verification.";

            $name = $email = $company_name = $website = $description = '';

        } catch (Throwable $e) {
            mysqli_rollback($conn);
            $errors[] = $e->getMessage();
        }
    }
}

include __DIR__ . '/../includes/header.php';
?>

<section class="m3-form-section">
    <div class="m3-form-box">
        <h2>Company Registration</h2>
        <p>Create your CareerConnect company account.</p>

        <?php if ($success): ?>
            <div class="m3-success"><?php echo m3_escape($success); ?></div>
            <a href="login.php" class="m3-btn">Go to Login</a>
        <?php endif; ?>

        <?php foreach ($errors as $error): ?>
            <div class="m3-error"><?php echo m3_escape($error); ?></div>
        <?php endforeach; ?>

        <form method="POST">
            <label>Contact Person Name *</label>
            <input type="text" name="name" value="<?php echo m3_escape($name); ?>" required>

            <label>Email *</label>
            <input type="email" name="email" value="<?php echo m3_escape($email); ?>" required>

            <label>Company Name *</label>
            <input type="text" name="company_name" value="<?php echo m3_escape($company_name); ?>" required>

            <label>Website</label>
            <input type="url" name="website" value="<?php echo m3_escape($website); ?>" placeholder="https://example.com">

            <label>Company Description</label>
            <textarea name="description" rows="4"><?php echo m3_escape($description); ?></textarea>

            <label>Password *</label>
            <input type="password" name="password" required>

            <label>Confirm Password *</label>
            <input type="password" name="confirm_password" required>

            <button type="submit" class="m3-btn">Register Company</button>
        </form>

        <p>Already registered? <a href="login.php">Login here</a></p>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
