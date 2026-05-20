<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include "db.php";
include "data.php";



// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);



$ism_id = $_GET['id'] ?? '';
if (empty($ism_id)) {
    die("No ISM selected");
}

$stmt = $connLocal->prepare("SELECT * FROM ism_header WHERE ism_id = ? ");
$stmt->bind_param("s", $ism_id);
$stmt->execute();
$headerResult = $stmt->get_result();

if ($headerResult->num_rows == 0) {
    die("ISM not found");
}

$header = $headerResult->fetch_assoc();

$stmtItems = $connLocal->prepare("SELECT * FROM ism_items WHERE ism_id = ? ORDER BY itemorder");
$stmtItems->bind_param("s", $ism_id);
$stmtItems->execute();
$itemsResult = $stmtItems->get_result();

// Calculate totals
$totalBags = 0;
$totalWeight = 0;
$items = [];
while ($row = $itemsResult->fetch_assoc()) {
    $totalBags += $row['bags_sacks_no'];
    $totalWeight += $row['total_weight'];
    $items[] = $row;
}


$ismNo = $header['ism_no'];
$seasonCode = ($header['season'] == '2') ? 'W' : 'D';
$ismNoFormatted = preg_replace('/^SSC-(\d{2})-/', "SSC-{$seasonCode}\\1-", $ismNo);



// var_dump($header);
// var_dump($items);

?>

<!DOCTYPE html>
<html>

<head>
    <title>ISM Print - <?php echo htmlspecialchars($ismNoFormatted); ?></title>
    <link rel="stylesheet" href="./styles/print_page.css">

</head>

<body>

    <div class=" header">
        <a href="/unloading_ism"><img src="./assets/stellar_logo.png" alt="Company Logo"></a>

        <div class="company-info">
            <strong>STELLAR SEEDS CORPORATION</strong><br>
            LOT 171, Glamang, Polomolok, South Cotabato., PH-9504
        </div>
    </div>

    <h2>INVENTORY STOCK MOVEMENT</h2>

    <div class="client-cont">
        <div style="font-size: 1rem;"><b>CLIENT:</b> <?php echo strtoupper(htmlspecialchars($header['client_name'])); ?></div>
        <div>
            <b><span style="color: black;">ISM No: </span>
                <span style="color: red; font-size: 1rem"><?php echo htmlspecialchars($ismNoFormatted); ?></span></b>
        </div>
    </div>


    <div style="border:1px solid #000; padding:10px; display:flex; justify-content:space-between; font-size: 13px;">


        <div style="width:48%; border-right:1px solid #000; padding-right:10px;">
            <div style="margin-bottom:5px; ">
                <b>Lot no:</b>
                <?php echo strtoupper($header['lot_number'] ?? 'N/A'); ?>
            </div>
            <div style="margin-bottom:5px; ">
                <b>Origin Site:</b>
                <?php echo strtoupper($origin_site[$header['origin_site']] ?? 'N/A'); ?>
            </div>
            <div>
                <b>Transfer Site:</b>
                <?php echo strtoupper($transfer_site[$header['transfer_site']] ?? 'N/A'); ?>
            </div>

        </div>

        <div style="width:48%; padding-left:10px;">

            <div style="margin-bottom:5px;">
                <b>Bin no:</b>
                <?php echo strtoupper($header['bin_no'] ?? 'N/A'); ?>
            </div>
            <div style="margin-bottom:5px; "> <b>Date:</b>
                <?php echo date('F j, Y', strtotime($header['date'])); ?>
            </div>


            <div>
                <b>Flagging:</b>
                <?php echo !empty($header['flagging'])
                    ? htmlspecialchars($flagging[$header['flagging']])
                    : 'N/A'; ?>
            </div>
        </div>

    </div>

    <table>
        <thead>
            <tr>
                <th>NO.</th>
                <th>
                    <div class="isJb">
                        <?php if ($header['isJB']): ?>
                            <div>
                                <input type="checkbox" disabled> PALLET No.
                            </div>

                            <div>
                                <input type="checkbox" checked disabled> JB No.
                            </div>
                        <?php else: ?>
                            <div>
                                <input type="checkbox" checked disabled> PALLET No.

                            </div>
                            <div>
                                <input type="checkbox" disabled> JB No.

                            </div>
                        <?php endif; ?>
                    </div>
                </th>
                <th>Variety / Hybrid</th>
                <th>Material Group</th>
                <th>Batch Number / Lot Number</th>
                <th>No. of Bags / Sacks</th>
                <th>Weight (Per Bag)</th>
                <th>Total Weight (KG)</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $rowCount = count($items);
            $totalRows = 25;

            foreach ($items as $index => $item): ?>
                <tr>
                    <td><?php echo str_pad($index + 1, 2, '0', STR_PAD_LEFT); ?></td>
                    <td><strong><?php echo htmlspecialchars($item['jb_pallet']); ?></strong></td>
                    <td><?php echo htmlspecialchars($item['variety_hybrid']); ?></td>
                    <td><?php echo htmlspecialchars($item['material_group']); ?></td>
                    <td><?php echo htmlspecialchars($item['batch_lot_number']); ?></td>
                    <td><?php echo number_format($item['bags_sacks_no']); ?></td>
                    <td><?php echo number_format($item['weight'], 2); ?></td>
                    <td><?php echo number_format($item['total_weight'], 2); ?></td>
                    <td><?php echo htmlspecialchars($item['remarks'] ?: '-'); ?></td>
                </tr>
            <?php endforeach; ?>


            <!-- <?php if ($rowCount > 0): ?>

                <tr>
                    <td><?php echo str_pad($rowCount + 1, 2, '0', STR_PAD_LEFT); ?></td>
                    <td colspan="8">&nbsp;</td>
                </tr>

                <tr>
                    <td><?php echo str_pad($rowCount + 2, 2, '0', STR_PAD_LEFT); ?></td>
                    <td colspan="8" style="text-align:center; font-weight:bold;">
                        --------------------------NF----------------------------
                    </td>
                </tr>

                <?php $rowCount += 2; ?>
            <?php endif; ?> -->

            <?php for ($i = $rowCount; $i < $totalRows; $i++): ?>
                <tr>
                    <td><?php echo str_pad($i + 1, 2, '0', STR_PAD_LEFT); ?></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            <?php endfor; ?>

            <tr class="totals-row">
                <td colspan="5" style="text-align:right; font-weight:bold; font-size: 15px;"> TOTAL:</td>
                <td style="font-weight:bold; font-size: 15px;"><?php echo number_format($totalBags); ?>

                    <!-- <?php echo ($header['isJB'] == '1') ? 'Jumbo' : ' Pallet'; ?> -->

                    <?php echo strtoupper($containerType[$header['isJB']] ?? 'N/A'); ?>

                </td>
                <td></td>
                <td style="font-weight:bold; font-size: 15px;">
                    <?php echo number_format($totalWeight, 2); ?> KGS
                </td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 5rem;" class="signatures">
        <div class="signature-line">
            _______________________ <br />
            <strong style="font-size: 15px;"><?php echo strtoupper(htmlspecialchars($prepared_by[$header['prepared_by']]))  ?> </strong><br />

            <span>PREPARED BY</span> <br />
            <?php echo date("F d, Y", strtotime($header['created_at'])); ?>
        </div>

        <div class="signature-line">
            _______________________ <br />
            <strong style="font-size: 15px;"><?php echo strtoupper(htmlspecialchars($verified_by[$header['verified_by']]))  ?> </strong><br />

            <span>VERIFIED BY</span> <br />
            <?php echo date("F d, Y", strtotime($header['created_at'])); ?>
        </div>


        <div class="signature-line">
            _______________________ <br /><br />
            RECEIVED BY <br />
            (Indicate Date)
        </div>
    </div>

    <div class="print-btn-container">
        <button class="print-btn" onclick="window.print()">
            🖨️ Print Document
        </button>
    </div>


</body>

</html>