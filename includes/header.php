
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$current_role = $_SESSION['role'] ?? $_SESSION['user_role'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>CareerConnect | University Career Portal</title>

    <!-- Common CSS -->
    <link rel="stylesheet"
          href="/University-Internship-and-Career-Portal-System/assets/css/style.css?v=<?php echo time(); ?>">

    <!-- Page Specific CSS -->
    <?php if (!empty($page_css)): ?>
        <link rel="stylesheet"
              href="<?php echo htmlspecialchars($page_css); ?>?v=<?php echo time(); ?>">
    <?php endif; ?>

</head>

<body>

<header class="main-header">

    <div class="navbar">

        <!-- Logo -->
        <a href="/University-Internship-and-Career-Portal-System/home/index.php"
           class="logo">
            Career<span>Connect</span>
        </a>

        <!-- Navigation -->
        <nav class="nav-links">

            <a href="/University-Internship-and-Career-Portal-System/home/index.php">
                Home
            </a>

            <a href="/University-Internship-and-Career-Portal-System/student/index.php">
                Student
            </a>

            <a href="/University-Internship-and-Career-Portal-System/company/index.php">
                Company
            </a>

            <a href="/University-Internship-and-Career-Portal-System/internship/index.php">
                Internship
            </a>

            <a href="/University-Internship-and-Career-Portal-System/application/index.php">
                Application
            </a>

            <a href="/University-Internship-and-Career-Portal-System/admin/index.php">
                Admin
            </a>

        </nav>

    </div>

</header>

<main class="main-content">
