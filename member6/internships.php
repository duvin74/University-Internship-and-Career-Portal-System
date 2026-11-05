<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';

m6_requireAdmin();

$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_internship"])) {

    $internshipId = (int)($_POST["internship_id"] ?? 0);

    $check = mysqli_prepare(
        $conn,
        "SELECT COUNT(*) AS total FROM applications WHERE internship_id = ?"
    );

    mysqli_stmt_bind_param($check, "i", $internshipId);
    mysqli_stmt_execute($check);
    $checkResult = mysqli_stmt_get_result($check);
    $checkData = mysqli_fetch_assoc($checkResult);
    mysqli_stmt_close($check);

    if ($checkData["total"] > 0) {

        $message = "This internship cannot be deleted because applications already exist.";
        $messageType = "error";

    } else {

        $delete = mysqli_prepare(
            $conn,
            "DELETE FROM internships WHERE internship_id = ?"
        );

        mysqli_stmt_bind_param($delete, "i", $internshipId);

        if (mysqli_stmt_execute($delete) && mysqli_stmt_affected_rows($delete) > 0) {
            $message = "Internship deleted successfully.";
            $messageType = "success";
        } else {
            $message = "Unable to delete this internship.";
            $messageType = "error";
        }

        mysqli_stmt_close($delete);
    }
}

$sql = "SELECT i.internship_id, i.title, i.deadline,
               c.company_name
        FROM internships i
        INNER JOIN companies c ON i.company_id = c.company_id
        ORDER BY i.internship_id DESC";

$result = mysqli_query($conn, $sql);

$page_css = "/University-Internship-and-Career-Portal-System/assets/css/member6.css";

include __DIR__ . '/../includes/header.php';
?>

<section class="m6-section">
    <div class="m6-container">

        <h1>Manage Internships</h1>

        <?php if ($message !== ""): ?>
            <div class="m6-message <?php echo $messageType; ?>">
                <?php echo m6_escape($message); ?>
            </div>
        <?php endif; ?>

        <div class="m6-table-wrapper">
            <table class="m6-table">

                <thead>
                    <tr>
                        <th>Internship Title</th>
                        <th>Company</th>
                        <th>Deadline</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if ($result && mysqli_num_rows($result) > 0): ?>

                        <?php while ($internship = mysqli_fetch_assoc($result)): ?>

                            <tr>
                                <td><?php echo m6_escape($internship['title']); ?></td>
                                <td><?php echo m6_escape($internship['company_name']); ?></td>
                                <td><?php echo m6_escape($internship['deadline']); ?></td>

                                <td>
                                    <div class="m6-action-buttons">

                                        <a class="m6-view-button"
                                           href="../member4/details.php?id=<?php echo (int)$internship['internship_id']; ?>">
                                            View
                                        </a>

                                        <form method="POST"
                                              class="m6-confirm"
                                              data-confirm-message="Are you sure you want to delete this internship?">

                                            <input type="hidden"
                                                   name="internship_id"
                                                   value="<?php echo (int)$internship['internship_id']; ?>">

                                            <button type="submit"
                                                    name="delete_internship"
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
                            <td colspan="4">No internships available.</td>
                        </tr>

                    <?php endif; ?>

                </tbody>
            </table>
        </div>

    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>