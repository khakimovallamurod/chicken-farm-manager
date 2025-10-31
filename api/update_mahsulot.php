<?php
    include_once '../config.php';

    $db = new Database();

    $data = [
        'nomi' => $_POST['nomi'] ?? '',
        'categoriya_id' => $_POST['kategoriya_id'] ?? 0,
        'birlik_id' => $_POST['birlik_id'] ?? 0,
        'narxi' => $_POST['narxi'] ?? 0,
        'tavsif' => $_POST['tavsif'] ?? '',
        'update_at' => date('Y-m-d H:i:s')
    ];
    $id = $_POST['id'] ?? 0;

    $result = $db->update('mahsulotlar', $data, "id = '$id'");
    
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Mahsulot muvaffaqiyatli yangilandi']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Mahsulot yangilashda xatolik']);
    }
?>