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
    <link rel="stylesheet" href="./styles/print_unloading.css">
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
                <div class="field-value"><?php echo strtoupper(htmlspecialchars($header['client'] ?? '')); ?></div>
            </div>
            <div class="field-row">
                <div class="field-label">VARIETY:</div>
                <div class="field-value"><?php echo strtoupper(htmlspecialchars($header['variety_hybrid'] ?? '')); ?></div>
            </div>
            <div class="field-row">
                <div class="field-label">MATERIAL GROUP:</div>
                <div class="field-value"><?php echo strtoupper(htmlspecialchars($header['material_group'] ?? '')); ?></div>
            </div>
            <div class="field-row">
                <div class="field-label">LOT NO:</div>
                <div class="field-value"><?php echo strtoupper(htmlspecialchars($header['lot_number'] ?? '')); ?></div>
            </div>
            <div class="field-row">
                <div class="field-label">BATCH NO:</div>
                <div class="field-value"><?php echo strtoupper(htmlspecialchars($header['batch_number'] ?? '')); ?></div>
            </div>
            <div class="field-row">
                <div class="field-label">TIME START:</div>
                <div class="field-value">
                    <?php echo !empty($header['time_start'])
                        ? date("g:i A", strtotime($header['time_start']))
                        : ''; ?>
                </div>
            </div>

            <div class="field-row">
                <div class="field-label">TIME FINISH:</div>
                <div class="field-value">
                    <?php echo !empty($header['time_finished'])
                        ? date("g:i A", strtotime($header['time_finished']))
                        : ''; ?>
                </div>
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
                        <td style="font-weight: 500;"><?php echo htmlspecialchars($item['jb_pallet'] ?? ''); ?></td>
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
            <div class="remarks-line"><?php echo htmlspecialchars($header['remarks'] ?? ''); ?></div>
            <div class="remarks-line"></div>
        </div>

        <!-- Signatures -->
        <div class="signatures">
            <div class="signature-item">
                <div class="signature-line"></div>
                <div class="signature-name"><?php echo strtoupper(htmlspecialchars($header['prepared_by'] ?? '')); ?></div>
                <div class="signature-title">PREPARED BY</div>
            </div>
            <div class="signature-item">
                <div class="signature-line"></div>
                <div class="signature-name"><?php echo strtoupper(htmlspecialchars($header['checked_by'] ?? '')); ?></div>
                <div class="signature-title">CHECKED BY</div>
            </div>
        </div>


    </div>
</body>

</html>