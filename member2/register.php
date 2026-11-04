
<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';

if (m2_isStudentLoggedIn()) {
    header("Location: index.php");
    exit;
}

$error = "";
$success = "";

$name = "";
$email = "";
$university = "";
$course = "";
$skills = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $university = trim($_POST['university'] ?? '');
    $course = trim($_POST['course'] ?? '');
    $skills = trim($_POST['skills'] ?? '');

    if (
        $name === '' ||
        $email === '' ||
        $password === '' ||
        $confirm_password === '' ||
        $university === '' ||
        $course === ''
    ) {
        $error = "Please fill in all required fields.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";

    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";

    } elseif (strlen($password) < 8) {
        $error = "Password must contain at least 8 characters.";

    } else {

        $check_sql = "SELECT user_id FROM Users WHERE email = ?";
        $check_stmt = mysqli_prepare($conn, $check_sql);

        mysqli_stmt_bind_param($check_stmt, "s", $email);
        mysqli_stmt_execute($check_stmt);

        $check_result = mysqli_stmt_get_result($check_stmt);

        if (mysqli_num_rows($check_result) > 0) {

            $error = "This email is already registered.";

        } else {

            mysqli_begin_transaction($conn);

            try {

                $hashed_password = password_hash(
                    $password,
                    PASSWORD_DEFAULT
                );

                $role = "Student";

                $user_sql = "INSERT INTO Users
                            (name, email, password, role)
                            VALUES (?, ?, ?, ?)";

                $user_stmt = mysqli_prepare($conn, $user_sql);

                mysqli_stmt_bind_param(
                    $user_stmt,
                    "ssss",
                    $name,
                    $email,
                    $hashed_password,
                    $role
                );

                if (!mysqli_stmt_execute($user_stmt)) {
                    throw new Exception("Unable to create student account.");
                }

                $user_id = mysqli_insert_id($conn);

                $profile_sql = "INSERT INTO student_profiles
                                (user_id, university, course, skills)
                                VALUES (?, ?, ?, ?)";

                $profile_stmt = mysqli_prepare($conn, $profile_sql);

                mysqli_stmt_bind_param(
                    $profile_stmt,
                    "isss",
                    $user_id,
                    $university,
                    $course,
                    $skills
                );

                if (!mysqli_stmt_execute($profile_stmt)) {
                    throw new Exception("Unable to create student profile.");
                }

                mysqli_commit($conn);

                $success = "Registration successful! You can now log in.";

                $name = "";
                $email = "";
                $university = "";
                $course = "";
                $skills = "";

            } catch (Throwable $e) {

                mysqli_rollback($conn);
                $error = "Registration failed. Please try again.";

            }
        }

        mysqli_stmt_close($check_stmt);
    }
}

$page_css = "/University-Internship-and-Career-Portal-System/assets/css/member2.css";

include __DIR__ . '/../includes/header.php';

?>

<section class="m2-form-section">

    <div class="m2-form-card">

        <div class="m2-section-heading">
            <span>JOIN CAREERCONNECT</span>
            <h2>Student Registration</h2>
            <p>Create your student account.</p>
        </div>

        <?php if ($error !== "") { ?>
            <div class="m2-message m2-error">
                <?php echo m2_escape($error); ?>
            </div>
        <?php } ?>

        <?php if ($success !== "") { ?>
            <div class="m2-message m2-success">
                <?php echo m2_escape($success); ?>
                <br>
                <a href="login.php">Go to Login</a>
            </div>
        <?php } ?>

        <form method="POST" action="">

            <label>Full Name *</label>
            <input
                type="text"
                name="name"
                value="<?php echo m2_escape($name); ?>"
                required
            >

            <label>Email Address *</label>
            <input
                type="email"
                name="email"
                value="<?php echo m2_escape($email); ?>"
                required
            >

            <label>Password *</label>
            <input
                type="password"
                name="password"
                required
            >

            <label>Confirm Password *</label>
            <input
                type="password"
                name="confirm_password"
                required
            >

            <label>University *</label>
            <input
                type="text"
                name="university"
                value="<?php echo m2_escape($university); ?>"
                required
            >

            <label>Course / Degree *</label>
            <input
                type="text"
                name="course"
                value="<?php echo m2_escape($course); ?>"
                required
            >

            <label>Skills</label>
            <textarea
                name="skills"
                rows="3"
                placeholder="Example: PHP, HTML, CSS, JavaScript"
            ><?php echo m2_escape($skills); ?></textarea>

            <button type="submit" class="m2-btn m2-btn-gold m2-submit">
                Create Account
            </button>

        </form>

        <p class="m2-form-bottom">
            Already have an account?
            <a href="login.php">Login here</a>
        </p>

    </div>

</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
