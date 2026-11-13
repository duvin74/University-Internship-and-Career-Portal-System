
<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';

m6_requireAdmin();

$message = "";
$messageType = "";

// DELETE COMPANY
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_company"])) {

    $company_id = (int)($_POST["company_id"] ?? 0);

    if ($company_id > 0) {

        // Check related internships
        $checkInternships = mysqli_prepare(
            $conn,
            "SELECT internship_id FROM internships WHERE company_id = ? LIMIT 1"
        );

        mysqli_stmt_bind_param($checkInternships, "i", $company_id);
        mysqli_stmt_execute($checkInternships);
        $internshipResult = mysqli_stmt_get_result($checkInternships);

        // Check related achievements
        $checkAchievements = mysqli_prepare(
            $conn,
            "SELECT achievement_id FROM company_achievements WHERE company_id = ? LIMIT 1"
        );

        mysqli_stmt_bind_param($checkAchievements, "i", $company_id);
        mysqli_stmt_execute($checkAchievements);
        $achievementResult = mysqli_stmt_get_result($checkAchievements);

        if (
            mysqli_num_rows($internshipResult) > 0 ||
            mysqli_num_rows($achievementResult) > 0
        ) {

            $message = "This company cannot be deleted because it has related internships or achievements.";
            $messageType = "error";

        } else {

            mysqli_begin_transaction($conn);

            try {

                // Get company user ID
                $getUser = mysqli_prepare(
                    $conn,
                    "SELECT user_id FROM companies WHERE company_id = ?"
                );

                mysqli_stmt_bind_param($getUser, "i", $company_id);
                mysqli_stmt_execute($getUser);

                $userResult = mysqli_stmt_get_result($getUser);
                $companyData = mysqli_fetch_assoc($userResult);

                if (!$companyData) {
                    throw new Exception("Company not found.");
                }

                $user_id = (int)$companyData["user_id"];

                // Update experiences
                $updateExperiences = mysqli_prepare(
                    $conn,
                    "UPDATE experiences SET company_id = NULL WHERE company_id = ?"
                );

                mysqli_stmt_bind_param($updateExperiences, "i", $company_id);
                mysqli_stmt_execute($updateExperiences);

                // Delete company
                $deleteCompany = mysqli_prepare(
                    $conn,
                    "DELETE FROM companies WHERE company_id = ?"
                );

                mysqli_stmt_bind_param($deleteCompany, "i", $company_id);
                mysqli_stmt_execute($deleteCompany);

                if (mysqli_stmt_affected_rows($deleteCompany) !== 1) {
                    throw new Exception("Company could not be deleted.");
                }

                // Delete company user account
                $deleteUser = mysqli_prepare(
                    $conn,
                    "DELETE FROM Users WHERE user_id = ?"
                );

                mysqli_stmt_bind_param($deleteUser, "i", $user_id);
                mysqli_stmt_execute($deleteUser);

                mysqli_commit($conn);

                $message = "Company deleted successfully!";
                $messageType = "success";

            } catch (Exception $e) {

                mysqli_rollback($conn);

                $message = "Unable to delete this company. Please check related records.";
                $messageType = "error";
            }
        }

    } else {

        $message = "Invalid company selected.";
        $messageType = "error";
    }
}

// FETCH COMPANIES
$sql = "SELECT c.company_id, c.company_name, c.website,
               c.verification_status, u.name, u.email
        FROM companies c
        INNER JOIN Users u ON c.user_id = u.user_id
        ORDER BY c.company_id DESC";

$result = mysqli_query($conn, $sql);

$page_css = "/University-Internship-and-Career-Portal-System/assets/css/admin.css";

include __DIR__ . '/../includes/header.php';
?>

<section class="m6-section">

    <div class="m6-container">

        <h1>Manage Companies</h1>

        <p class="m6-subtitle">
            Review, view and manage registered companies.
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
                        <th>Company</th>
                        <th>Contact Person</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if ($result && mysqli_num_rows($result) > 0): ?>

                        <?php while ($company = mysqli_fetch_assoc($result)): ?>

                            <tr>

                                <td>
                                    <?php echo m6_escape($company['company_name']); ?>
                                </td>

                                <td>
                                    <?php echo m6_escape($company['name']); ?>
                                </td>

                                <td>
                                    <?php echo m6_escape($company['email']); ?>
                                </td>

                                <td>
                                    <span class="m6-status">
                                        <?php echo m6_escape($company['verification_status']); ?>
                                    </span>
                                </td>

                                <td>

                                    <div class="m6-action-buttons">

                                        <!-- VIEW BUTTON -->
                                        <a
                                            class="m6-view-button"
                                            href="company-details.php?id=<?php echo (int)$company['company_id']; ?>">
                                            View
                                        </a>

                                        <!-- DELETE BUTTON -->
                                        <form
                                            method="POST"
                                            class="m6-confirm"
                                            data-confirm-message="Are you sure you want to delete this company?">

                                            <input
                                                type="hidden"
                                                name="company_id"
                                                value="<?php echo (int)$company['company_id']; ?>">

                                            <button
                                                type="submit"
                                                name="delete_company"
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
                            <td colspan="5">No companies registered yet.</td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
