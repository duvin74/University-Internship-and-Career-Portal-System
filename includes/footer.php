
<?php
$currentMember = basename(dirname($_SERVER['SCRIPT_FILENAME']));

$jsFiles = [
    
    'student' => 'student.js',
    'company' => 'company.js',
    'internship' => 'internship.js',
    'application' => 'application.js',
    'admin' => 'admin.js'
];
?>

    </main>

    <?php if (isset($jsFiles[$currentMember])): ?>
        <script src="/University-Internship-and-Career-Portal-System/assets/js/<?php echo htmlspecialchars($jsFiles[$currentMember]); ?>"></script>
    <?php endif; ?>

</body>
</html>