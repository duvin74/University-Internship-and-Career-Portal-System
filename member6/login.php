<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';

if (m6_isAdminLoggedIn()) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $sql = "SELECT user_id, name, email, password
            FROM Users
            WHERE email = ? AND role = 'Admin'
            LIMIT 1";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $admin = mysqli_fetch_assoc($result);

        mysqli_stmt_close($stmt);

        if ($admin && password_verify($password, $admin['password'])) {

            session_regenerate_id(true);

            $_SESSION['user_id'] = $admin['user_id'];
            $_SESSION['user_name'] = $admin['name'];
            $_SESSION['user_role'] = 'Admin';
            $_SESSION['role'] = 'Admin';

            header("Location: dashboard.php");
            exit;

        } else {
            $error = "Invalid Admin email or password.";
        }

    } else {
        $error = "Unable to process login.";
    }
}

$page_css = "/University-Internship-and-Career-Portal-System/assets/css/member6.css";

include __DIR__ . '/../includes/header.php';
?>

<section class="m6-section">

    <div class="m6-form">

        <h2>Admin Login</h2>

        <p>Sign in to access the administration dashboard.</p>

        <?php if ($error): ?>
            <div class="m6-error">
                <?php echo m6_escape($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST">

            <label>Email Address</label>
            <input type="email" name="email" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <button type="submit" class="m6-btn">
                Login
            </button>

        </form>

    </div>

</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>