<?php
include "config.php";

if(!isset($_GET['id'])) die("Invalid request");

$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM cert WHERE ID=?");
$stmt->execute([$id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$row) die("Record not found");

if(isset($_POST['update'])){

    $fname = $_POST['fname'];
    $mname = $_POST['mname'];
    $lname = $_POST['lname'];
    $category = $_POST['category'];

    $update = $pdo->prepare("
        UPDATE cert 
        SET FName=?, MName=?, LName=?, Category=?
        WHERE ID=?
    ");

    $update->execute([$fname, $mname, $lname, $category, $id]);

    header("Location: index.php?page=dashboard");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Certificate</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body class="edit-body">

<div class="edit-card">

    <h2>Edit Certificate</h2>

    <form method="POST">

        <label>First Name</label>
        <input type="text" name="fname" value="<?= $row['FName'] ?>" required>

        <label>Middle Name</label>
        <input type="text" name="mname" value="<?= $row['MName'] ?>">

        <label>Last Name</label>
        <input type="text" name="lname" value="<?= $row['LName'] ?>" required>

        <label>Certificate Number</label>
        <input type="text" value="<?= $row['CertNo'] ?>" class="readonly" readonly>

        <label>Category</label>
        <select name="category" required>
            <option value="Perfect Attendance and Punctuality"
                <?= $row['Category'] == "Perfect Attendance and Punctuality" ? "selected" : "" ?>>
                Perfect Attendance and Punctuality
            </option>

            <option value="Top 1 Angela"
                <?= $row['Category'] == "Top 1 Angela" ? "selected" : "" ?>>
                Top 1 Angela
            </option>
        </select>

        <label>Status</label>
        <input type="text" value="<?= $row['Status'] ?>" class="readonly" readonly>

        <button type="submit" name="update">Save changes</button>

    </form>

    <a href="index.php?page=dashboard" class="back-btn">
        Back to certificates
    </a>

</div>

</body>
</html>