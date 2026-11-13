
<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';

m2_requireStudent();

$profile = m2_getStudentProfile($conn, $_SESSION['user_id']);

if (!$profile || !m2_hasCV($profile)) {
    die("CV not found.");
}

$cv_url = "download-cv.php?mode=view";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View My CV</title>

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #eef2f7;
        }

        .top-bar {
            background: #142d4e;
            padding: 16px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .top-bar h2 {
            color: white;
            margin: 0;
            font-size: 20px;
        }

        .buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .buttons a {
            text-decoration: none;
            padding: 11px 17px;
            border-radius: 6px;
            background: #d5aa45;
            color: #142d4e;
            font-weight: bold;
        }

        .buttons a:hover {
            background: white;
        }

        .cv-container {
            width: 95%;
            height: calc(100vh - 85px);
            margin: 15px auto;
        }

        .cv-container iframe {
            width: 100%;
            height: 100%;
            border: none;
            background: white;
            border-radius: 8px;
        }
    </style>
</head>

<body>

    <div class="top-bar">
        <h2>My Curriculum Vitae</h2>

        <div class="buttons">
            <a href="index.php">Back to Dashboard</a>
            <a href="profile.php">Back to Profile</a>
            <a href="<?php echo htmlspecialchars($cv_url); ?>" download>Download CV</a>
        </div>
    </div>

    <div class="cv-container">
        <iframe src="<?php echo htmlspecialchars($cv_url); ?>"></iframe>
    </div>

</body>
</html>
