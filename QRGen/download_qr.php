<?php
include "config.php";

if(!isset($_GET['cert'])) die("Invalid request");

$certNo = $_GET['cert'];

$stmt = $pdo->prepare("SELECT qr FROM cert WHERE CertNo=?");
$stmt->execute([$certNo]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$row || !$row['qr']) die("QR not found");

header("Content-Type: image/png");
header("Content-Disposition: attachment; filename=qr.png");

echo $row['qr'];