
<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';

m2_requireStudent();

$user_id = (int)$_SESSION['user_id'];

$profile = m2_getStudentProfile($conn, $user_id);

if (!$profile) {
    die("Student profile could not be found.");
}

$error = '';
$success = '';

$upload_dir = __DIR__ . '/uploads/';

if (!is_dir($upload_dir)) {
    if (!mkdir($upload_dir, 0755, true)) {
        die("Unable to create upload folder.");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $updates = [];
    $params = [];
    $types = '';

    /*
    =========================================
    PROFILE PHOTO UPLOAD
    =========================================
    */

    if (
        isset($_FILES['profile_photo']) &&
        $_FILES['profile_photo']['error'] !== UPLOAD_ERR_NO_FILE
    ) {

        $file = $_FILES['profile_photo'];

        if ($file['error'] !== UPLOAD_ERR_OK) {

            $error = "Profile photo upload failed.";

        } elseif ($file['size'] > 3 * 1024 * 1024) {

            $error = "Profile photo must be less than 3 MB.";

        } else {

            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($file['tmp_name']);

            $allowed_images = [
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/webp' => 'webp'
            ];

            if (!isset($allowed_images[$mime])) {

                $error = "Only JPG, PNG and WEBP photos are allowed.";

            } else {

                $filename = 'photo_' . $user_id . '_' .
                    bin2hex(random_bytes(8)) . '.' .
                    $allowed_images[$mime];

                $destination = $upload_dir . $filename;

                if (move_uploaded_file(
                    $file['tmp_name'],
                    $destination
                )) {

                    $updates[] = "profile_photo = ?";
                    $params[] = 'student/uploads/' . $filename;
                    $types .= 's';

                } else {

                    $error = "Could not save profile photo.";

                }
            }
        }
    }

    /*
    =========================================
    CV PDF UPLOAD
    =========================================
    */

    if (
        $error === '' &&
        isset($_FILES['cv_file']) &&
        $_FILES['cv_file']['error'] !== UPLOAD_ERR_NO_FILE
    ) {

        $file = $_FILES['cv_file'];

        if ($file['error'] !== UPLOAD_ERR_OK) {

            $error = "CV upload failed.";

        } elseif ($file['size'] > 5 * 1024 * 1024) {

            $error = "CV must be less than 5 MB.";

        } else {

            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($file['tmp_name']);

            if ($mime !== 'application/pdf') {

                $error = "Only PDF files are allowed for CV.";

            } else {

                $filename = 'cv_' . $user_id . '_' .
                    bin2hex(random_bytes(8)) . '.pdf';

                $destination = $upload_dir . $filename;

                if (move_uploaded_file(
                    $file['tmp_name'],
                    $destination
                )) {

                    $updates[] = "cv_path = ?";
                    $params[] = 'student/uploads/' . $filename;
                    $types .= 's';

                } else {

                    $error = "Could not save CV.";

                }
            }
        }
    }

    /*
    =========================================
    DATABASE UPDATE
    =========================================
    */

    if ($error === '' && !empty($updates)) {

        $sql = "UPDATE student_profiles SET " .
               implode(', ', $updates) .
               " WHERE user_id = ?";

        $types .= 'i';
        $params[] = $user_id;

        $stmt = mysqli_prepare($conn, $sql);

        if (!$stmt) {

            $error = "Database query preparation failed.";

        } else {

            $bind_values = [$types];

            foreach ($params as $key => &$value) {
                $bind_values[] = &$value;
            }

            $bind_result = call_user_func_array(
                [$stmt, 'bind_param'],
                $bind_values
            );

            if (!$bind_result) {

                $error = "Database parameter binding failed.";

            } elseif (mysqli_stmt_execute($stmt)) {

                mysqli_stmt_close($stmt);

                header("Location: upload-files.php?success=1");
                exit;

            } else {

                $error = "Database update failed.";

            }

            if ($stmt instanceof mysqli_stmt) {
                mysqli_stmt_close($stmt);
            }
        }

    } elseif ($error === '') {

        $error = "Please select a photo or CV to upload.";

    }
}

/*
=========================================
SUCCESS MESSAGE
=========================================
*/

if (isset($_GET['success'])) {
    $success = "Your files have been uploaded successfully.";
}

$page_css = "/University-Internship-and-Career-Portal-System/assets/css/student.css";

include __DIR__ . '/../includes/header.php';

?>

<section class="m2-upload-section">

    <div class="m2-new-profile-heading">

        <span>STUDENT DOCUMENTS</span>

        <h1>Upload Photo & CV</h1>

        <p>
            Keep your student profile and career documents updated.
        </p>

    </div>

    <div class="m2-upload-card">

        <?php if ($error !== ''): ?>

            <div class="m2-message-error">
                <?php echo m2_escape($error); ?>
            </div>

        <?php endif; ?>


        <?php if ($success !== ''): ?>

            <div class="m2-message-success">
                <?php echo m2_escape($success); ?>
            </div>

        <?php endif; ?>


        <form method="POST" enctype="multipart/form-data">

            <!-- PROFILE PHOTO -->

            <div class="m2-upload-field">

                <label for="profile_photo">
                    Profile Photo
                </label>

                <p>
                    Accepted formats: JPG, PNG, WEBP.
                    Maximum size: 3 MB.
                </p>

                <input
                    type="file"
                    name="profile_photo"
                    id="profile_photo"
                    accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                >

            </div>


            <!-- CV FILE -->

            <div class="m2-upload-field">

                <label for="cv_file">
                    Curriculum Vitae (CV)
                </label>

                <p>
                    Accepted format: PDF.
                    Maximum size: 5 MB.
                </p>

                <input
                    type="file"
                    name="cv_file"
                    id="cv_file"
                    accept=".pdf,application/pdf"
                >

            </div>


            <button
                type="submit"
                class="m2-dashboard-button"
            >
                Upload Selected Files
            </button>

        </form>


        <div class="m2-upload-back">

            <a href="index.php">
                ← Back to Dashboard
            </a>

        </div>

    </div>

</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
