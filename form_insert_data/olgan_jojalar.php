<?php
    include_once '../config.php';
    $db = new Database();
    $data = json_decode(file_get_contents('php://input'), true);
    header('Content-Type: application/json');
    $katak_id = $data['katak_id'] ?? 0;
    $soni = $data['soni'] ?? 0;
    $sana = $data['sana'] ?? '';
    $izoh = $data['izoh'] ?? '';
    $olgan_joja_data = [
        'katak_id' => $katak_id,
        'soni' => $soni,
        'sana' => $sana,
        'izoh' => $izoh
    ];
    $insert = $db->insert('olgan_jojalar', $olgan_joja_data);
    if ($insert) {
        echo json_encode(['success' => true, 'message' => 'O\'lgan jo\'jalar muvaffaqiyatli ayirildi!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'O\'lgan jo\'jalarni ayirishda xatolik yuz berdi.']);
    }


?>