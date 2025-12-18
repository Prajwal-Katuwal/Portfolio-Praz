<?php
// Include header
require __DIR__ . "/includes/header.php";

// File path
$filePath = __DIR__ . "/students.txt";
?>

<main>
    <h2>Student List</h2>

    <?php
    if (!file_exists($filePath) || filesize($filePath) === 0) {
        echo "<p>No students found.</p>";
    } else {
        // Read each line
        foreach (file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            list($namePart, $emailPart, $skillsPart) = explode("|", $line);

            $name = trim(explode(":", $namePart)[1]);
            $email = trim(explode(":", $emailPart)[1]);
            $skills = array_map("trim", explode(",", trim(explode(":", $skillsPart)[1])));
            ?>

            <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px; border-radius:5px;">
                <p><strong>Name:</strong> <?php echo htmlspecialchars($name); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
                <p><strong>Skills:</strong> <?php echo implode(", ", $skills); ?></p>
            </div>

        <?php
        }
    }
    ?>

</main>

<?php
// Include footer
include __DIR__ . "/includes/footer.php";
?>
