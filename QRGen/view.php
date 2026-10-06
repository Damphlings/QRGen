<?php
include "config.php";

if(!isset($_GET['CertNo'])){
    die("Invalid QR Code");
}

$certNo = $_GET['CertNo'];

$stmt = $pdo->prepare("SELECT * FROM cert WHERE CertNo=?");
$stmt->execute([$certNo]);
$cert = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$cert){
    die("Certificate not found");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Certificate Verification</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="verification-container">

<?php if($cert['Status'] == 'Verified'): ?>
    <div class="verification-status verified">
        ✅ VERIFIED CERTIFICATE
    </div>
<?php else: ?>
    <div class="verification-status invalid">
        ⚠ NOT VERIFIED
    </div>
<?php endif; ?>

<h2 class="verification-name"><?= $cert['FName'] . " " . $cert['LName'] ?></h2>
<p class="verification-detail"><strong>Certificate No:</strong> <?= $cert['CertNo'] ?></p>
<p class="verification-detail"><strong>Category:</strong> <?= $cert['Category'] ?></p>
<p class="verification-detail"><strong>Status:</strong> <?= $cert['Status'] ?></p>

</div>

</body>
</html>