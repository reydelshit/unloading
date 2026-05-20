<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "data.php";

$connLocal = new mysqli("localhost", "root", "", "ism");

if ($connLocal->connect_error) {
    die("Connection failed: " . $connLocal->connect_error);
}


$result = $connLocal->query("
    SELECT 
        u.*,
        h.ism_id
    FROM unloading u
    LEFT JOIN ism_header h
        ON u.unloading_id = h.unloading_id
    ORDER BY u.unloading_id DESC
");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Unloading List | Monitoring System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #ffffff;
            font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, Helvetica, sans-serif;
            line-height: 1.5;
            color: #1e1e2a;
            padding: 2rem 1.5rem;
        }

        .dashboard-container {
            max-width: 1440px;
            width: 100%;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 2rem;
            box-shadow: 0 20px 35px -12px rgba(0, 0, 0, 0.05), 0 1px 2px rgba(0, 0, 0, 0.02);
            border: 1px solid #f0f0ee;
            overflow: hidden;
        }

        .header {
            background: #ffffff;
            padding: 1.8rem 2rem 1rem 2rem;
            border-bottom: 2px solid #FFE770;
        }

        .header h2 {
            font-size: 1.65rem;
            font-weight: 600;
            letter-spacing: -0.01em;
            background: linear-gradient(135deg, #1e1e2a 0%, #2c2c3a 100%);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
            display: inline-block;
        }

        .badge {
            display: inline-block;
            background: #FFF7D6;
            padding: 0.2rem 0.7rem;
            border-radius: 40px;
            font-size: 0.7rem;
            font-weight: 500;
            color: #b17f00;
            margin-left: 0.75rem;
            vertical-align: middle;
        }

        .subnote {
            color: #5e5e6e;
            font-size: 0.85rem;
            margin-top: 0.5rem;
            border-left: 3px solid #FFDC4A;
            padding-left: 0.8rem;
        }

        .table-wrapper {
            overflow-x: auto;
            padding: 1.5rem 2rem 2rem 2rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 1.25rem;
            overflow: hidden;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
        }

        th {
            text-align: left;
            padding: 1rem 1rem;
            background-color: #FFFDF5;
            font-weight: 600;
            color: #4a4a5a;
            border-bottom: 2px solid #FFEAB0;
            font-size: 0.8rem;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }

        td {
            padding: 0.9rem 1rem;
            border-bottom: 1px solid #f5f5ed;
            font-size: 0.85rem;
            color: #2a2a36;
            vertical-align: middle;
        }

        tr:hover td {
            background-color: #FFFEF8;
        }

        .btn {
            border: none;
            padding: 0.45rem 1rem;
            cursor: pointer;
            border-radius: 2rem;
            font-size: 0.75rem;
            font-weight: 500;
            text-decoration: none;
            display: inline-block;
            transition: all 0.2s ease;
            margin-right: 0.5rem;
            margin-bottom: 0.25rem;
        }

        .btn-primary {
            background: #ffffff;
            border: 1px solid #FFE078;
            color: #c28b00;
        }

        .btn-primary:hover {
            background: #FFFBE6;
            border-color: #FFD44A;
            transform: translateY(-1px);
        }

        .btn-view {
            background: #ffffff;
            border: 1px solid #e0e0d8;
            color: #5a5a6e;
        }

        .btn-view:hover {
            background: #f9f9f5;
            border-color: #ccccbf;
            transform: translateY(-1px);
        }

        .btn-danger {
            background: #ffffff;
            border: 1px solid #ffe0b3;
            color: #b87c00;
        }

        .btn-danger:hover {
            background: #FFEDB5;
            border-color: #FFD966;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(2px);
        }

        .modal-content {
            width: 520px;
            max-width: 90%;
            background: white;
            margin: 5% auto;
            padding: 1.8rem;
            border-radius: 1.5rem;
            box-shadow: 0 30px 40px -20px rgba(0, 0, 0, 0.2);
            border: 1px solid #f0f0e8;
        }

        .modal-content h3 {
            font-size: 1.35rem;
            font-weight: 600;
            margin-bottom: 1.2rem;
            color: #1e1e2a;
            border-left: 4px solid #FFDC4A;
            padding-left: 0.8rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            font-size: 0.7rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #8e8e9e;
            margin-bottom: 0.3rem;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.6rem 0.9rem;
            border: 1px solid #e9e9e2;
            border-radius: 1rem;
            font-family: inherit;
            font-size: 0.85rem;
            background: #ffffff;
            transition: all 0.2s;
            outline: none;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #FFDC4A;
            box-shadow: 0 0 0 3px rgba(255, 220, 74, 0.2);
        }

        .modal-actions {
            display: flex;
            gap: 0.8rem;
            margin-top: 1.5rem;
            justify-content: flex-end;
        }

        .modal-actions button {
            padding: 0.6rem 1.4rem;
            border-radius: 2rem;
            border: none;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-save-modal {
            background: #1e1e2a;
            color: white;
        }

        .btn-save-modal:hover {
            background: #2c2c3a;
            transform: translateY(-1px);
        }

        .btn-close-modal {
            background: #ffffff;
            border: 1px solid #e9e9e2;
            color: #7a7a8a;
        }

        .btn-close-modal:hover {
            background: #f5f5ef;
        }

        @media (max-width: 760px) {
            body {
                padding: 1rem;
            }

            .table-wrapper {
                padding: 1rem;
            }

            .header {
                padding: 1.2rem 1.2rem 0.8rem 1.2rem;
            }

            th,
            td {
                padding: 0.7rem 0.8rem;
            }

            .btn {
                padding: 0.35rem 0.8rem;
                font-size: 0.7rem;
            }
        }

        .action-cell {
            white-space: nowrap;
        }
    </style>
</head>

<body>
    <div class="dashboard-container">
        <div class="header">
            <h2>
                📋 Unloading Header List
                <span class="badge">Stellar Seeds Corp.</span>
            </h2>
            <div class="subnote">
                Manage unloading records, generate ISM, and view detailed reports
            </div>
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
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal" id="ismModal">
        <div class="modal-content">
            <h3>Create ISM Document</h3>
            <form method="POST" action="create_ism.php">
                <input type="hidden" name="unloading_id" id="unloading_id">
                <div class="form-group">
                    <label>Packaging Type</label>
                    <select name="isJB" required>
                        <option value="">-- Select --</option>
                        <option value="1">JB</option>
                        <option value="0">PALLET</option>
                        <option value="2">POUCH</option>
                        <option value="3">SACKS</option>
                        <option value="4">BAGS</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Season</label>
                    <select name="season" required>
                        <option value="1">Dry</option>
                        <option value="2">Wet</option>
                    </select>
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
                    <label>Bin No</label>
                    <input type="text" name="bin_no" required placeholder="e.g., BIN-101">
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