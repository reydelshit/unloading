<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$response = ['success' => false, 'message' => '', 'data' => null];

$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    $response['message'] = 'Invalid request data';
    echo json_encode($response);
    exit();
}

$lotNumber = isset($input['lot_number']) ? trim($input['lot_number']) : '';
$phaseType = isset($input['phase_type']) ? trim($input['phase_type']) : '';

if (empty($lotNumber)) {
    $response['message'] = 'Lot number is required';
    echo json_encode($response);
    exit();
}

if (empty($phaseType)) {
    $response['message'] = 'Phase type is required';
    echo json_encode($response);
    exit();
}

$onlineHost = 'srv2188.hstgr.io';
$onlineUser = 'u321237277_stellarsys';
$onlinePass = 'Stellar2025*';
$onlineDb = 'u321237277_stellarsys';

$onlineConn = new mysqli($onlineHost, $onlineUser, $onlinePass, $onlineDb);

if ($onlineConn->connect_error) {
    $response['message'] = 'Online database connection failed: ' . $onlineConn->connect_error;
    echo json_encode($response);
    exit();
}


$stmt = $onlineConn->prepare("
    SELECT 
        *
    FROM tbl_tags 
    WHERE lotno = ? AND phasetype = ?
    LIMIT 1
");

if (!$stmt) {
    $response['message'] = 'Database query preparation failed: ' . $onlineConn->error;
    echo json_encode($response);
    $onlineConn->close();
    exit();
}

$stmt->bind_param("ss", $lotNumber, $phaseType);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $response['message'] = "No data found for Lot Number: $lotNumber and Phase Type: $phaseType";
    echo json_encode($response);
    $stmt->close();
    $onlineConn->close();
    exit();
}

$row = $result->fetch_assoc();

$itemsStmt = $onlineConn->prepare("
    SELECT 
        jbno as jb_pallet,
        quantity as weight
    FROM tbl_tags 
    WHERE lotno = ? AND phasetype = ?
");

if ($itemsStmt) {
    $itemsStmt->bind_param("ss", $lotNumber, $phaseType);
    $itemsStmt->execute();
    $itemsResult = $itemsStmt->get_result();

    $items = [];
    $itemIndex = 0;
    while ($itemRow = $itemsResult->fetch_assoc()) {
        $items[] = [
            'jb_pallet' => $itemRow['jb_pallet'],
            'bags_sacks_no' => 1,
            'weight' => $itemRow['weight'],
            'total_weight' => $itemRow['weight']
        ];
        $itemIndex++;
    }
    $itemsStmt->close();
} else {
    $items = [];
}

$containerType = '0';
if (isset($row['type'])) {
    $typeLower = strtolower($row['type']);
    if ($typeLower == 'jb' || $typeLower == 'jumbo') {
        $containerType = '1';
    } elseif ($typeLower == 'pallet') {
        $containerType = '0';
    } elseif ($typeLower == 'pouch') {
        $containerType = '2';
    } elseif ($typeLower == 'sacks') {
        $containerType = '3';
    } elseif ($typeLower == 'bags') {
        $containerType = '4';
    }
}


$response['success'] = true;
$response['message'] = 'Data fetched successfully';
$response['data'] = [
    'lot_number' => $row['lotno'],
    'phase_type' => $row['phasetype'],
    'client' => $row['client'],
    'variety_hybrid' => $row['hybrid'],
    'material_group' => $row['descp'],
    'batch_number' => $row['recid'],
    'isJB' => $containerType,
    'time_start' => '',
    'time_finished' => '',
    'prepared_by' => $row['prepby'],
    'checked_by' => $row['checkby'],
    'remarks' => $row['remarks'],
    'bin_no' => $row['bin'],
    'quality' => $row['quality'],
    'quantity' => $row['quantity'],
    'items' => $items
];

$stmt->close();
$onlineConn->close();

echo json_encode($response);
