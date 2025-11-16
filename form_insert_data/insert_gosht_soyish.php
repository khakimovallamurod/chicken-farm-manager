<?php
    include_once '../config.php';
    $db = new Database();
    header('Content-Type: application/json'); 

    $katak_id = $_POST['katak_id'] ?? '';
    $soni = intval(str_replace(' ', '', $_POST['soni'])) ?? 0;
    $massasi = intval(str_replace(' ', '', $_POST['massasi'])) ?? 0;
    $sana = $_POST['sana'] ?? '';
    $izoh = $_POST['izoh'] ?? '';

    $gosht_data = [
        'katak_id' => $katak_id,
        'joja_soni' => $soni,
        'massasi'=>$massasi,
        'sana' => $sana,
        'izoh' => $izoh
    ];

    $insert = $db->insert('gosht_soyish', $gosht_data);

    if ($insert) {
        echo json_encode([
            'status' => 'success',
            'message' => "✅ Go'sht topshirish muvaffaqiyatli qo'shildi!"
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => "❗ Go'sht topshirishda xatolik yuz berdi."
        ]);
    }
