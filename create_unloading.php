<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

include 'db.php';

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
$isJB = $_POST['isJB'] ?? 0;

$stmt = $connLocal->prepare("
    INSERT INTO unloading 
    (client, variety_hybrid, material_group, lot_number, batch_number, time_start, time_finished, prepared_by, checked_by, remarks, created_at, isJB)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "sssssssssssi",
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
    $created_at,
    $isJB
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

echo "
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Success</title>

    <style>
        body{
            margin:0;
            height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            background:#f4f6f9;
            font-family:Arial, sans-serif;
        }

        .success-box{
            background:#fff;
            padding:40px;
            border-radius:16px;
            box-shadow:0 4px 20px rgba(0,0,0,0.1);
            text-align:center;
            width:350px;
        }

        .success-icon{
            font-size:60px;
            color:#28a745;
            margin-bottom:15px;
        }

        .success-title{
            font-size:24px;
            font-weight:bold;
            color:#333;
            margin-bottom:10px;
        }

        .success-text{
            color:#666;
            margin-bottom:25px;
        }

        .btn{
            display:inline-block;
            padding:12px 22px;
            background:#007bff;
            color:#fff;
            text-decoration:none;
            border-radius:10px;
            transition:0.3s;
            font-weight:bold;
        }

        .btn:hover{
            background:#0056b3;
        }

          .btn-back {
            background: #1e1e2a;
            border: none;
            border-radius: 2rem;
            padding: 0.7rem 2rem;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            color: white;
            transition: all 0.2s;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        }

        .btn-back:hover {
            background: #2c2c3a;
            transform: translateY(-1px);
            box-shadow: 0 8px 20px -8px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>
<body>

    <div class='success-box'>
        <div class='success-icon'>✔</div>

        <div class='success-title'>
            Saved Successfully!
        </div>

        <div class='success-text'>
            Your unloading record has been saved.
        </div>

        <a href='index.php' class='btn btn-back'>
            Back to Dashboard
        </a>
    </div>

</body>
</html>
";
