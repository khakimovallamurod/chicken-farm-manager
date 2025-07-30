<?php
    header('Content-Type: application/json');
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['katak_id'])) {
        echo json_encode(['success' => false, 'message' => 'katak_id yuborilmadi']);
        exit;
    }
    $katakId = intval($data['katak_id']);
    include_once '../config.php'; 
    $db = new Database();

    $result = $db->get_data_by_table_all('joja',  ' WHERE katak_id='.$katakId.' ORDER BY sana DESC');
   
    echo json_encode([
        'success' => true,
        'data' => $result
    ]);
?>