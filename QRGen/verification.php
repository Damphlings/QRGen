<?php
if(isset($_GET['verify'])){
    $id = $_GET['verify'];

    $stmt = $pdo->prepare("UPDATE cert SET Status='Verified' WHERE ID=?");
    $stmt->execute([$id]);

    echo "<div class=\"success-box\">Certificate verified.</div>";
}
?>

<h2 class="page-title">Verification Requests</h2>

<div class="table-container">
    <table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Status</th>
        <th>Action</th>
    </tr>

    <?php
    $pendingRows = $pdo->query("SELECT * FROM cert WHERE Status='Pending'")->fetchAll(PDO::FETCH_ASSOC);
    if(!$pendingRows): ?>
    <tr><td colspan="4" class="empty">No certificates are waiting for verification.</td></tr>
    <?php endif;
    foreach($pendingRows as $row):
    ?>
    <tr>
        <td><?= $row['ID'] ?></td>
        <td><?= $row['FName'] . " " . $row['LName'] ?></td>
        <td><span class="badge badge-pending"><?= $row['Status'] ?></span></td>
        <td>
            <a class="button" href="?page=verification&verify=<?= $row['ID'] ?>">Verify</a>
        </td>
    </tr>
    <?php endforeach; ?>
    </table>
</div>