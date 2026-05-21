<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "data.php";

$connLocal = new mysqli("localhost", "root", "", "ism");

if ($connLocal->connect_error) {
    die("Connection failed: " . $connLocal->connect_error);
}

$unloading_id = $_GET['id'] ?? '';
if (empty($unloading_id)) {
    die("No Unloading Record Selected");
}

// Handle Update Header
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_header'])) {
    $client = $_POST['client'];
    $variety_hybrid = $_POST['variety_hybrid'];
    $material_group = $_POST['material_group'];
    $lot_number = $_POST['lot_number'];
    $batch_number = $_POST['batch_number'];
    $time_start = $_POST['time_start'];
    $time_finished = $_POST['time_finished'];
    $prepared_by = $_POST['prepared_by'];
    $checked_by = $_POST['checked_by'];
    $remarks = $_POST['remarks'];
    $isJB = $_POST['isJB'] ?? 0;

    $updateStmt = $connLocal->prepare("UPDATE unloading SET client=?, variety_hybrid=?, material_group=?, lot_number=?, batch_number=?, time_start=?, time_finished=?, prepared_by=?, checked_by=?, remarks=?, isJB=? WHERE unloading_id=?");
    $updateStmt->bind_param("sssssssssssi", $client, $variety_hybrid, $material_group, $lot_number, $batch_number, $time_start, $time_finished, $prepared_by, $checked_by, $remarks, $isJB, $unloading_id);

    if ($updateStmt->execute()) {
        $success_msg = "Header updated successfully!";
    } else {
        $error_msg = "Update failed: " . $updateStmt->error;
    }
    $updateStmt->close();
}

// Handle Update Items
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_items'])) {
    $item_ids = $_POST['item_id'] ?? [];
    $jb_pallets = $_POST['jb_pallet'] ?? [];
    $bags_sacks_nos = $_POST['bags_sacks_no'] ?? [];
    $weights = $_POST['weight'] ?? [];
    $total_weights = $_POST['total_weight'] ?? [];

    foreach ($item_ids as $index => $item_id) {
        $jb_pallet = $jb_pallets[$index] ?? '';
        $bags_sacks_no = $bags_sacks_nos[$index] ?? 0;
        $weight = $weights[$index] ?? 0;
        $total_weight = $total_weights[$index] ?? 0;

        $updateItemStmt = $connLocal->prepare("UPDATE unloading_items SET jb_pallet=?, bags_sacks_no=?, weight=?, total_weight=? WHERE id=?");
        $updateItemStmt->bind_param("siddi", $jb_pallet, $bags_sacks_no, $weight, $total_weight, $item_id);
        $updateItemStmt->execute();
        $updateItemStmt->close();
    }
    $success_msg = "All items updated successfully!";
    header("Location: edit_unloading.php?id=" . $unloading_id . "&success=1");
    exit();
}

// Handle Add Item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_item'])) {
    $jb_pallet = $_POST['jb_pallet'];
    $bags_sacks_no = $_POST['bags_sacks_no'];
    $weight = $_POST['weight'];
    $total_weight = $_POST['total_weight']; // Manual input, not calculated

    $orderQuery = $connLocal->query("SELECT MAX(itemorder) as max_order FROM unloading_items WHERE unloading_id='$unloading_id'");
    $orderRow = $orderQuery->fetch_assoc();
    $newOrder = ($orderRow['max_order'] ?? 0) + 1;

    $insertStmt = $connLocal->prepare("INSERT INTO unloading_items (unloading_id, jb_pallet, bags_sacks_no, weight, total_weight, itemorder) VALUES (?, ?, ?, ?, ?, ?)");
    $insertStmt->bind_param("ssiddi", $unloading_id, $jb_pallet, $bags_sacks_no, $weight, $total_weight, $newOrder);

    if ($insertStmt->execute()) {
        $success_msg = "Item added successfully!";
    } else {
        $error_msg = "Add failed: " . $insertStmt->error;
    }
    $insertStmt->close();
    header("Location: edit_unloading.php?id=" . $unloading_id . "&success=1");
    exit();
}

// Handle Delete Item
if (isset($_GET['delete_item'])) {
    $item_id = $_GET['delete_item'];
    $deleteStmt = $connLocal->prepare("DELETE FROM unloading_items WHERE id=?");
    $deleteStmt->bind_param("i", $item_id);
    if ($deleteStmt->execute()) {
        $success_msg = "Item deleted successfully!";
    }
    $deleteStmt->close();
    header("Location: edit_unloading.php?id=" . $unloading_id);
    exit();
}

// Fetch header data
$stmt = $connLocal->prepare("SELECT * FROM unloading WHERE unloading_id = ?");
$stmt->bind_param("s", $unloading_id);
$stmt->execute();
$headerResult = $stmt->get_result();

if ($headerResult->num_rows == 0) {
    die("Unloading record not found");
}
$header = $headerResult->fetch_assoc();
$stmt->close();

// Fetch items
$itemsStmt = $connLocal->prepare("SELECT * FROM unloading_items WHERE unloading_id = ? ORDER BY itemorder");
$itemsStmt->bind_param("s", $unloading_id);
$itemsStmt->execute();
$itemsResult = $itemsStmt->get_result();
$items = [];
while ($row = $itemsResult->fetch_assoc()) {
    $items[] = $row;
}
$itemsStmt->close();

// Calculate totals
$totalBags = 0;
$totalWeight = 0;
foreach ($items as $item) {
    $totalBags += $item['bags_sacks_no'];
    $totalWeight += $item['total_weight'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Unloading - <?php echo htmlspecialchars($unloading_id); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #f5f5ef;
            padding: 2rem;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .card {
            background: white;
            border-radius: 1.5rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
            overflow: hidden;
            border: 1px solid #f0f0e8;
        }

        .card-header {
            background: #ffffff;
            padding: 1.2rem 1.8rem;
            border-bottom: 2px solid #FFE770;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .card-header h2 {
            font-size: 1.3rem;
            font-weight: 600;
            color: #1e1e2a;
        }

        .card-header h2 span {
            color: #c28b00;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .close-tab-btn {
            background: #ffffff;
            border: 1px solid #e0e0d8;
            padding: 0.5rem 1.2rem;
            border-radius: 2rem;
            text-decoration: none;
            color: #5a5a6e;
            font-size: 0.8rem;
            transition: all 0.2s;
            cursor: pointer;
        }

        .close-tab-btn:hover {
            background: #f5f5ef;
        }

        .card-body {
            padding: 1.8rem;
        }

        .alert {
            padding: 0.8rem 1.2rem;
            border-radius: 1rem;
            margin-bottom: 1.5rem;
            font-size: 0.85rem;
        }

        .alert-success {
            background: #e6f7e6;
            border: 1px solid #b3e6b3;
            color: #2d6a2d;
        }

        .alert-error {
            background: #ffe6e6;
            border: 1px solid #ffb3b3;
            color: #cc0000;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
        }

        .form-group label {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            color: #8e8e9e;
            letter-spacing: 0.5px;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            padding: 0.7rem 1rem;
            border: 1px solid #e9e9e2;
            border-radius: 1rem;
            font-size: 0.85rem;
            font-family: inherit;
            transition: all 0.2s;
            outline: none;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            border-color: #FFDC4A;
            box-shadow: 0 0 0 3px rgba(255, 220, 74, 0.2);
        }

        .btn {
            padding: 0.6rem 1.5rem;
            border-radius: 2rem;
            border: none;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 0.8rem;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: #1e1e2a;
            color: white;
        }

        .btn-primary:hover {
            background: #2c2c3a;
            transform: translateY(-1px);
        }

        .btn-success {
            background: #2d6a2d;
            color: white;
        }

        .btn-success:hover {
            background: #1f4f1f;
        }

        .btn-danger {
            background: #cc0000;
            color: white;
        }

        .btn-danger:hover {
            background: #a30000;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        th,
        td {
            padding: 0.8rem;
            text-align: left;
            border-bottom: 1px solid #f0f0e8;
        }

        th {
            background: #FFFDF5;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            color: #8e8e9e;
        }

        td {
            font-size: 0.85rem;
        }

        .totals-row {
            background: #FFFDF5;
            font-weight: 600;
            border-top: 2px solid #FFE770;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 1.5rem;
        }

        .item-edit-input {
            width: 100%;
            padding: 0.4rem 0.6rem;
            border: 1px solid #e9e9e2;
            border-radius: 0.6rem;
            font-size: 0.8rem;
        }

        .add-item-section {
            background: #FFFDF5;
            padding: 1.5rem;
            border-radius: 1rem;
            margin-top: 1.5rem;
            border: 1px solid #f0f0e8;
        }

        .add-item-section h4 {
            margin-bottom: 1rem;
            color: #1e1e2a;
            font-size: 1rem;
        }

        .add-item-form {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr auto;
            gap: 1rem;
            align-items: end;
        }

        .inline-item-input {
            padding: 0.6rem 0.8rem;
            border: 1px solid #e9e9e2;
            border-radius: 0.8rem;
            font-size: 0.8rem;
            width: 100%;
        }

        @media (max-width: 1024px) {
            .add-item-form {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 1rem;
            }

            .add-item-form {
                grid-template-columns: 1fr;
            }

            .card-body {
                padding: 1rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header Card -->
        <div class="card">
            <div class="card-header">
                <h2>
                    ✏️ Edit Unloading Record
                    <span><?php echo htmlspecialchars($unloading_id); ?></span>
                </h2>
                <button class="close-tab-btn" onclick="window.close()">✖ Close Tab</button>
            </div>
            <div class="card-body">
                <?php if (isset($_GET['success']) || isset($success_msg)): ?>
                    <div class="alert alert-success">✅ Changes saved successfully!</div>
                <?php endif; ?>
                <?php if (isset($error_msg)): ?>
                    <div class="alert alert-error">❌ <?php echo $error_msg; ?></div>
                <?php endif; ?>

                <form method="POST">
                    <input type="hidden" name="update_header" value="1">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Client *</label>
                            <input type="text" name="client" value="<?php echo htmlspecialchars($header['client'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Variety / Hybrid *</label>
                            <input type="text" name="variety_hybrid" value="<?php echo htmlspecialchars($header['variety_hybrid'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Material Group *</label>
                            <input type="text" name="material_group" value="<?php echo htmlspecialchars($header['material_group'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Lot Number *</label>
                            <input type="text" name="lot_number" value="<?php echo htmlspecialchars($header['lot_number'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Batch Number *</label>
                            <input type="text" name="batch_number" value="<?php echo htmlspecialchars($header['batch_number'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Time Start</label>
                            <input type="time" name="time_start" value="<?php echo htmlspecialchars($header['time_start'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label>Time Finished</label>
                            <input type="time" name="time_finished" value="<?php echo htmlspecialchars($header['time_finished'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label>Prepared By</label>
                            <input type="text" name="prepared_by" value="<?php echo htmlspecialchars($header['prepared_by'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label>Checked By</label>
                            <input type="text" name="checked_by" value="<?php echo htmlspecialchars($header['checked_by'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label>Remarks</label>
                            <textarea name="remarks" rows="2"><?php echo htmlspecialchars($header['remarks'] ?? ''); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label>Container Type</label>
                            <select name="isJB">
                                <option value="0" <?php echo ($header['isJB'] == 0) ? 'selected' : ''; ?>>Pallet</option>
                                <option value="1" <?php echo ($header['isJB'] == 1) ? 'selected' : ''; ?>>JB</option>
                            </select>
                        </div>
                        <div class="action-buttons">
                            <button type="submit" class="btn btn-primary">💾 Save Header Changes</button>
                        </div>
                </form>
            </div>
        </div>

        <!-- Items Card -->
        <div class="card">
            <div class="card-header">
                <h2>📦 Unloading Items</h2>
                <small style="color: #8e8e9e;">Edit items below, then click "Save All Items"</small>
            </div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="update_items" value="1">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>PALLET / JB NO.</th>
                                <th>NO. OF BAGS</th>
                                <th>WEIGHT (KG/BAG)</th>
                                <th>TOTAL WEIGHT (KG)</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $index => $item): ?>
                                <tr>
                                    <td><?php echo $index + 1; ?>
                                        <input type="hidden" name="item_id[]" value="<?php echo $item['id']; ?>">
                                    </td>
                                    <td>
                                        <input type="text" name="jb_pallet[]" value="<?php echo htmlspecialchars($item['jb_pallet'] ?? ''); ?>" class="item-edit-input">
                                    </td>
                                    <td>
                                        <input type="number" name="bags_sacks_no[]" value="<?php echo $item['bags_sacks_no']; ?>" class="item-edit-input" step="1" min="0">
                                    </td>
                                    <td>
                                        <input type="number" name="weight[]" value="<?php echo $item['weight']; ?>" class="item-edit-input" step="0.5" min="0">
                                    </td>
                                    <td>
                                        <input type="number" name="total_weight[]" value="<?php echo $item['total_weight']; ?>" class="item-edit-input" step="0.5" min="0">
                                    </td>
                                    <td>
                                        <a href="?id=<?php echo $unloading_id; ?>&delete_item=<?php echo $item['id']; ?>" class="btn btn-danger" style="padding: 0.3rem 0.8rem; font-size: 0.7rem;" onclick="return confirm('Delete this item?')">🗑️ Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <tr class="totals-row">
                                <td colspan="2" style="text-align: right; font-weight: bold;">TOTAL:</td>
                                <td id="totalBags"><?php echo number_format($totalBags); ?></td>
                                <td>&nbsp;</td>
                                <td id="totalWeight"><?php echo number_format($totalWeight, 2); ?> KG</td>
                                <td>&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="action-buttons">
                        <button type="submit" class="btn btn-primary">💾 Save All Items Changes</button>
                    </div>
                </form>

                <!-- Add New Item -->
                <div class="add-item-section">
                    <h4>➕ Add New Item</h4>
                    <form method="POST" class="add-item-form">
                        <input type="hidden" name="add_item" value="1">
                        <div class="form-group">
                            <label>Pallet / JB No.</label>
                            <input type="text" name="jb_pallet" placeholder="e.g., PAL-001" class="inline-item-input" required>
                        </div>
                        <div class="form-group">
                            <label>No. of Bags</label>
                            <input type="number" name="bags_sacks_no" step="1" min="1" class="inline-item-input" required>
                        </div>
                        <div class="form-group">
                            <label>Weight per Bag (KG)</label>
                            <input type="number" name="weight" step="0.5" min="0" class="inline-item-input" required>
                        </div>
                        <div class="form-group">
                            <label>Total Weight (KG)</label>
                            <input type="number" name="total_weight" step="0.5" min="0" class="inline-item-input" required>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-success">+ Add Item</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>