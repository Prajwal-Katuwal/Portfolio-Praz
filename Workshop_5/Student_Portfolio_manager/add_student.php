<?php
// Include header
require __DIR__ . "/includes/header.php";

// Initialize variables
$name = $email = $skills = "";
$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $name = trim($_POST["name"] ?? "");
        $email = trim($_POST["email"] ?? "");
        $skills = trim($_POST["skills"] ?? "");

        if (empty($name)) {
            $errors[] = "Name is required.";
        }

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Valid email is required.";
        }

        if (empty($skills)) {
            $errors[] = "Skills are required.";
        }

        if (!empty($errors)) {
            throw new Exception("Validation failed.");
        }

        $skillsArray = explode(",", $skills);

        $skillsArray = array_map("trim", $skillsArray);

        $skillsString = implode(", ", $skillsArray);

        $data = "Name: $name | Email: $email | Skills: $skillsString" . PHP_EOL;

        if (!file_put_contents("students.txt", $data, FILE_APPEND)) {
            throw new Exception("Failed to save student data.");
        }

        $success = "Student information saved successfully!";
        $name = $email = $skills = ""; // Clear form

    } catch (Exception $e) {
        if (empty($errors)) {
            $errors[] = $e->getMessage();
        }
    }
}
?>

<main>
    <h2>Add Student Information</h2>

    <?php if (!empty($errors)): ?>
        <div style="color:red;">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <p style="color:green;"><?php echo $success; ?></p>
    <?php endif; ?>

    <form method="post">
        <label>Name:</label><br>
        <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>"><br><br>

        <label>Email:</label><br>
        <input type="text" name="email" value="<?php echo htmlspecialchars($email); ?>"><br><br>

        <label>Skills (comma-separated):</label><br>
        <input type="text" name="skills" value="<?php echo htmlspecialchars($skills); ?>"><br><br>

        <button type="submit">Save Student</button>
    </form>
</main>

<?php
include __DIR__ . "/includes/footer.php";
?>
