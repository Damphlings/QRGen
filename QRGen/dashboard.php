<?php
include "config.php";

/* ======================
   SEARCH
====================== */
$search = $_GET['search'] ?? '';

if($search){
    $stmt = $pdo->prepare("
        SELECT * FROM cert 
        WHERE FName LIKE ? 
        OR LName LIKE ? 
        OR CertNo LIKE ?
    ");
    $stmt->execute([
        "%$search%",
        "%$search%",
        "%$search%"
    ]);
} else {
    $stmt = $pdo->query("SELECT * FROM cert");
}

/* ======================
   COUNTS
====================== */
$total = $pdo->query("SELECT COUNT(*) FROM cert")->fetchColumn();
$verified = $pdo->query("SELECT COUNT(*) FROM cert WHERE Status='Verified'")->fetchColumn();
$pending = $pdo->query("SELECT COUNT(*) FROM cert WHERE Status='Pending'")->fetchColumn();
?>

<h1 class="dashboard-title">Certificate Dashboard</h1>

<div class="dashboard-wrapper">

    <!-- TOP ACTION BAR (SWAPPED) -->
    <div class="top-actions">

        <!-- DELETE (LEFT SIDE) -->
        <button 
            type="button" 
            onclick="if(confirm('Delete selected certificates?')) document.getElementById('delete-form').submit();"
            class="delete-btn"
        >
            Delete selected
        </button>

        <!-- SEARCH (RIGHT SIDE) -->
        <form method="GET" class="search-form">
            <input type="hidden" name="page" value="dashboard">

            <input 
                type="text" 
                name="search" 
                placeholder="Search by name or certificate number"
                value="<?= htmlspecialchars($search ?? '') ?>"
            >

            <button type="submit" class="search-btn">
                Search
            </button>

        </form>

    </div>

    <!-- DELETE FORM -->
    <form id="delete-form" method="POST" action="bulk_delete.php">

    <!-- CARDS -->
    <div class="cards">
        <div>
            <span class="stat-label">Total issued</span>
            <span class="stat-value"><?= $total ?></span>
        </div>
        <div class="stat-verified">
            <span class="stat-label">Verified</span>
            <span class="stat-value"><?= $verified ?></span>
        </div>
        <div class="stat-pending">
            <span class="stat-label">Pending</span>
            <span class="stat-value"><?= $pending ?></span>
        </div>
    </div>

    <!-- TABLE -->
    <div class="table-wrap">
    <table>
        <tr>
            <th><input type="checkbox" id="selectAll"></th>
            <th>ID</th>
            <th>Certificate Number</th>
            <th>Name</th>
            <th>Category</th>
            <th>Status</th>
            <th>QR Code</th>
            <th>Download QR</th>
            <th>Action</th>
        </tr>

        <?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>

            <td>
                <input type="checkbox" name="ids[]" value="<?= $row['ID'] ?>">
            </td>

            <td><?= $row['ID'] ?></td>
            <td><?= $row['CertNo'] ?></td>
            <td><?= $row['FName'] . " " . $row['LName'] ?></td>
            <td><?= $row['Category'] ?></td>
            <td><span class="badge badge-<?= strtolower($row['Status']) ?>"><?= $row['Status'] ?></span></td>

            <td>
                <?php if(!empty($row['qr'])): ?>
                    <img src="data:image/png;base64,<?= base64_encode($row['qr']) ?>" width="60">
                <?php endif; ?>
            </td>

            <td>
                <a class="button btn-green" href="download_qr.php?cert=<?= $row['CertNo'] ?>">
                    Download
                </a>
            </td>

            <td>
                <a class="button" href="edit.php?id=<?= $row['ID'] ?>">
                    Edit
                </a>
            </td>

        </tr>
        <?php endwhile; ?>

    </table>
    </div>

    </form>

</div>

<!-- SELECT ALL -->
<script>
document.getElementById("selectAll").addEventListener("click", function(){
    let boxes = document.querySelectorAll("input[name='ids[]']");
    boxes.forEach(b => b.checked = this.checked);
});
</script>