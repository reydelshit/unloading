<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "data.php";
include 'db.php';

if ($connLocal->connect_error) {
    die("Connection failed: " . $connLocal->connect_error);
}

$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$searchCondition = "";
$searchParam = "";
if (!empty($search)) {
    $searchParam = "%$search%";
    $searchCondition = "WHERE (u.client LIKE ? OR u.variety_hybrid LIKE ? OR u.material_group LIKE ? OR u.lot_number LIKE ? OR u.batch_number LIKE ? OR u.prepared_by LIKE ?)";
}

if (!empty($search)) {
    $countStmt = $connLocal->prepare("
        SELECT COUNT(*) as total
        FROM unloading u
        LEFT JOIN ism_header h ON u.unloading_id = h.unloading_id
        $searchCondition
    ");
    $countStmt->bind_param("ssssss", $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam);
    $countStmt->execute();
    $countResult = $countStmt->get_result();
    $totalRows = $countResult->fetch_assoc()['total'];
    $countStmt->close();

    $stmt = $connLocal->prepare("
        SELECT 
            u.*,
            h.ism_id
        FROM unloading u
        LEFT JOIN ism_header h ON u.unloading_id = h.unloading_id
        $searchCondition
        ORDER BY u.unloading_id DESC
        LIMIT ? OFFSET ?
    ");
    $stmt->bind_param("ssssssii", $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $limit, $offset);
} else {
    $countResult = $connLocal->query("
        SELECT COUNT(*) as total
        FROM unloading u
        LEFT JOIN ism_header h ON u.unloading_id = h.unloading_id
    ");
    $totalRows = $countResult->fetch_assoc()['total'];

    $stmt = $connLocal->prepare("
        SELECT 
            u.*,
            h.ism_id
        FROM unloading u
        LEFT JOIN ism_header h ON u.unloading_id = h.unloading_id
        ORDER BY u.unloading_id DESC
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
    <title>Unloading List | Monitoring System</title>
    <link rel="stylesheet" href="./styles/unloading_list.css">
</head>

<body>

    <a href="javascript:history.back()" class="btn-back">← Back</a>

    <div class="dashboard-container">
        <div class="header">
            <div class="headera">
                <h2>
                    📋 Unloading Header List
                    <span class="badge">Stellar Seeds Corp.</span>
                </h2>
                <div class="subnote">
                    Manage unloading records, generate ISM, and view detailed reports — 10 items per page
                </div>
            </div>

            <form method="GET" action="" class="search-box">
                <div class="form-group">
                    <input type="text" style="width: 20rem;" name="search" placeholder="Search client, variety, lot, batch..." value="<?= htmlspecialchars($search) ?>" autocomplete="off">
                </div>
                <button type="submit">Search</button>
                <?php if (!empty($search)): ?>
                    <a href="?" class="clear-btn" style="padding: 0.6rem 1.4rem;">Clear</a>
                <?php endif; ?>
            </form>
        </div>



        <div style="margin-top: 2rem;" class="info-bar">
            <span>Showing <?= $result->num_rows ?> of <?= $totalRows ?> records</span>
            <span>Page <?= $page ?> of <?= $totalPages ?> (<?= $limit ?> per page)</span>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Client</th>
                        <th>Variety / Hybrid</th>
                        <th>Material Group</th>
                        <th>Lot Number</th>
                        <th>Batch Number</th>
                        <th>Prepared By</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows === 0): ?>
                        <tr>
                            <td colspan="9" style="text-align: center; padding: 3rem; color: #a0a0b0;">
                                🔍 No unloading records found
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['unloading_id']) ?></td>
                                <td><?= htmlspecialchars($row['client']) ?></td>
                                <td><?= htmlspecialchars($row['variety_hybrid']) ?></td>
                                <td><?= htmlspecialchars($row['material_group']) ?></td>
                                <td><?= htmlspecialchars($row['lot_number']) ?></td>
                                <td><?= htmlspecialchars($row['batch_number']) ?></td>
                                <td><?= htmlspecialchars($row['prepared_by']) ?></td>
                                <td><?= htmlspecialchars($row['created_at']) ?></td>
                                <td class="action-cell">
                                    <?php if (!empty($row['ism_id'])): ?>
                                        <a class="btn btn-view" href="print_ism.php?id=<?= $row['ism_id'] ?>" target="_blank">
                                            📄 View ISM
                                        </a>
                                    <?php else: ?>
                                        <button class="btn btn-primary" onclick="openModal('<?= $row['unloading_id'] ?>')">
                                            ✨ Generate ISM
                                        </button>
                                    <?php endif; ?>
                                    <a class="btn btn-view" href="print_unloading.php?id=<?= $row['unloading_id'] ?>" target="_blank">
                                        🚛 View Unloading
                                    </a>
                                    <a class="btn btn-view" href="edit_unloading.php?id=<?= $row['unloading_id'] ?>">
                                        ✏️ Edit
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

    <div class="modal" id="ismModal">
        <div class="modal-content">
            <h3>Create ISM Document</h3>
            <form method="POST" action="create_ism.php">
                <input type="hidden" name="unloading_id" id="unloading_id">
                <div class="two-columns">
                    <div class="form-group">
                        <label>Season</label>
                        <select name="season" required>
                            <option value="1">Dry</option>
                            <option value="2">Wet</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Bin No</label>
                        <input type="text" name="bin_no" required placeholder="e.g., BIN-101">
                    </div>
                    <div class="form-group">
                        <label>Origin Site</label>
                        <select name="origin_site" required>
                            <option value="">Select</option>
                            <?php foreach ($origin_site as $id => $site): ?>
                                <option value="<?= $id ?>"><?= htmlspecialchars($site) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Transfer Site</label>
                        <select name="transfer_site" required>
                            <option value="">Select</option>
                            <?php foreach ($transfer_site as $id => $site): ?>
                                <option value="<?= $id ?>"><?= htmlspecialchars($site) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Prepared By</label>
                        <select name="prepared_by" required>
                            <option value="">Select</option>
                            <?php foreach ($prepared_by as $id => $name): ?>
                                <option value="<?= $id ?>"><?= htmlspecialchars($name) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Verified By</label>
                        <select name="verified_by" required>
                            <option value="">Select</option>
                            <?php foreach ($verified_by as $id => $name): ?>
                                <option value="<?= $id ?>"><?= htmlspecialchars($name) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Flagging</label>
                        <select name="flagging">
                            <option value="">Select</option>
                            <?php foreach ($flagging as $id => $site): ?>
                                <option value="<?= $id ?>"><?= htmlspecialchars($site) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-close-modal" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn-save-modal">Save ISM</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById("ismModal").style.display = "block";
            document.getElementById("unloading_id").value = id;
            document.body.style.overflow = "hidden";
        }

        function closeModal() {
            document.getElementById("ismModal").style.display = "none";
            document.body.style.overflow = "auto";
        }

        window.onclick = function(event) {
            const modal = document.getElementById("ismModal");
            if (event.target === modal) {
                closeModal();
            }
        }
    </script>
</body>

</html>