<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);


$connLocal = new mysqli("localhost", "root", "", "ism");

if ($connLocal->connect_error) {
    die("Connection failed: " . $connLocal->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request");
}

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
$created_at = date('Y-m-d H:i:s');

$stmt = $connLocal->prepare("
    INSERT INTO unloading 
    (client, variety_hybrid, material_group, lot_number, batch_number, time_start, time_finished, prepared_by, checked_by, remarks, created_at)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "sssssssssss",
    $client,
    $variety_hybrid,
    $material_group,
    $lot_number,
    $batch_number,
    $time_start,
    $time_finished,
    $prepared_by,
    $checked_by,
    $remarks,
    $created_at
);

$stmt->execute();

$unloading_id = $stmt->insert_id;



if (!empty($_POST['items'])) {

    foreach ($_POST['items'] as $i => $item) {

        $jb_pallet = $item['jb_pallet'];
        $bags_sacks_no = $item['bags_sacks_no'];
        $weight = $item['weight'];
        $total_weight = $item['total_weight'];
        $itemorder = $i + 1;

        $stmt2 = $connLocal->prepare("
    INSERT INTO unloading_items 
    (unloading_id, jb_pallet, bags_sacks_no, weight, total_weight, itemorder)
    VALUES (?, ?, ?, ?, ?, ?)
");

        $stmt2->bind_param(
            "isdddi",
            $unloading_id,
            $jb_pallet,
            $bags_sacks_no,
            $weight,
            $total_weight,
            $itemorder
        );
        $stmt2->execute();
    }
}

echo "Saved successfully!";
