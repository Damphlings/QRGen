<?php
include "config.php";
$page = $_GET['page'] ?? 'dashboard';
if(!in_array($page, ['dashboard', 'verification', 'generator'])) $page = 'dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Certificate Generator</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="navbar">
    <div class="navbar-brand">
        <h2>Certificate Generator</h2>
    </div>
    <ul>
        <li><a href="index.php?page=dashboard" class="<?= $page === 'dashboard' ? 'active' : '' ?>">Certificates</a></li>
        <li><a href="index.php?page=verification" class="<?= $page === 'verification' ? 'active' : '' ?>">Verification</a></li>
        <li><a href="index.php?page=generator" class="<?= $page === 'generator' ? 'active' : '' ?>">Generator</a></li>
        <li><a href="qr.php">Scan QR</a></li>
    </ul>
</div>

<div class="main">
<?php
switch($page){
    case 'verification':
        include "verification.php";
        break;
    case 'generator':
        include "generator.php";
        break;
    case 'dashboard':
    default:
        include "dashboard.php";
}
?>
</div>

</body>
</html>
