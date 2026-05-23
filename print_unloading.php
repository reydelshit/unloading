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
$totalRows = 20;
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
            font-family: Arial, sans-serif;
        }

        @page {
            size: 105mm 250mm;
            /* 1/2 lengthwise paper */
            margin: 2mm;
        }

        body {
            width: 105mm;
            margin: 0 auto;
            background: #fff;
            font-size: 9px;
        }

        .form-container {
            width: 100%;
            min-height: 250mm;
            padding: 2mm;
            display: flex;
            flex-direction: column;
        }

        .header {
            text-align: center;
            flex-shrink: 0;
            margin-bottom: 1em;
        }

        .header img {
            height: 12mm;
            width: auto;
        }

        .company-info {
            font-size: 8px;
            line-height: 1.2;
        }

        h2 {
            font-size: 15px;
            margin: 1px 0;
            text-transform: uppercase;
            text-align: center;
        }

        .unloading-no {
            text-align: right;
            font-size: 15px;
            font-weight: bold;
            margin: 2mm;
            flex-shrink: 0;
            color: red;
        }

        .form-fields {
            margin-bottom: 1mm;
            flex-shrink: 0;
        }

        .field-row {
            display: flex;
            font-size: 10px;
            margin-bottom: 1mm;
        }

        .field-label {
            width: 28%;
            font-weight: bold;
        }

        .field-value {
            flex: 1;
            border-bottom: 0.2px solid #000;
            padding-left: 2px;
            white-space: nowrap;
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
            margin: 2mm 0;
            /* padding: 1mm; */
            flex: 1;
        }

        th,
        td {
            border: 0.2px solid #000;
            padding: 1mm;
            text-align: center;
            font-size: 8px;
        }

        th {
            background: #e6e6e6;
            font-size: 8px;
            text-transform: uppercase;
        }

        .totals-row td {
            font-size: 12px;
        }

        th:nth-child(1) {
            width: 10%;
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
            width: 24%;
        }

        .remarks-label {
            font-size: 10px;
            font-weight: bold;
            margin-top: 1mm;
            flex-shrink: 0;
        }

        .remarks-line {
            border-bottom: 0.2px solid #000;
            min-height: 5mm;
            font-size: 12px;
        }

        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 3mm;
            flex-shrink: 0;
        }

        .signature-item {
            width: 45%;
            text-align: center;
        }

        .signature-line {
            border-top: 0.2px solid #000;
            margin-top: 5mm;
        }

        .signature-name {
            font-size: 12px;
            font-weight: bold;
        }

        .signature-title {
            font-size: 10px;
        }

        .checkbox-group {
            line-height: 1.2;
        }

        @media print {
            body {
                width: 105mm;
                margin: 4mm;
                /* border: 1px solid #000; */
            }

            .form-container {
                min-height: 250mm;
                padding: 2mm;
            }

            table {
                font-size: 6px;
            }




        }
    </style>
</head>

<body>
    <div class="form-container">
        <div class="header">
            <img src="./assets/stellar_logo.png" alt="Stellar Seeds">
            <div class="company-info">
                <strong>STELLAR SEEDS CORPORATION</strong><br>
                LOT 171, Glamang, Polomolok, South Cotabato
            </div>
        </div>

        <h2>UNLOADING MONITORING FORM</h2>

        <div class="unloading-no">
            NO: <span><?php echo sprintf('%06d', $unloading_id); ?></span>
        </div>

        <div class="form-fields">
            <div class="field-row">
                <div class="field-label">DATE:</div>
                <div class="field-value"><?php echo date('m/d/Y', strtotime($header['created_at'])); ?></div>
            </div>
            <div class="field-row">
                <div class="field-label">CLIENT:</div>
                <div class="field-value"><?php echo strtoupper(substr(htmlspecialchars($header['client'] ?? ''), 0, 20)); ?></div>
            </div>
            <div class="field-row">
                <div class="field-label">VARIETY:</div>
                <div class="field-value"><?php echo strtoupper(substr(htmlspecialchars($header['variety_hybrid'] ?? ''), 0, 15)); ?></div>
            </div>
            <div class="field-row">
                <div class="field-label">MATERIAL GROUP:</div>
                <div class="field-value"><?php echo strtoupper(substr(htmlspecialchars($header['material_group'] ?? ''), 0, 12)); ?></div>
            </div>
            <div class="field-row">
                <div class="field-label">LOT NO:</div>
                <div class="field-value"><?php echo strtoupper(substr(htmlspecialchars($header['lot_number'] ?? ''), 0, 12)); ?></div>
            </div>
            <div class="field-row">
                <div class="field-label">BATCH NO:</div>
                <div class="field-value"><?php echo strtoupper(substr(htmlspecialchars($header['batch_number'] ?? ''), 0, 12)); ?></div>
            </div>
            <div class="field-row">
                <div class="field-label">TIME START:</div>
                <div class="field-value"><?php echo htmlspecialchars($header['time_start'] ?? ''); ?></div>
            </div>
            <div class="field-row">
                <div class="field-label">TIME FINISH:</div>
                <div class="field-value"><?php echo htmlspecialchars($header['time_finished'] ?? ''); ?></div>
            </div>
        </div>

        <!-- Table -->
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>
                        <div class="checkbox-group">
                            <?php if ($isJB == 1): ?>
                                <div><input type="checkbox" disabled> PALLET No.</div>
                                <div><input type="checkbox" checked disabled> JB No.</div>
                            <?php else: ?>
                                <div><input type="checkbox" checked disabled> PALLET No.</div>
                                <div><input type="checkbox" disabled> JB No.</div>
                            <?php endif; ?>
                        </div>
                    </th>
                    <th>BAGS</th>
                    <th>KG/B</th>
                    <th>TOTAL</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($items as $index => $item):
                ?>
                    <tr>
                        <td><?php echo str_pad($index + 1, 2, '0', STR_PAD_LEFT); ?></td>
                        <td style="font-weight: 500;"><?php echo htmlspecialchars(substr($item['jb_pallet'] ?? '', 0, 6)); ?></td>
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
                    <td style="font-weight: 700;"><?php echo number_format($totalWeight, 0); ?> KG</td>
                </tr>
            </tbody>
        </table>

        <div class="remarks-section">
            <div class="remarks-label">REMARKS:</div>
            <div class="remarks-line"><?php echo htmlspecialchars(substr($header['remarks'] ?? '', 0, 35)); ?></div>
            <div class="remarks-line"></div>
        </div>

        <!-- Signatures -->
        <div class="signatures">
            <div class="signature-item">
                <div class="signature-line"></div>
                <div class="signature-name"><?php echo strtoupper(substr(htmlspecialchars($header['prepared_by'] ?? ''), 0, 12)); ?></div>
                <div class="signature-title">PREPARED BY</div>
            </div>
            <div class="signature-item">
                <div class="signature-line"></div>
                <div class="signature-name"><?php echo strtoupper(substr(htmlspecialchars($header['checked_by'] ?? ''), 0, 12)); ?></div>
                <div class="signature-title">CHECKED BY</div>
            </div>
        </div>


    </div>
</body>

</html>