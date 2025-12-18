<?php
// Initialize variables
$name = $email = "";
$errors = ["name" => "", "email" => "", "password" => "", "confirm_password" => "", "file" => ""];
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Collect input values
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirm_password = $_POST["confirm_password"] ?? "";

    // Validation
    if (empty($name)) {
        $errors["name"] = "Name is required.";
    }

    if (empty($email)) {
        $errors["email"] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = "Invalid email format.";
    }

    if (empty($password)) {
        $errors["password"] = "Password is required.";
    } elseif (strlen($password) < 6 || !preg_match('/[!@#$%^&*]/', $password)) {
        $errors["password"] = "Password must be at least 6 characters and contain a special character.";
    }

    if ($confirm_password !== $password) {
        $errors["confirm_password"] = "Passwords do not match.";
    }

    // If no validation errors, proceed
    if (!array_filter($errors)) {
        $file = 'users.json';

        if (!file_exists($file)) {
            file_put_contents($file, json_encode([]));
        }

        $jsonData = file_get_contents($file);
        if ($jsonData === false) {
            $errors["file"] = "Failed to read users.json";
        } else {
            $usersArray = json_decode($jsonData, true);
            if (!is_array($usersArray)) {
                $usersArray = [];
            }

            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // New user array
            $newUser = [
                "name" => $name,
                "email" => $email,
                "password" => $hashedPassword
            ];

            // Add user to array
            $usersArray[] = $newUser;

            // Save back to JSON
            if (file_put_contents($file, json_encode($usersArray, JSON_PRETTY_PRINT)) === false) {
                $errors["file"] = "Failed to write to users.json";
            } else {
                $success = "Registration successful!";
                $name = $email = "";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
    <style>
        .error { color: red; }
        .success { color: green; font-weight: bold; }
        form { width: 300px; margin: 20px auto; }
        label { display: block; margin-top: 10px; }
        input { width: 100%; padding: 5px; }
        button { margin-top: 15px; padding: 10px; width: 100%; }
    </style>
</head>
<body>

<?php if ($success): ?>
<script>alert('Registration successful!');</script>
<?php endif; ?>

<form method="POST" action="">
    <label>Name:</label>
    <input type="text" name="name" value="<?= htmlspecialchars($name) ?>">
    <div class="error"><?= $errors['name'] ?></div>

    <label>Email:</label>
    <input type="text" name="email" value="<?= htmlspecialchars($email) ?>">
    <div class="error"><?= $errors['email'] ?></div>

    <label>Password:</label>
    <input type="password" name="password">
    <div class="error"><?= $errors['password'] ?></div>

    <label>Confirm Password:</label>
    <input type="password" name="confirm_password">
    <div class="error"><?= $errors['confirm_password'] ?></div>

    <div class="error"><?= $errors['file'] ?></div>

    <button type="submit">Register</button>
</form>

</body>
</html>
