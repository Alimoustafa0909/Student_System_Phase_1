<?php
require '../config/db.php';

// Validation Function
function validateStudent($name, $email, $gender, $phone) {
    if (!$name || !$email || !$gender || !$phone) {
        return "All fields are required.";
    }

  
    if (!preg_match('/^[a-zA-Z\s]+$/', $name)) {
        return "Name must contain only char";
    }

    // Check that the phone contains only Numbers and is up to 12 Numbers
    if (!preg_match('/^[0-9]{1,12}$/', $phone)) {
        return "Phone must be numeric and 12 digits max.";
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name   = $_POST['name'];
    $email  = $_POST['email'];
    $gender = $_POST['gender'];
    $phone  = $_POST['phone'];

    $error = validateStudent($name, $email, $gender, $phone);

    if (!$error) {
        $name   = $conn->real_escape_string($name);
        $email  = $conn->real_escape_string($email);
        $gender = $conn->real_escape_string($gender);
        $phone  = $conn->real_escape_string($phone);

        $sql = "INSERT INTO students (name, email, gender, phone) VALUES ('$name', '$email', '$gender', '$phone')";
        if ($conn->query($sql)) {
            header("Location: index.php");
    }
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Student</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="page-content">
    <h2>Add New Student</h2>

    <?php if (!empty($error)): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST" class="create-form" id="studentForm">
        <input type="text" name="name" id="name" placeholder="Your Name" value="<?= $_POST['name'] ?? '' ?>">
        <small class="error" id="nameError"></small>

        <input type="email" name="email" id="email" placeholder="Your Email" value="<?= $_POST['email'] ?? '' ?>">
        <small class="error" id="emailError"></small>

        <select name="gender" id="gender">
            <option value="">Select Gender</option>
            <option value="Male" <?= (($_POST['gender'] ?? '') == 'Male') ? 'selected' : '' ?>>Male</option>
            <option value="Female" <?= (($_POST['gender'] ?? '') == 'Female') ? 'selected' : '' ?>>Female</option>
        </select>
        <small class="error" id="genderError"></small>

        <input type="text" name="phone" id="phone" placeholder="Your Phone" value="<?= $_POST['phone'] ?? '' ?>">
        <small class="error" id="phoneError"></small>

        <button class="add-button" type="submit">Save</button>
    </form>
</div>
</body>
<script>
document.getElementById("studentForm").addEventListener("submit", function (e) {
    let hasError = false;
    const name = document.getElementById("name").value.trim();
    const email = document.getElementById("email").value.trim();
    const gender = document.getElementById("gender").value;
    const phone = document.getElementById("phone").value.trim();

    const nameError = document.getElementById("nameError");
    const emailError = document.getElementById("emailError");
    const genderError = document.getElementById("genderError");
    const phoneError = document.getElementById("phoneError");

    nameError.textContent = "";
    emailError.textContent = "";
    genderError.textContent = "";
    phoneError.textContent = "";

    if (name === "") {
        nameError.textContent = "Name is required.";
        hasError = true;
    }

    if (email === "") {
        emailError.textContent = "Email is required.";
        hasError = true;
    } else if (!/^\S+@\S+\.\S+$/.test(email)) {
        emailError.textContent = "Please enter a valid email.";
        hasError = true;
    }

    if (gender === "") {
        genderError.textContent = "Gender is required.";
        hasError = true;
    }

    if (phone === "") {
        phoneError.textContent = "Phone is required.";
        hasError = true;
    } 
    else if (!/^[0-9]{1,12}$/.test(phone)) {
        phoneError.textContent = "Phone must be numeric and 12 digits max.";
        hasError = true;
    }

    if (hasError) {
        e.preventDefault();
    }
});
</script>
</html>
