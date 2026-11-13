
<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';

if (m2_isStudentLoggedIn()) {
    header("Location: dashboard.php");
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

    $photo_path = "";
    $cv_path = "";
    $saved_files = [];

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

    } elseif (
        !isset($_FILES['profile_photo']) ||
        $_FILES['profile_photo']['error'] !== UPLOAD_ERR_OK
    ) {
        $error = "Please upload your profile photo.";

    } elseif (
        !isset($_FILES['cv_file']) ||
        $_FILES['cv_file']['error'] !== UPLOAD_ERR_OK
    ) {
        $error = "Please upload your CV.";

    } else {

        $check_sql = "SELECT user_id FROM Users WHERE email = ?";
        $check_stmt = mysqli_prepare($conn, $check_sql);

        if (!$check_stmt) {
            $error = "Something went wrong. Please try again.";
        } else {

            mysqli_stmt_bind_param($check_stmt, "s", $email);
            mysqli_stmt_execute($check_stmt);

            $check_result = mysqli_stmt_get_result($check_stmt);

            if (mysqli_num_rows($check_result) > 0) {

                $error = "This email is already registered.";

            } else {

                // Validate profile photo
                $photo_tmp = $_FILES['profile_photo']['tmp_name'];
                $photo_size = $_FILES['profile_photo']['size'];

                $photo_mime = mime_content_type($photo_tmp);

                $allowed_photos = [
                    'image/jpeg' => 'jpg',
                    'image/png' => 'png',
                    'image/webp' => 'webp'
                ];

                if (!isset($allowed_photos[$photo_mime])) {
                    $error = "Profile photo must be JPG, PNG or WEBP.";
                } elseif ($photo_size > 2 * 1024 * 1024) {
                    $error = "Profile photo must be smaller than 2 MB.";
                }

                // Validate CV
                $cv_tmp = $_FILES['cv_file']['tmp_name'];
                $cv_size = $_FILES['cv_file']['size'];
                $cv_mime = mime_content_type($cv_tmp);

                if ($error === "" && $cv_mime !== 'application/pdf') {
                    $error = "CV must be uploaded as a PDF file.";
                } elseif ($error === "" && $cv_size > 5 * 1024 * 1024) {
                    $error = "CV must be smaller than 5 MB.";
                }

                if ($error === "") {

                    $upload_dir = __DIR__ . '/uploads/';

                    if (
                        !is_dir($upload_dir) &&
                        !mkdir($upload_dir, 0755, true)
                    ) {
                        $error = "Unable to create upload folder.";
                    }

                    if ($error === "") {

                        $photo_filename = 'photo_' .
                            bin2hex(random_bytes(16)) .
                            '.' . $allowed_photos[$photo_mime];

                        $cv_filename = 'cv_' .
                            bin2hex(random_bytes(16)) . '.pdf';

                        $photo_destination = $upload_dir . $photo_filename;
                        $cv_destination = $upload_dir . $cv_filename;

                        if (!move_uploaded_file($photo_tmp, $photo_destination)) {

                            $error = "Unable to upload profile photo.";

                        } else {

                            $saved_files[] = $photo_destination;
                            $photo_path = 'student/uploads/' . $photo_filename;

                            if (!move_uploaded_file($cv_tmp, $cv_destination)) {

                                $error = "Unable to upload CV.";

                            } else {

                                $saved_files[] = $cv_destination;
                                $cv_path = 'student/uploads/' . $cv_filename;

                            }
                        }
                    }
                }

                if ($error === "") {

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

                        if (!$user_stmt) {
                            throw new Exception("Unable to prepare account.");
                        }

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
                                        (user_id, university, course, skills,
                                         cv_path, profile_photo)
                                        VALUES (?, ?, ?, ?, ?, ?)";

                        $profile_stmt = mysqli_prepare($conn, $profile_sql);

                        if (!$profile_stmt) {
                            throw new Exception("Unable to prepare profile.");
                        }

                        mysqli_stmt_bind_param(
                            $profile_stmt,
                            "isssss",
                            $user_id,
                            $university,
                            $course,
                            $skills,
                            $cv_path,
                            $photo_path
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

                    if (isset($user_stmt) && $user_stmt) {
                        mysqli_stmt_close($user_stmt);
                    }

                    if (isset($profile_stmt) && $profile_stmt) {
                        mysqli_stmt_close($profile_stmt);
                    }
                }

                // Remove uploaded files if registration fails
                if ($error !== "") {
                    foreach ($saved_files as $file) {
                        if (file_exists($file)) {
                            unlink($file);
                        }
                    }
                }
            }

            mysqli_stmt_close($check_stmt);
        }
    }
}

$page_css = "/University-Internship-and-Career-Portal-System/assets/css/student.css";

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

        <form method="POST" action="" enctype="multipart/form-data">

            <label>Full Name *</label>
            <input type="text" name="name"
                value="<?php echo m2_escape($name); ?>" required>

            <label>Email Address *</label>
            <input type="email" name="email"
                value="<?php echo m2_escape($email); ?>" required>

            <label>Password *</label>
            <input type="password" name="password" required>

            <label>Confirm Password *</label>
            <input type="password" name="confirm_password" required>

            <label>University *</label>
            <input type="text" name="university"
                value="<?php echo m2_escape($university); ?>" required>

            <label>Course / Degree *</label>
            <input type="text" name="course"
                value="<?php echo m2_escape($course); ?>" required>

            <label>Skills</label>
            <textarea name="skills" rows="3"
                placeholder="Example: PHP, HTML, CSS, JavaScript"><?php
                echo m2_escape($skills);
            ?></textarea>

            <label>Profile Photo *</label>
            <input type="file" name="profile_photo"
                accept=".jpg,.jpeg,.png,.webp" required>

            <label>Upload CV (PDF) *</label>
            <input type="file" name="cv_file"
                accept=".pdf,application/pdf" required>

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
