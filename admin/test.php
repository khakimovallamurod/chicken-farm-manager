<?php
    include_once '../config.php';
    header('Content-Type: application/json');

    $db = new Database();
    $data = [
        "mijoz_id"=> "19",
        "summa"=> "12",
        "sana"=> "2025-07-28",
        "tolov_usuli"=> "2",
        "izoh"=> "test"
    ];
    $mijoz_id = $data['mijoz_id'] ?? null;
    $summa = $data['summa'] ?? null;
    $sana = $data['sana'] ?? null;
    $tolov_usuli = $data['tolov_usuli'] ?? null;
    $izoh = $data['izoh'] ?? '';

    if (!$mijoz_id || !$summa || !$sana || !$tolov_usuli) {
        echo json_encode(['success' => false, 'message' => 'Barcha maydonlarni to‘ldiring.']);
        exit;
    }
    $data = [
        'mijoz_id' => $mijoz_id,
        'summa' => $summa,
        'sana' => $sana,
        'tolov_birlik_id' => $tolov_usuli,
        'izoh' => $izoh
    ];
    $insert = $db->insert('pul_olish', $data);

    if ($insert) {
        echo json_encode(['success' => true, 'message' => '✅ Pul muvaffaqiyatli qayd etildi.']);
    } else {
        echo json_encode(['success' => false, 'message' => '❌ Ma’lumotni qo‘shishda xatolik yuz berdi.']);
    }
?>
