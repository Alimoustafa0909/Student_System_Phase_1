<?php
session_start();
require '../config/db.php';

// Get search from the url 
$search = $_GET['search'] ?? '';
$searchParam = "%" . $conn->real_escape_string($search) . "%"; //3a4an amn3 el sql injection  the % symbols is for the like query to match any string in the database 

// Search Query
$sql = "SELECT * FROM students WHERE name LIKE '$searchParam' OR id LIKE '$searchParam'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student List</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="page-content">
        <h2>Student List</h2>
<a href="create.php" class="button add-button" style="margin-bottom: 15px;">+ Add New Student</a>

        <!-- Search Form -->
        <form method="GET" class="search-form">
            <input type="text" name="search" placeholder="Search by name or ID" value="<?= $search ?>">
            <input type="submit" value="Search">
            <?php if (!empty($search)): ?>
                <a  href="index.php" class="button clear-search-button">Clear Search</a>
            <?php endif; ?>
        </form>

        <?php if ($result && $result->num_rows > 0): ?>
            <table>
                <tr>
                    <th>ID</th><th>Name</th><th>Email</th><th>Gender</th><th>Phone</th>
                    <?php if (!empty($_SESSION['admin'])): ?><th>Actions</th><?php endif; ?>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= $row['name'] ?></td>
                        <td><?= $row['email'] ?></td>
                        <td><?= $row['gender'] ?></td>
                        <td><?= $row['phone'] ?></td>
                        <?php if (!empty($_SESSION['admin'])): ?>
                            <td>
<a href="edit.php?id=<?= $row['id'] ?>" class="button add-button">Edit</a>
<a href="delete.php?id=<?= $row['id'] ?>" class="button delete-button">Delete</a>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No students found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
