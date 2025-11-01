<?php
    include_once '../config.php';
    $db = new Database();

    $data = json_decode(file_get_contents('php://input'), true);
    header('Content-Type: application/json');

    $katak_id = $data['katak_id'] ?? 0;
    $mahsulot_id = $data['kategoriya'] ?? '';
    $soni = intval(str_replace(' ', '', $data['soni'])) ?? 0;
    $sana = $data['sana'] ?? '';
    $izoh = $data['izoh'] ?? '';

    // Oxirgi narxni olish
    $kirim_by_mahsulot = $db->get_data_by_table('kirim_mahsulotlar', ['mahsulot_id'=>$mahsulot_id], " ORDER BY id DESC");
    $narxi = isset($kirim_by_mahsulot['narxi']) ? floatval($kirim_by_mahsulot['narxi']) : 0;

    // Joja ma'lumotlarini tayyorlash
    $joja_data = [
        'katak_id' => $katak_id,
        'soni' => $soni,
        'sana' => $sana,
        'izoh' => $izoh,
        'mahsulot_id' => $mahsulot_id,
        'narxi' => $narxi,
        'summa' => $narxi * $soni
    ];

    // Mahsulot zahirasini tekshirish
    $mahsulot_one = $db->get_data_by_table_all('mahsulot_zahirasi', ' WHERE mahsulot_id = '.$mahsulot_id);
    $available = isset($mahsulot_one[0]['soni']) ? (int)$mahsulot_one[0]['soni'] : 0;

    $delta = $available - (int)$soni;

    if ($delta < 0) {
        echo json_encode(['success' => false, 'message' => 'Joja yetarli emas.']);
    } else {
        $insert = $db->insert('joja', $joja_data);
        if ($insert) {
            $db->update('kataklar', ['status' => 'active'], "id = '$katak_id'");
            $db->update('mahsulot_zahirasi', ['soni' => $delta], 'mahsulot_id = '.$mahsulot_id);
            echo json_encode(['success' => true, 'message' => 'Yangi jo\'jalar muvaffaqiyatli qo\'shildi!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Jo\'jalar qo\'shishda xatolik yuz berdi.']);
        }
    }
?>
