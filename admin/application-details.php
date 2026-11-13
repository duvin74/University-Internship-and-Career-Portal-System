
<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';

m6_requireAdmin();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$applicationId = (int)($_GET['id'] ?? 0);

$message = "";
$messageType = "";

// CSRF Token
if (empty($_SESSION['application_csrf_token'])) {
    $_SESSION['application_csrf_token'] = bin2hex(random_bytes(32));
}

// Accept or Reject Application
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $action = $_POST['application_action'] ?? '';
    $token = $_POST['csrf_token'] ?? '';

    if (
        !hash_equals(
            $_SESSION['application_csrf_token'],
            $token
        )
    ) {

        $message = "Invalid request. Please try again.";
        $messageType = "error";

    } elseif (!in_array($action, ['Accepted', 'Rejected'], true)) {

        $message = "Invalid application action.";
        $messageType = "error";

    } else {

        // Only Pending applications can be updated
        $update = mysqli_prepare(
            $conn,
            "UPDATE applications
             SET status = ?
             WHERE application_id = ?
             AND status = 'Pending'"
        );

        mysqli_stmt_bind_param(
            $update,
            "si",
            $action,
            $applicationId
        );

        if (mysqli_stmt_execute($update)) {

            if (mysqli_stmt_affected_rows($update) > 0) {

                $message = "Application status updated successfully to " . $action . ".";
                $messageType = "success";

            } else {

                $message = "This application is already processed or does not exist.";
                $messageType = "error";
            }

        } else {

            $message = "Unable to update application status.";
            $messageType = "error";
        }

        mysqli_stmt_close($update);
    }
}

// Get Application Details
$sql = "SELECT
            a.application_id,
            a.application_date,
            a.status,
            u.name AS student_name,
            u.email,
            i.title AS internship_title,
            c.company_name
        FROM applications a
        INNER JOIN student_profiles s
            ON a.student_id = s.student_id
        INNER JOIN Users u
            ON s.user_id = u.user_id
        INNER JOIN internships i
            ON a.internship_id = i.internship_id
        INNER JOIN companies c
            ON i.company_id = c.company_id
        WHERE a.application_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $applicationId
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$application = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

$page_css = "/University-Internship-and-Career-Portal-System/assets/css/admin.css";

include __DIR__ . '/../includes/header.php';
?>

<section class="m6-section">

    <div class="m6-container">

        <h1>Application Details</h1>

        <?php if ($message !== ""): ?>

            <div class="m6-message <?php echo m6_escape($messageType); ?>">
                <?php echo m6_escape($message); ?>
            </div>

        <?php endif; ?>

        <?php if ($application): ?>

            <div class="m6-details-card">

                <p>
                    <strong>Application ID:</strong>
                    <?php echo (int)$application['application_id']; ?>
                </p>

                <p>
                    <strong>Student:</strong>
                    <?php echo m6_escape($application['student_name']); ?>
                </p>

                <p>
                    <strong>Email:</strong>
                    <?php echo m6_escape($application['email']); ?>
                </p>

                <p>
                    <strong>Internship:</strong>
                    <?php echo m6_escape($application['internship_title']); ?>
                </p>

                <p>
                    <strong>Company:</strong>
                    <?php echo m6_escape($application['company_name']); ?>
                </p>

                <p>
                    <strong>Applied Date:</strong>
                    <?php echo m6_escape($application['application_date']); ?>
                </p>

                <p>
                    <strong>Status:</strong>

                    <span class="m6-status">
                        <?php echo m6_escape($application['status']); ?>
                    </span>

                </p>

                <?php if ($application['status'] === 'Pending'): ?>

                    <hr>

                    <h3>Review Application</h3>

                    <p>
                        Please review the student's application
                        and choose an appropriate action.
                    </p>

                    <form method="POST">

                        <input
                            type="hidden"
                            name="csrf_token"
                            value="<?php echo m6_escape($_SESSION['application_csrf_token']); ?>"
                        >

                        <div class="m6-action-buttons">

                            <button
                                type="submit"
                                name="application_action"
                                value="Accepted"
                                class="m6-view-button"
                                onclick="return confirm('Are you sure you want to accept this application?');"
                            >
                                Accept Application
                            </button>

                            <button
                                type="submit"
                                name="application_action"
                                value="Rejected"
                                class="m6-delete-button"
                                onclick="return confirm('Are you sure you want to reject this application?');"
                            >
                                Reject Application
                            </button>

                        </div>

                    </form>

                <?php else: ?>

                    <p>
                        This application has already been processed.
                    </p>

                <?php endif; ?>

                <br>

                <a href="applications.php" class="m6-back-link">
                    Back to Applications
                </a>

            </div>

        <?php else: ?>

            <div class="m6-message error">
                Application not found.
            </div>

            <a href="applications.php" class="m6-back-link">
                Back to Applications
            </a>

        <?php endif; ?>

    </div>

</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
