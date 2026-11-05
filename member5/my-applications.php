<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';

m5_requireStudent();

$user_id = (int)$_SESSION['user_id'];
$student_id = m5_getStudentId($conn, $user_id);

$applications = [];

if ($student_id) {
    $sql = "SELECT a.application_id, a.application_date, a.status,
                   i.title, i.deadline, c.company_name
            FROM applications a
            INNER JOIN internships i ON a.internship_id = i.internship_id
            INNER JOIN companies c ON i.company_id = c.company_id
            WHERE a.student_id = ?
            ORDER BY a.application_id DESC";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $student_id);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $applications = mysqli_fetch_all($result, MYSQLI_ASSOC);

        mysqli_stmt_close($stmt);
    }
}

$page_css = "/University-Internship-and-Career-Portal-System/assets/css/member5.css";

include __DIR__ . '/../includes/header.php';
?>

<section class="m5-section">
    <div class="m5-container">

        <h1>My Applications</h1>
        <p class="m5-subtitle">
            View and manage your submitted internship applications.
        </p>

        <?php if (($_GET['message'] ?? '') === 'success'): ?>
            <div class="m5-success">
                Your application has been submitted successfully!
            </div>
        <?php elseif (($_GET['message'] ?? '') === 'already_applied'): ?>
            <div class="m5-notice">
                You have already applied for this internship.
            </div>
        <?php elseif (($_GET['message'] ?? '') === 'withdrawn'): ?>
            <div class="m5-success">
                Your application has been withdrawn.
            </div>
        <?php endif; ?>

        <?php if (empty($applications)): ?>

            <div class="m5-message">
                <h3>No Applications Found</h3>
                <p>You have not submitted any internship applications yet.</p>
                <a href="../member4/index.php" class="m5-btn">
                    Browse Internships
                </a>
            </div>

        <?php else: ?>

            <div class="m5-table-wrapper">
                <table class="m5-table">
                    <thead>
                        <tr>
                            <th>Internship</th>
                            <th>Company</th>
                            <th>Applied Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($applications as $application): ?>
                            <tr>
                                <td><?php echo m5_escape($application['title']); ?></td>

                                <td><?php echo m5_escape($application['company_name']); ?></td>

                                <td><?php echo m5_escape($application['application_date']); ?></td>

                                <td>
                                    <span class="m5-status">
                                        <?php echo m5_escape($application['status']); ?>
                                    </span>
                                </td>

                                <td>
                                    <a class="m5-small-link"
                                       href="application-details.php?id=<?php echo (int)$application['application_id']; ?>">
                                        View
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>

                </table>
            </div>

        <?php endif; ?>

    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>