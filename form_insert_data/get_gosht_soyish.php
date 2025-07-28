<?php
    include_once '../config.php';
    $db = new Database();
    header('Content-Type: application/json');

    $gosht_id = $_POST['gosht_topshirish_id'] ?? null;
    $mahsulot_id = $_POST['mahsulot_id'] ?? null;
    $miqdor = $_POST['miqdor'] ?? 0;

    $data = [
        'gosht_soyish_id' => $gosht_id,
        'mahsulot_id' => $mahsulot_id,
        'soni' => $miqdor,
    ];

    $insert = $db->insert('gosht_soyish_mahsulot', $data);

    if ($insert) {
        echo json_encode(['status' => 'success', 'message' => 'Mahsulot qo‘shildi']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Bazaga yozishda xatolik']);
    }
?>