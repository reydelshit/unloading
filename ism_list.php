<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "db.php";
include "data.php";

if ($connLocal->connect_error) {
    die("Connection failed: " . $connLocal->connect_error);
}

$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if (!empty($search)) {
    $searchParam = "%$search%";
    $countStmt = $connLocal->prepare("
        SELECT COUNT(*) as total
        FROM ism_header
        WHERE ism_no LIKE ? OR client_name LIKE ? OR lot_number LIKE ? OR origin_site LIKE ? OR transfer_site LIKE ? OR bin_no LIKE ? OR prepared_by LIKE ? OR verified_by LIKE ?
    ");
    $countStmt->bind_param("ssssssss", $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam);
    $countStmt->execute();
    $countResult = $countStmt->get_result();
    $totalRows = $countResult->fetch_assoc()['total'];
    $countStmt->close();

    $stmt = $connLocal->prepare("
        SELECT *
        FROM ism_header
        WHERE ism_no LIKE ? OR client_name LIKE ? OR lot_number LIKE ? OR origin_site LIKE ? OR transfer_site LIKE ? OR bin_no LIKE ? OR prepared_by LIKE ? OR verified_by LIKE ?
        ORDER BY id DESC
        LIMIT ? OFFSET ?
    ");
    $stmt->bind_param("ssssssssii", $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $limit, $offset);
} else {
    $countResult = $connLocal->query("SELECT COUNT(*) as total FROM ism_header");
    $totalRows = $countResult->fetch_assoc()['total'];

    $stmt = $connLocal->prepare("
        SELECT *
        FROM ism_header
        ORDER BY id DESC
        LIMIT ? OFFSET ?
    ");
    $stmt->bind_param("ii", $limit, $offset);
}

$stmt->execute();
$result = $stmt->get_result();

$totalPages = ceil($totalRows / $limit);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>ISM List | Monitoring System</title>
    <link rel="stylesheet" href="./styles/ism_list.css">
</head>

<body>

    <a href="javascript:history.back()" class="btn-back">← Back</a>

    <div class="dashboard-container">
        <div class="header" style="display: flex; justify-content: space-between; align-items: center; ">
            <div class="headera">
                <h2>
                    📋 ISM Header List
                    <span class="badge">Stellar Seeds Corp.</span>
                </h2>
                <div class="subnote">
                    View, print, and manage all Inspection and Sampling Monitoring records — 10 items per page
                </div>
            </div>

            <div class="search-section">
                <form method="GET" action="" class="search-box">
                    <input type="text" name="search" placeholder="Search ISM No, client, lot, bin..." value="<?= htmlspecialchars($search) ?>" autocomplete="off">
                    <button type="submit">Search</button>
                    <?php if (!empty($search)): ?>
                        <a href="?" class="clear-btn">Clear</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <div class="stats-row">
            <div class="stat-chip">📄 Total ISM Records: <?= $totalRows ?></div>
            <div class="stat-chip">📄 Showing <?= $result->num_rows ?> of <?= $totalRows ?></div>
        </div>

        <div class="info-bar">
            <span>Page <?= $page ?> of <?= $totalPages ?></span>
            <span><?= $limit ?> items per page</span>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ISM No</th>
                        <th>Client</th>
                        <th>Lot Number</th>
                        <th>Origin Site</th>
                        <th>Transfer Site</th>
                        <th>Bin No</th>
                        <th>Packaging</th>
                        <th>Prepared By</th>
                        <th>Verified By</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows === 0):
                    ?>
                        <tr>
                            <td colspan="12" style="text-align: center; padding: 3rem; color: #a0a0b0;">
                                ✨ No ISM records found. Generate your first ISM from Unloading List.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['id']) ?></td>
                                <td><span class="ism-no"><?= htmlspecialchars($row['ism_no']) ?></span></td>
                                <td><?= htmlspecialchars($row['client_name']) ?></td>
                                <td><?= htmlspecialchars($row['lot_number']) ?></td>
                                <td><?= htmlspecialchars($origin_site[$row['origin_site']] ?? $row['origin_site']) ?></td>
                                <td><?= htmlspecialchars($transfer_site[$row['transfer_site']] ?? $row['transfer_site']) ?></td>
                                <td><?= htmlspecialchars($row['bin_no']) ?></td>
                                <td>
                                    <span class="packaging-badge"><?= $containerType[$row['isJB']] ?? ($row['isJB'] == '1' ? 'JB' : 'Other') ?></span>
                                </td>
                                <td><?= htmlspecialchars($prepared_by[$row['prepared_by']] ?? $row['prepared_by']) ?></td>
                                <td><?= htmlspecialchars($verified_by[$row['verified_by']] ?? $row['verified_by']) ?></td>
                                <td><?= date('Y-m-d', strtotime($row['date'])) ?></td>
                                <td class="action-cell">
                                    <a class="btn btn-print" href="print_ism.php?id=<?= $row['ism_id'] ?>" target="_blank">
                                        🖨️ Print
                                    </a>
                                    <a class="btn btn-view" href="print_ism.php?id=<?= $row['ism_id'] ?>">
                                        👁️ View
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>">← Previous</a>
                <?php else: ?>
                    <span class="disabled">← Previous</span>
                <?php endif; ?>

                <?php
                $startPage = max(1, $page - 2);
                $endPage = min($totalPages, $page + 2);

                if ($startPage > 1) {
                    echo '<a href="?page=1&search=' . urlencode($search) . '">1</a>';
                    if ($startPage > 2) echo '<span>...</span>';
                }

                for ($i = $startPage; $i <= $endPage; $i++):
                ?>
                    <?php if ($i == $page): ?>
                        <span class="active"><?= $i ?></span>
                    <?php else: ?>
                        <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php
                if ($endPage < $totalPages) {
                    if ($endPage < $totalPages - 1) echo '<span>...</span>';
                    echo '<a href="?page=' . $totalPages . '&search=' . urlencode($search) . '">' . $totalPages . '</a>';
                }
                ?>

                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>">Next →</a>
                <?php else: ?>
                    <span class="disabled">Next →</span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>