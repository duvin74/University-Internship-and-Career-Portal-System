
<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';

if (m2_isStudentLoggedIn()) {
    header("Location: index.php");
    exit;
}

$error = "";
$email = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {

        $error = "Please enter your email and password.";

    } else {

        $sql = "SELECT user_id, name, email, password, role
                FROM Users
                WHERE email = ?
                AND role = 'Student'
                LIMIT 1";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        if ($user && password_verify($password, $user['password'])) {

            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];

            header("Location: index.php");
            exit;

        } else {

            $error = "Invalid email or password.";

        }

        mysqli_stmt_close($stmt);
    }
}

$page_css = "/University-Internship-and-Career-Portal-System/assets/css/member2.css";

include __DIR__ . '/../includes/header.php';

?>

<section class="m2-form-section">

    <div class="m2-form-card">

        <div class="m2-section-heading">
            <span>WELCOME BACK</span>
            <h2>Student Login</h2>
            <p>Login to your CareerConnect account.</p>
        </div>

        <?php if ($error !== "") { ?>
            <div class="m2-message m2-error">
                <?php echo m2_escape($error); ?>
            </div>
        <?php } ?>

        <form method="POST" action="">

            <label>Email Address</label>
            <input
                type="email"
                name="email"
                value="<?php echo m2_escape($email); ?>"
                required
            >

            <label>Password</label>
            <input
                type="password"
                name="password"
                required
            >

            <button type="submit" class="m2-btn m2-btn-gold m2-submit">
                Login
            </button>

        </form>

        <p class="m2-form-bottom">
            Don't have an account?
            <a href="register.php">Register here</a>
        </p>

    </div>

</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
