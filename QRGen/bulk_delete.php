<?php
include "config.php";

if(isset($_POST['ids']) && is_array($_POST['ids'])){

    $ids = $_POST['ids'];

    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    $stmt = $pdo->prepare("DELETE FROM cert WHERE ID IN ($placeholders)");
    $stmt->execute($ids);
}

header("Location: index.php?page=dashboard");
exit;