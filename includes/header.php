
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$current_role = $_SESSION['role'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>CareerConnect | University Career Portal</title>

    <link rel="stylesheet"
          href="/University-Internship-and-Career-Portal-System/assets/css/style.css">

    <?php if (!empty($page_css)): ?>
        <link rel="stylesheet"
              href="<?php echo htmlspecialchars($page_css); ?>">
    <?php endif; ?>

</head>

<body>

<header class="main-header">

    <div class="navbar">

        <a href="/University-Internship-and-Career-Portal-System/member1/index.php"
           class="logo">
            Career<span>Connect</span>
        </a>

        <nav class="nav-links">

            <!-- 1. Home - Member 1 -->
            <a href="/University-Internship-and-Career-Portal-System/member1/index.php">
                Home
            </a>

            <!-- 2. Student - Member 2 -->
            <a href="/University-Internship-and-Career-Portal-System/member2/index.php">
                Student
            </a>

            <!-- 3. Company - Member 3 -->
            <a href="/University-Internship-and-Career-Portal-System/member3/index.php">
                Company
            </a>

            <!-- 4. Internship - Member 4 -->
            <a href="/University-Internship-and-Career-Portal-System/member4/index.php">
                Internship
            </a>

            <!-- 5. Application - Member 5 -->
            <a href="/University-Internship-and-Career-Portal-System/member5/index.php">
                Application
            </a>

            <!-- 6. Admin - Member 6 -->
            <a href="/University-Internship-and-Career-Portal-System/member6/index.php">
                Admin
            </a>

        </nav>

    </div>

</header>

<main class="main-content">
