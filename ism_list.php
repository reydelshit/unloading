<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$connLocal = new mysqli("localhost", "root", "", "ism");

if ($connLocal->connect_error) {
    die("Connection failed: " . $connLocal->connect_error);
}

$result = $connLocal->query("
    SELECT *
    FROM ism_header
    ORDER BY id DESC
");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>ISM List | Monitoring System</title>
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

        .stats-row {
            padding: 1rem 2rem 0 2rem;
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            border-bottom: 1px solid #f5f5ed;
        }

        .stat-chip {
            background: #FFFDF5;
            border-radius: 2rem;
            padding: 0.3rem 1rem;
            font-size: 0.75rem;
            color: #c28b00;
            border: 1px solid #FFEAB0;
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
        }

        th {
            text-align: left;
            padding: 1rem 1rem;
            background-color: #FFFDF5;
            font-weight: 600;
            color: #4a4a5a;
            border-bottom: 2px solid #FFEAB0;
            font-size: 0.75rem;
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

        .ism-no {
            font-weight: 700;
            color: #1e1e2a;
            background: #FFF7E0;
            padding: 0.2rem 0.5rem;
            border-radius: 1rem;
            display: inline-block;
            font-size: 0.8rem;
        }

        .btn {
            border: none;
            padding: 0.4rem 1rem;
            cursor: pointer;
            border-radius: 2rem;
            font-size: 0.7rem;
            font-weight: 500;
            text-decoration: none;
            display: inline-block;
            transition: all 0.2s ease;
            margin-right: 0.4rem;
            margin-bottom: 0.2rem;
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

        .btn-print {
            background: #ffffff;
            border: 1px solid #FFE078;
            color: #c28b00;
        }

        .btn-print:hover {
            background: #FFFBE6;
            border-color: #FFD44A;
            transform: translateY(-1px);
        }

        .btn-delete {
            background: #ffffff;
            border: 1px solid #ffe0b3;
            color: #b87c00;
        }

        .btn-delete:hover {
            background: #FFEDB5;
            border-color: #FFD966;
        }

        .packaging-badge {
            background: #FEF6E0;
            padding: 0.2rem 0.6rem;
            border-radius: 2rem;
            font-size: 0.7rem;
            font-weight: 500;
            color: #b87c00;
            display: inline-block;
        }

        @media (max-width: 1000px) {
            body {
                padding: 1rem;
            }

            .table-wrapper {
                padding: 1rem;
            }

            .header {
                padding: 1.2rem 1.2rem 0.8rem 1.2rem;
            }

            .stats-row {
                padding: 0.8rem 1.2rem 0 1.2rem;
            }

            th,
            td {
                padding: 0.7rem 0.8rem;
            }

            .btn {
                padding: 0.3rem 0.7rem;
                font-size: 0.65rem;
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
                📋 ISM Header List
                <span class="badge">Stellar Seeds Corp.</span>
            </h2>
            <div class="subnote">
                View, print, and manage all Inspection and Sampling Monitoring records
            </div>
        </div>

        <?php
        $totalCount = $result->num_rows;
        ?>
        <div class="stats-row">
            <div class="stat-chip">📄 Total ISM Records: <?= $totalCount ?></div>

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
                                <td><?= htmlspecialchars($row['origin_site']) ?></td>
                                <td><?= htmlspecialchars($row['transfer_site']) ?></td>
                                <td><?= htmlspecialchars($row['bin_no']) ?></td>
                                <td>
                                    <?php
                                    $type = $row['isJB'];
                                    $packagingLabel = match ($type) {
                                        '1' => 'JB',
                                        '0' => 'PALLET',
                                        '2' => 'POUCH',
                                        '3' => 'SACKS',
                                        '4' => 'BAGS',
                                        default => 'N/A'
                                    };
                                    ?>
                                    <span class="packaging-badge"><?= $packagingLabel ?></span>
                                </td>
                                <td><?= htmlspecialchars($row['prepared_by']) ?></td>
                                <td><?= htmlspecialchars($row['verified_by']) ?></td>
                                <td><?= date('Y-m-d', strtotime($row['date'])) ?></td>
                                <td class="action-cell">
                                    <a class="btn btn-view" href="print_ism.php?id=<?= $row['ism_id'] ?>">
                                        👁️ View
                                    </a>
                                    <a class="btn btn-print" href="print_ism.php?id=<?= $row['ism_id'] ?>" target="_blank">
                                        🖨️ Print
                                    </a>
                                    <a class="btn btn-delete" href="delete_ism.php?id=<?= $row['ism_id'] ?>"
                                        onclick="return confirm('Delete this ISM record? This action cannot be undone.')">
                                        🗑️ Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>