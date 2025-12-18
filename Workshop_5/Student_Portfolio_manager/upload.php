<?php
require __DIR__ . "/includes/header.php";

$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        if (!isset($_FILES["portfolio"]) || $_FILES["portfolio"]["error"] !== 0) {
            throw new Exception("No file uploaded or upload error occurred.");
        }

        $file = $_FILES["portfolio"];

        $allowedTypes = ["pdf", "jpg", "jpeg", "png"];

        $fileName = $file["name"];
        $fileSize = $file["size"];
        $fileTmp  = $file["tmp_name"];

        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (!in_array($extension, $allowedTypes)) {
            throw new Exception("Only PDF, JPG, and PNG files are allowed.");
        }

        if ($fileSize > 2 * 1024 * 1024) {
            throw new Exception("File size must not exceed 2MB.");
        }

        $newFileName = "portfolio_" . time() . "." . $extension;

        $uploadDir = __DIR__ . "/uploads/";

        if (!is_dir($uploadDir)) {
            throw new Exception("Upload directory does not exist.");
        }

        // Destination path
        $destination = $uploadDir . $newFileName;

        if (!move_uploaded_file($fileTmp, $destination)) {
            throw new Exception("Failed to upload file.");
        }

        $success = "Portfolio uploaded successfully!";

    } catch (Exception $e) {
        $errors[] = $e->getMessage();
    }
}
?>

<main>
    <h2>Upload Portfolio File</h2>

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

    <form method="post" enctype="multipart/form-data">
        <label>Select Portfolio File (PDF, JPG, PNG | Max 2MB):</label><br><br>
        <input type="file" name="portfolio" required><br><br>
        <button type="submit">Upload</button>
    </form>
</main>

<?php
include __DIR__ . "/includes/footer.php";
?>
