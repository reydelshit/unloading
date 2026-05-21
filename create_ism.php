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

$warehouseRanges = [
    1 => ['min' => 0,        'max' => 24999999],
    2 => ['min' => 25000000, 'max' => 49999999],
    3 => ['min' => 50000000, 'max' => 74999999],
    4 => ['min' => 75000000, 'max' => 99999999],
];


// change this based on warehouse
$warehouse_id = 2;

$range = $warehouseRanges[$warehouse_id];

// generate random number within range
$randomNumber = rand($range['min'], $range['max']);

// format ISM number
$ism_no = "SSC-" . date("y") . "-" . str_pad($randomNumber, 8, '0', STR_PAD_LEFT);



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

header("Location: print_ism.php?id=" . urlencode($ism_id));
exit;
