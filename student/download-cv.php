
<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';

m2_requireStudent();

$profile = m2_getStudentProfile($conn, $_SESSION['user_id']);

if (!$profile || empty($profile['cv_path'])) {
    http_response_code(404);
    exit("CV not found.");
}

$filename = m2_safeFilePath($profile['cv_path']);

if (!$filename) {
    http_response_code(403);
    exit("Invalid CV path.");
}

$file_path = __DIR__ . '/uploads/' . $filename;

if (!is_file($file_path)) {
    http_response_code(404);
    exit("CV file does not exist.");
}

$mode = $_GET['mode'] ?? 'download';

header('Content-Type: application/pdf');

if ($mode === 'view') {
    header('Content-Disposition: inline; filename="Student-CV.pdf"');
} else {
    header('Content-Disposition: attachment; filename="Student-CV.pdf"');
}

header('Content-Length: ' . filesize($file_path));
header('X-Content-Type-Options: nosniff');

readfile($file_path);
exit;
