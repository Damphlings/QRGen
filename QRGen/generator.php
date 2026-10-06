<?php
include "config.php";

$generatedQR = null;
$certNo = null;

// Check for success parameter from redirect
if(isset($_GET['success']) && isset($_GET['cert'])) {
    $certNo = $_GET['cert'];

    // Load the QR from database
    try {
        $stmt = $pdo->prepare("SELECT qr FROM cert WHERE CertNo = ?");
        $stmt->execute([$certNo]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if($result && $result['qr']) {
            $generatedQR = base64_encode($result['qr']);
        }
    } catch(PDOException $e) {
        // Handle error silently
    }
}

// Load categories from database (reload after successful submission to include new categories)
$categories = [];
try {
    $stmt = $pdo->query("SELECT name FROM categories ORDER BY name");
    $categories = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch(PDOException $e) {
    // Fallback to default categories if table doesn't exist
    $categories = ['Perfect Attendance and Punctuality', 'Top 1 Angela'];
}

if(isset($_POST['submit'])){

    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $mname = $_POST['mname'];
    $category = $_POST['category'];

    // Handle custom category
    if($category === 'custom' && !empty($_POST['custom_category'])){
        $category = trim($_POST['custom_category']);

        // Save new category to database
        try {
            $stmt = $pdo->prepare("INSERT IGNORE INTO categories (name) VALUES (?)");
            $stmt->execute([$category]);
        } catch(PDOException $e) {
            // Continue even if category save fails
        }
    }

    // Generate certificate number
    $certNo = "CERT-" . date("Y") . "-" . rand(100000,999999);

    // QR content (recommended: verification link)
    $qrData = $certNo;

    // Generate QR image
    $qrImage = file_get_contents(
        "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($qrData)
    );

    // Save to DB (IMPORTANT: using existing qr column)
    $stmt = $pdo->prepare("
        INSERT INTO cert (CertNo, FName, LName, MName, Category, Status, qr)
        VALUES (?, ?, ?, ?, ?, 'Pending', ?)
    ");

    $stmt->execute([$certNo, $fname, $lname, $mname, $category, $qrImage]);

    // Redirect to prevent form resubmission on refresh
    header("Location: index.php?page=generator&success=1&cert=" . urlencode($certNo));
    exit();
}
?>

<h1 class="page-title">Generate a certificate</h1>

<div class="form-container">

<?php if($generatedQR): ?>
    <div class="success-box qr-generated">
        QR code generated
        <br><br>

        <img src="data:image/png;base64,<?= $generatedQR ?>" width="180">

        <br><br>

        <a class="button btn-green" href="download_qr.php?cert=<?= $certNo ?>">
            Download QR
        </a>
    </div>
<?php endif; ?>

<form method="POST">

    <input type="text" name="fname" placeholder="First Name" required>
    <input type="text" name="mname" placeholder="Middle Name">
    <input type="text" name="lname" placeholder="Last Name" required>

    <select name="category" id="category-select" required>
        <option value="">Select Category</option>
        <?php foreach($categories as $cat): ?>
            <option value="<?= htmlspecialchars($cat) ?>" <?= (isset($_POST['category']) && $_POST['category'] === $cat) ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat) ?>
            </option>
        <?php endforeach; ?>
        <option value="custom">Add New Category</option>
    </select>

    <input type="text" name="custom_category" id="custom-category" placeholder="Enter new category name" style="display: none;">

    <button type="submit" name="submit">Generate QR code</button>

</form>

</div>

<script>
// Handle custom category selection
document.getElementById('category-select').addEventListener('change', function() {
    const customInput = document.getElementById('custom-category');
    if (this.value === 'custom') {
        customInput.style.display = 'block';
        customInput.required = true;
        customInput.focus();
    } else {
        customInput.style.display = 'none';
        customInput.required = false;
        customInput.value = '';
    }
});
</script>

