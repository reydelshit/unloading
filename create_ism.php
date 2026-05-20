<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);



include "db.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request");
}

$unloading_id = $_POST['unloading_id'];

$origin_site = $_POST['origin_site'];
$transfer_site = $_POST['transfer_site'];
$bin_no = $_POST['bin_no'];
$prepared_by = $_POST['prepared_by'];
$verified_by = $_POST['verified_by'];
$flagging = $_POST['flagging'] ?? null;
$season = $_POST['season'] ?? null;


// ================= GET UNLOADING =================

$stmt = $connLocal->prepare("
    SELECT *
    FROM unloading
    WHERE unloading_id = ?
");

$stmt->bind_param("i", $unloading_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Unloading not found");
}

$unloading = $result->fetch_assoc();



$ism_id = uniqid("ISM");

$ism_no = "SSC-" . date("y") . "-" . rand(10000, 99999);


$stmtInsert = $connLocal->prepare("
    INSERT INTO ism_header
    (
        ism_id,
        ism_no,
        unloading_id,
        client_name,
        lot_number,
        origin_site,
        transfer_site,
        bin_no,
        prepared_by,
        verified_by,
        created_at,
        date,
        flagging,
        season
    )
    VALUES
    (
        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), CURDATE(), ?, ?
    )
");

$stmtInsert->bind_param(
    "ssisssssssii",
    $ism_id,
    $ism_no,
    $unloading['unloading_id'],
    $unloading['client'],
    $unloading['lot_number'],
    $origin_site,
    $transfer_site,
    $bin_no,
    $prepared_by,
    $verified_by,
    $flagging,
    $season
);

$stmtInsert->execute();



$stmtItems = $connLocal->prepare("
    SELECT *
    FROM unloading_items
    WHERE unloading_id = ?
    ORDER BY itemorder
");

$stmtItems->bind_param("i", $unloading_id);
$stmtItems->execute();

$itemsResult = $stmtItems->get_result();

$order = 1;

while ($item = $itemsResult->fetch_assoc()) {

    $batchLot = trim(
        $unloading['batch_number'] . " / " . $unloading['lot_number']
    );

    $stmtInsertItem = $connLocal->prepare("
        INSERT INTO ism_items
        (
            ism_id,
            jb_pallet,
            variety_hybrid,
            material_group,
            batch_lot_number,
            bags_sacks_no,
            weight,
            total_weight,
            remarks,
            itemorder
        )
        VALUES
        (
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
        )
    ");

    $stmtInsertItem->bind_param(
        "sssssiddsi",
        $ism_id,
        $item['jb_pallet'],
        $unloading['variety_hybrid'],
        $unloading['material_group'],
        $batchLot,
        $item['bags_sacks_no'],
        $item['weight'],
        $item['total_weight'],
        $item['remarks'],
        $order
    );

    $stmtInsertItem->execute();

    $order++;
}


header("Location: print_ism.php?id=" . urlencode($ism_id));
exit;
