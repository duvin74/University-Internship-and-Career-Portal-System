
<?php
$currentMember = basename(dirname($_SERVER['SCRIPT_FILENAME']));

$jsFiles = [
    
    'member2' => 'member2.js',
    'member3' => 'member3.js',
    'member4' => 'member4.js',
    'member5' => 'member5.js',
    'member6' => 'member6.js'
];
?>

    </main>

    <?php if (isset($jsFiles[$currentMember])): ?>
        <script src="/University-Internship-and-Career-Portal-System/assets/js/<?php echo htmlspecialchars($jsFiles[$currentMember]); ?>"></script>
    <?php endif; ?>

</body>
</html>
