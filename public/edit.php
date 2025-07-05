<?php
session_start();
require '../config/db.php';


if (!isset($_SESSION['admin'])) {
    header("Location: ../auth/login.php");
    exit;
}


$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    die('Invalid student ID.');
}


$id = (int) $id;
$sql = "SELECT * FROM students WHERE id = $id";
$result = $conn->query($sql);
$student = $result->fetch_assoc();

if (!$student) {
    die('Student not found.');
}


function validateStudent($name, $email, $gender, $phone) {
    if (!$name || !$email || !$gender || !$phone) {
        return "All fields are required.";
    }
    if (!preg_match('/^[0-9]{1,12}$/', $phone)) {
        return "Phone must be numeric and 12 digits max.";
    }
    return null;
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name   = $conn->real_escape_string($_POST['name']);
    $email  = $conn->real_escape_string($_POST['email']);
    $gender = $conn->real_escape_string($_POST['gender']);
    $phone  = $conn->real_escape_string($_POST['phone']);

    $error = validateStudent($name, $email, $gender, $phone);

    if (!$error) {
        $updateSql = "UPDATE students SET name='$name', email='$email', gender='$gender', phone='$phone' WHERE id=$id";
        if ($conn->query($updateSql)) {
            header("Location: index.php");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Student</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="page-content">
    <h2>Edit Student</h2>

    <?php if (!empty($error)): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST" class="create-form">
        <input type="text" name="name" value="<?= $student['name'] ?>" required>
        <input type="email" name="email" value="<?= $student['email'] ?>" required>
        <select name="gender" required>
            <option value="">Select Gender</option>
            <option value="Male" <?= $student['gender'] === 'Male' ? 'selected' : '' ?>>Male</option>
            <option value="Female" <?= $student['gender'] === 'Female' ? 'selected' : '' ?>>Female</option>
        </select>
        <input type="text" name="phone" value="<?= $student['phone'] ?>" required>
        <button class="add-button" type="submit">Update</button>
    </form>
</div>
</body>
</html>
