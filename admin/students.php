
<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';

m6_requireAdmin();

$message = "";
$messageType = "";

// DELETE STUDENT
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_student"])) {

    $user_id = (int)($_POST["user_id"] ?? 0);

    if ($user_id > 0) {

        $checkStudent = mysqli_prepare(
            $conn,
            "SELECT student_id FROM student_profiles WHERE user_id = ?"
        );

        mysqli_stmt_bind_param($checkStudent, "i", $user_id);
        mysqli_stmt_execute($checkStudent);

        $studentResult = mysqli_stmt_get_result($checkStudent);
        $student = mysqli_fetch_assoc($studentResult);

        if (!$student) {

            $message = "Student not found.";
            $messageType = "error";

        } else {

            $student_id = (int)$student["student_id"];

            // CHECK APPLICATIONS
            $checkApplications = mysqli_prepare(
                $conn,
                "SELECT application_id FROM applications WHERE student_id = ? LIMIT 1"
            );

            mysqli_stmt_bind_param($checkApplications, "i", $student_id);
            mysqli_stmt_execute($checkApplications);

            $applicationResult = mysqli_stmt_get_result($checkApplications);

            // CHECK EXPERIENCES
            $checkExperiences = mysqli_prepare(
                $conn,
                "SELECT experience_id FROM experiences WHERE student_id = ? LIMIT 1"
            );

            mysqli_stmt_bind_param($checkExperiences, "i", $student_id);
            mysqli_stmt_execute($checkExperiences);

            $experienceResult = mysqli_stmt_get_result($checkExperiences);

            if (
                mysqli_num_rows($applicationResult) > 0 ||
                mysqli_num_rows($experienceResult) > 0
            ) {

                $message = "This student cannot be deleted because related applications or experiences exist.";
                $messageType = "error";

            } else {

                mysqli_begin_transaction($conn);

                try {

                    // DELETE STUDENT PROFILE
                    $deleteProfile = mysqli_prepare(
                        $conn,
                        "DELETE FROM student_profiles WHERE student_id = ?"
                    );

                    mysqli_stmt_bind_param($deleteProfile, "i", $student_id);
                    mysqli_stmt_execute($deleteProfile);

                    // DELETE USER ACCOUNT
                    $deleteUser = mysqli_prepare(
                        $conn,
                        "DELETE FROM Users WHERE user_id = ? AND role = 'Student'"
                    );

                    mysqli_stmt_bind_param($deleteUser, "i", $user_id);
                    mysqli_stmt_execute($deleteUser);

                    if (mysqli_stmt_affected_rows($deleteUser) !== 1) {
                        throw new Exception("Student account could not be deleted.");
                    }

                    mysqli_commit($conn);

                    $message = "Student deleted successfully!";
                    $messageType = "success";

                } catch (Exception $e) {

                    mysqli_rollback($conn);

                    $message = "Unable to delete this student. Related records may exist.";
                    $messageType = "error";
                }
            }
        }

    } else {

        $message = "Invalid student selected.";
        $messageType = "error";
    }
}

// FETCH STUDENTS
$sql = "SELECT u.user_id, u.name, u.email,
               s.university, s.course
        FROM Users u
        LEFT JOIN student_profiles s ON u.user_id = s.user_id
        WHERE u.role = 'Student'
        ORDER BY u.user_id DESC";

$result = mysqli_query($conn, $sql);

$page_css = "/University-Internship-and-Career-Portal-System/assets/css/admin.css";

include __DIR__ . '/../includes/header.php';
?>

<section class="m6-section">

    <div class="m6-container">

        <h1>Manage Students</h1>

        <p class="m6-subtitle">
            View and manage registered students.
        </p>

        <?php if ($message !== ""): ?>

            <div class="m6-message <?php echo m6_escape($messageType); ?>">
                <?php echo m6_escape($message); ?>
            </div>

        <?php endif; ?>

        <div class="m6-table-wrapper">

            <table class="m6-table">

                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>University</th>
                        <th>Course</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if ($result && mysqli_num_rows($result) > 0): ?>

                        <?php while ($student = mysqli_fetch_assoc($result)): ?>

                            <tr>

                                <td>
                                    <?php echo m6_escape($student['name']); ?>
                                </td>

                                <td>
                                    <?php echo m6_escape($student['email']); ?>
                                </td>

                                <td>
                                    <?php echo m6_escape($student['university'] ?? 'Not provided'); ?>
                                </td>

                                <td>
                                    <?php echo m6_escape($student['course'] ?? 'Not provided'); ?>
                                </td>

                                <td>

                                    <div class="m6-action-buttons">

                                        <!-- VIEW BUTTON -->
                                        <a
                                            class="m6-view-button"
                                            href="student-details.php?id=<?php echo (int)$student['user_id']; ?>">
                                            View
                                        </a>

                                        <!-- DELETE BUTTON -->
                                        <form
                                            method="POST"
                                            class="m6-confirm"
                                            data-confirm-message="Are you sure you want to delete this student?">

                                            <input
                                                type="hidden"
                                                name="user_id"
                                                value="<?php echo (int)$student['user_id']; ?>">

                                            <button
                                                type="submit"
                                                name="delete_student"
                                                value="1"
                                                class="m6-delete-button">
                                                Delete
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="5">No students registered yet.</td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
