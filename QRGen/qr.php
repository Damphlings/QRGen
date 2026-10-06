<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Scan certificate</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://unpkg.com/html5-qrcode"></script>
</head>

<body>

<div class="qr-scanner-container">

    <h2 class="qr-scanner-title">
        Verify a certificate
    </h2>

    <p class="qr-scanner-subtitle">
        Scan QR code or enter certificate number
    </p>

    <!-- ⌨️ Input -->
    <input
        type="text"
        id="certInput"
        placeholder="Enter Certificate No"
        autofocus
        class="qr-input"
    >

    <!-- Optional Button (you can remove if you want pure ENTER only) -->
    <button
        onclick="goToVerify(document.getElementById('certInput').value)"
        class="qr-verify-btn"
    >
        Verify
    </button>

    <!-- 📷 Scanner -->
    <div id="reader" class="qr-reader"></div>

</div>

<a class="page-back" href="index.php">Back to certificates</a>

<script>
let scanner;

// ✅ Redirect function
function goToVerify(certNo) {
    if (!certNo) return;
    window.location.href = "view.php?CertNo=" + encodeURIComponent(certNo);
}

// ✅ QR Scan
function onScanSuccess(decodedText) {
    goToVerify(decodedText);
}

// ✅ Start scanner
function startScanner() {
    scanner = new Html5QrcodeScanner("reader", {
        fps: 10,
        qrbox: 250
    });

    scanner.render(onScanSuccess);
}

// ✅ ENTER key
document.getElementById("certInput").addEventListener("keypress", function(e) {
    if (e.key === "Enter") {
        goToVerify(this.value.trim());
    }
});

// ✅ Load
window.onload = function() {
    startScanner();
};
</script>

</body>
</html>