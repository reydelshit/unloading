<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "db.php";

$unloading_id = $_GET['id'] ?? '';
if (empty($unloading_id)) {
    die("No Unloading Record Selected");
}

$stmt = $connLocal->prepare("SELECT * FROM unloading WHERE unloading_id = ?");
$stmt->bind_param("s", $unloading_id);
$stmt->execute();
$headerResult = $stmt->get_result();

if ($headerResult->num_rows == 0) {
    die("Unloading record not found");
}

$header = $headerResult->fetch_assoc();

// Fetch items from unloading_items table
$stmtItems = $connLocal->prepare("SELECT * FROM unloading_items WHERE unloading_id = ? ORDER BY itemorder");
$stmtItems->bind_param("s", $unloading_id);
$stmtItems->execute();
$itemsResult = $stmtItems->get_result();

$totalBags = 0;
$totalWeight = 0;
$items = [];
while ($row = $itemsResult->fetch_assoc()) {
    $totalBags += $row['bags_sacks_no'];
    $totalWeight += $row['total_weight'];
    $items[] = $row;
}

$rowCount = count($items);
$totalRows = 10;
$isJB = $header['isJB'] ?? 0;
?>

<!DOCTYPE html>
<html>

<head>
    <title>Unloading Monitoring - <?php echo htmlspecialchars($unloading_id); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', 'Arial', sans-serif;
        }

        /* 4 inches width = 101.6mm, using 4in for exact */
        body {
            background: #fff;
            padding: 0;
            margin: 0;
            width: 4in;
            min-height: 5.83in;
            display: flex;
            justify-content: center;
            margin: 0 auto;
        }

        .form-container {
            width: 100%;
            background: white;
            padding: 0.1in;
        }

        .header {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin-bottom: 5px;
            /* padding-bottom: 4px; */

        }

        .header img {
            height: 22px;
            width: auto;
        }

        .company-info {
            text-align: center;
            font-size: 6px;
            line-height: 1.2;
            font-weight: 500;
        }

        h2 {
            text-align: center;
            font-size: 10px;
            font-weight: 700;
            margin: 5px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .unloading-no {
            text-align: right;
            margin-bottom: 8px;
            font-weight: 600;
            font-size: 8px;
        }

        .unloading-no span {
            color: #c00;
            font-size: 9px;
        }

        .form-fields {
            margin-bottom: 8px;
        }

        .field-row {
            display: flex;
            align-items: baseline;
            margin-bottom: 5px;
            font-size: 8px;
        }

        .field-label {
            width: 30%;
            font-weight: 600;
            font-size: 7.5px;
        }

        .field-value {
            flex: 1;
            border-bottom: 0.5px solid #000;
            padding-left: 5px;
            font-size: 7.5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 6px 0;
            font-size: 6px;
        }

        table,
        th,
        td {
            border: 0.5px solid #000;
        }

        th {
            background-color: #e6e6e6;
            font-weight: 700;
            padding: 3px 2px;
            text-align: center;
            font-size: 6px;
            text-transform: uppercase;
        }

        td {
            padding: 3px 2px;
            text-align: center;
            vertical-align: middle;
            font-size: 6px;
        }

        th:nth-child(1) {
            width: 12%;
        }

        th:nth-child(2) {
            width: 30%;
        }

        th:nth-child(3) {
            width: 18%;
        }

        th:nth-child(4) {
            width: 18%;
        }

        th:nth-child(5) {
            width: 22%;
        }

        .checkbox-group {
            display: flex;
            flex-direction: row;
            gap: 8px;
            align-items: center;
            justify-content: center;
        }

        .checkbox-group div {
            display: flex;
            align-items: center;
            gap: 3px;
            white-space: nowrap;
            font-size: 5px;
        }

        .checkbox-group input {
            margin: 0;
            width: 7px;
            height: 7px;
        }

        .totals-row {
            background-color: #e6e6e6;
            font-weight: 700;
        }

        .totals-row td {
            padding: 3px 2px;
            font-size: 7px;
        }

        .remarks-section {
            margin: 6px 0;
            padding: 2px 0;
        }

        .remarks-label {
            font-weight: 600;
            margin-bottom: 3px;
            font-size: 7px;
        }

        .remarks-line {
            border-bottom: 0.5px solid #000;
            padding: 4px 0;
            min-height: 18px;
            font-size: 7px;
        }

        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 8px;
        }

        .signature-item {
            width: 45%;
        }

        .signature-item:first-child {
            text-align: left;
        }

        .signature-item:last-child {
            text-align: right;
        }

        .signature-line {
            border-top: 0.5px solid #000;
            margin: 8px 0 3px 0;
            width: 100%;
        }

        .signature-name {
            font-weight: 600;
            font-size: 6px;
            text-transform: uppercase;
            margin-bottom: 1px;
        }

        .signature-title {
            font-size: 5px;
            color: #555;
        }

        /* Print button */
        .print-btn-container {
            text-align: center;
            margin-top: 10px;
        }

        .print-btn {
            background: #1a4a1a;
            color: white;
            border: none;
            padding: 4px 12px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: 600;
            cursor: pointer;
        }

        .print-btn:hover {
            background: #0f3a0f;
        }

        /* Print optimization - 4 inches width */
        @media print {
            body {
                width: 4in;
                padding: 0;
                margin: 0;
            }

            .form-container {
                padding: 0.1in;
            }

            .print-btn-container {
                display: none;
            }

            th {
                background-color: #e6e6e6 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .totals-row {
                background-color: #e6e6e6 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            table,
            th,
            td {
                border: 0.5px solid black !important;
            }

            @page {
                size: 4in 5.83in;
                margin: 0;
            }
        }
    </style>
</head>

<body>
    <div class="form-container">
        <!-- Header -->
        <div class="header">
            <img src="./assets/stellar_logo.png" alt="Stellar Seeds">
            <div class="company-info">
                <strong>STELLAR SEEDS CORPORATION</strong><br>
                LOT 171, Glamang, Polomolok, South Cotabato
            </div>
        </div>

        <h2>UNLOADING MONITORING FORM</h2>

        <!-- Unloading No -->
        <div class="unloading-no">
            NO: <span><?php echo htmlspecialchars($unloading_id); ?></span>
        </div>

        <div class="form-fields">
            <div class="field-row">
                <div class="field-label">DATE:</div>
                <div class="field-value"><?php echo date('F j, Y', strtotime($header['created_at'])); ?></div>
            </div>
            <div class="field-row">
                <div class="field-label">CLIENT:</div>
                <div class="field-value"><?php echo strtoupper(htmlspecialchars($header['client'] ?? '')); ?></div>
            </div>
            <div class="field-row">
                <div class="field-label">VARIETY/HYBRID:</div>
                <div class="field-value"><?php echo strtoupper(htmlspecialchars($header['variety_hybrid'] ?? '')); ?></div>
            </div>
            <div class="field-row">
                <div class="field-label">MATERIAL GROUP:</div>
                <div class="field-value"><?php echo strtoupper(htmlspecialchars($header['material_group'] ?? '')); ?></div>
            </div>
            <div class="field-row">
                <div class="field-label">LOT NUMBER:</div>
                <div class="field-value"><?php echo strtoupper(htmlspecialchars($header['lot_number'] ?? '')); ?></div>
            </div>
            <div class="field-row">
                <div class="field-label">BATCH NUMBER:</div>
                <div class="field-value"><?php echo strtoupper(htmlspecialchars($header['batch_number'] ?? '')); ?></div>
            </div>
            <div class="field-row">
                <div class="field-label">TIME START:</div>
                <div class="field-value"><?php echo htmlspecialchars($header['time_start'] ?? ''); ?></div>
            </div>
            <div class="field-row">
                <div class="field-label">TIME FINISHED:</div>
                <div class="field-value"><?php echo htmlspecialchars($header['time_finished'] ?? ''); ?></div>
            </div>
        </div>

        <!-- Table -->
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>
                        <div class="checkbox-group">
                            <?php if ($isJB == 1): ?>
                                <div><input type="checkbox" disabled> PALLET</div>
                                <div><input type="checkbox" checked disabled> JB</div>
                            <?php else: ?>
                                <div><input type="checkbox" checked disabled> PALLET</div>
                                <div><input type="checkbox" disabled> JB</div>
                            <?php endif; ?>
                        </div>
                    </th>
                    <th>BAGS</th>
                    <th>KG/BAG</th>
                    <th>TOTAL KG</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($items as $index => $item):
                    $containerType = ($isJB == 1) ? 'JB' : 'PAL';
                ?>
                    <tr>
                        <td><?php echo str_pad($index + 1, 2, '0', STR_PAD_LEFT); ?></td>
                        <td style="font-weight: 500;"><?php echo $containerType; ?> <?php echo htmlspecialchars(substr($item['jb_pallet'] ?? '', 0, 8)); ?></td>
                        <td><?php echo number_format($item['bags_sacks_no']); ?></td>
                        <td><?php echo number_format($item['weight'], 0); ?></td>
                        <td><?php echo number_format($item['total_weight'], 0); ?></td>
                    </tr>
                <?php endforeach; ?>

                <?php for ($i = $rowCount; $i < $totalRows; $i++): ?>
                    <tr>
                        <td><?php echo str_pad($i + 1, 2, '0', STR_PAD_LEFT); ?></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                <?php endfor; ?>

                <tr class="totals-row">
                    <td colspan="2" style="text-align: right; font-weight: 700;">TOTAL:</td>
                    <td style="font-weight: 700;"><?php echo number_format($totalBags); ?></td>
                    <td>&nbsp;</td>
                    <td style="font-weight: 700;"><?php echo number_format($totalWeight, 0); ?></td>
                </tr>
            </tbody>
        </table>

        <div class="remarks-section">
            <div class="remarks-label">REMARKS:</div>
            <div class="remarks-line"><?php echo htmlspecialchars($header['remarks'] ?? ''); ?></div>
        </div>

        <!-- Signatures -->
        <div class="signatures">
            <div class="signature-item">
                <div class="signature-line"></div>
                <div class="signature-name"><?php echo strtoupper(substr(htmlspecialchars($header['prepared_by'] ?? ''), 0, 15)); ?></div>
                <div class="signature-title">PREPARED BY</div>
            </div>
            <div class="signature-item">
                <div class="signature-line"></div>
                <div class="signature-name"><?php echo strtoupper(substr(htmlspecialchars($header['checked_by'] ?? ''), 0, 15)); ?></div>
                <div class="signature-title">CHECKED BY</div>
            </div>
        </div>

        <div class="print-btn-container">
            <button class="print-btn" onclick="window.print()">🖨️ PRINT</button>
        </div>
    </div>
</body>

</html>