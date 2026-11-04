
<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';

$page_css = "/University-Internship-and-Career-Portal-System/assets/css/member3.css";

$error = '';

if (m3_isCompanyLoggedIn()) {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = mysqli_prepare(
        $conn,
        "SELECT user_id, name, email, password
         FROM Users
         WHERE email = ? AND role = 'Company'
         LIMIT 1"
    );

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if ($user && password_verify($password, $user['password'])) {
        $company = m3_getCompany($conn, $user['user_id']);

        if (!$company) {
            $error = "Company profile was not found. Please contact the administrator.";
        } elseif ($company['verification_status'] !== 'Verified') {
            $error = "Your company account is awaiting admin verification.";
        } else {
            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = 'Company';
            $_SESSION['role'] = 'Company';
            $_SESSION['company_name'] = $company['company_name'];

            header("Location: dashboard.php");
            exit;
        }
    } else {
        $error = "Invalid company email or password.";
    }
}

include __DIR__ . '/../includes/header.php';
?>

<section class="m3-form-section">
    <div class="m3-form-box">
        <h2>Company Login</h2>
        <p>Login to manage your company account.</p>

        <?php if ($error): ?>
            <div class="m3-error"><?php echo m3_escape($error); ?></div>
        <?php endif; ?>

        <form method="POST">
            <label>Email</label>
            <input type="email" name="email" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <button type="submit" class="m3-btn">Login</button>
        </form>

        <p>New company? <a href="register.php">Register here</a></p>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
