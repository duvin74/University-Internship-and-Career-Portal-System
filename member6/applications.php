
<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';

m6_requireAdmin();

$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_application"])) {

    $applicationId = (int)($_POST["application_id"] ?? 0);

    $check = mysqli_prepare(
        $conn,
        "SELECT COUNT(*) AS total FROM interviews WHERE application_id = ?"
    );

    mysqli_stmt_bind_param($check, "i", $applicationId);
    mysqli_stmt_execute($check);

    $checkResult = mysqli_stmt_get_result($check);
    $checkData = mysqli_fetch_assoc($checkResult);

    mysqli_stmt_close($check);

    if ($checkData["total"] > 0) {

        $message = "This application cannot be deleted because an interview record exists.";
        $messageType = "error";

    } else {

        $delete = mysqli_prepare(
            $conn,
            "DELETE FROM applications WHERE application_id = ?"
        );

        mysqli_stmt_bind_param($delete, "i", $applicationId);

        if (mysqli_stmt_execute($delete) &&
            mysqli_stmt_affected_rows($delete) > 0) {

            $message = "Application deleted successfully.";
            $messageType = "success";

        } else {

            $message = "Unable to delete this application.";
            $messageType = "error";
        }

        mysqli_stmt_close($delete);
    }
}

$sql = "SELECT
            a.application_id,
            a.application_date,
            a.status,
            u.name AS student_name,
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
        ORDER BY a.application_id DESC";

$result = mysqli_query($conn, $sql);

$page_css = "/University-Internship-and-Career-Portal-System/assets/css/member6.css";

include __DIR__ . '/../includes/header.php';
?>

<section class="m6-section">

    <div class="m6-container">

        <h1>Student Applications</h1>

        <?php if ($message !== ""): ?>

            <div class="m6-message <?php echo m6_escape($messageType); ?>">
                <?php echo m6_escape($message); ?>
            </div>

        <?php endif; ?>

        <div class="m6-table-wrapper">

            <table class="m6-table">

                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Internship</th>
                        <th>Company</th>
                        <th>Applied Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if ($result && mysqli_num_rows($result) > 0): ?>

                        <?php while ($application = mysqli_fetch_assoc($result)): ?>

                            <tr>

                                <td>
                                    <?php echo m6_escape($application['student_name']); ?>
                                </td>

                                <td>
                                    <?php echo m6_escape($application['internship_title']); ?>
                                </td>

                                <td>
                                    <?php echo m6_escape($application['company_name']); ?>
                                </td>

                                <td>
                                    <?php echo m6_escape($application['application_date']); ?>
                                </td>

                                <td>
                                    <span class="m6-status">
                                        <?php echo m6_escape($application['status']); ?>
                                    </span>
                                </td>

                                <td>

                                    <div class="m6-action-buttons">

                                        <a
                                            class="m6-view-button"
                                            href="application-details.php?id=<?php echo (int)$application['application_id']; ?>"
                                        >
                                            View
                                        </a>

                                        <form
                                            method="POST"
                                            class="m6-confirm"
                                            data-confirm-message="Are you sure you want to delete this application?"
                                        >

                                            <input
                                                type="hidden"
                                                name="application_id"
                                                value="<?php echo (int)$application['application_id']; ?>"
                                            >

                                            <button
                                                type="submit"
                                                name="delete_application"
                                                class="m6-delete-button"
                                            >
                                                Delete
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="6">
                                No applications submitted yet.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
