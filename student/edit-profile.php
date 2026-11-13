
<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';

m2_requireStudent();

$user_id = (int) $_SESSION['user_id'];

$profile = m2_getStudentProfile($conn, $user_id);

if (!$profile) {
    die("Student profile not found.");
}

$error = "";
$success = "";

$name = $profile['name'];
$university = $profile['university'] ?? '';
$course = $profile['course'] ?? '';
$skills = $profile['skills'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name'] ?? '');
    $university = trim($_POST['university'] ?? '');
    $course = trim($_POST['course'] ?? '');
    $skills = trim($_POST['skills'] ?? '');

    if ($name === '' || $university === '' || $course === '') {

        $error = "Please complete all required fields.";

    } else {

        mysqli_begin_transaction($conn);

        try {

            $user_sql = "UPDATE Users
                         SET name = ?
                         WHERE user_id = ?
                         AND role = 'Student'";

            $user_stmt = mysqli_prepare($conn, $user_sql);

            if (!$user_stmt) {
                throw new Exception("Unable to prepare user update.");
            }

            mysqli_stmt_bind_param(
                $user_stmt,
                "si",
                $name,
                $user_id
            );

            if (!mysqli_stmt_execute($user_stmt)) {
                throw new Exception("Unable to update name.");
            }

            mysqli_stmt_close($user_stmt);

            $profile_sql = "UPDATE student_profiles
                            SET university = ?,
                                course = ?,
                                skills = ?
                            WHERE user_id = ?";

            $profile_stmt = mysqli_prepare($conn, $profile_sql);

            if (!$profile_stmt) {
                throw new Exception("Unable to prepare profile update.");
            }

            mysqli_stmt_bind_param(
                $profile_stmt,
                "sssi",
                $university,
                $course,
                $skills,
                $user_id
            );

            if (!mysqli_stmt_execute($profile_stmt)) {
                throw new Exception("Unable to update profile.");
            }

            mysqli_stmt_close($profile_stmt);

            mysqli_commit($conn);

            $_SESSION['user_name'] = $name;

            $success = "Your profile has been updated successfully.";

        } catch (Throwable $e) {

            mysqli_rollback($conn);

            $error = "Unable to update your profile. Please try again.";
        }
    }
}

$page_css = "/University-Internship-and-Career-Portal-System/assets/css/student.css";

include __DIR__ . '/../includes/header.php';

?>

<section class="m2-form-section">

    <div class="m2-form-card">

        <div class="m2-section-heading">
            <span>ACCOUNT SETTINGS</span>
            <h2>Edit Student Profile</h2>
            <p>Update your personal and academic information.</p>
        </div>

        <?php if ($error !== "") { ?>
            <div class="m2-message m2-error">
                <?php echo m2_escape($error); ?>
            </div>
        <?php } ?>

        <?php if ($success !== "") { ?>
            <div class="m2-message m2-success">
                <?php echo m2_escape($success); ?>
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
            <textarea name="skills" rows="4"><?php echo m2_escape($skills); ?></textarea>

            <button type="submit" class="m2-btn m2-btn-gold m2-submit">
                Save Changes
            </button>

        </form>

        <p class="m2-form-bottom">
            <a href="profile.php">Back to My Profile</a>
        </p>

    </div>

</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>