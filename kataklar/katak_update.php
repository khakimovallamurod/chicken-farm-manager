<?php
    include_once '../config.php';

    $db = new Database();

    $data = [
        'katak_nomi' => $_POST['nomi'] ?? '',
        'sigimi' => $_POST['sigimi'] ?? 0,
        'izoh' => $_POST['izoh'] ?? '',
        'updated_at' => date('Y-m-d H:i:s')
    ];
    $id = $_POST['id'] ?? 0;

    $result = $db->update('kataklar', $data, "id = '$id'");
    
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Katak muvaffaqiyatli yangilandi']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Katakni yangilashda xatolik']);
    }
?>