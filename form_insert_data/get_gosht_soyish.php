<?php
    include_once '../config.php';
    $db = new Database();
    header('Content-Type: application/json');
    $gosht_id = $_POST['gosht_topshirish_id'] ?? null;
    $mahsulot_id = $_POST['mahsulot_id'] ?? null;
    $miqdor = intval(str_replace(' ', '', $_POST['miqdor'])) ?? 0;

    $data = [
        'gosht_soyish_id' => $gosht_id,
        'mahsulot_id' => $mahsulot_id,
        'soni' => $miqdor,
    ];

    $insert = $db->insert('gosht_soyish_mahsulot', $data);
    if ($insert) {
        $mahsulot_zahirasi = $db->get_data_by_table('mahsulot_zahirasi', ['mahsulot_id' => $mahsulot_id]);

        if ($mahsulot_zahirasi) {
            $yangi_soni = $miqdor + $mahsulot_zahirasi['soni'];
            $db->update('mahsulot_zahirasi', ['soni' => $yangi_soni], "mahsulot_id = $mahsulot_id");
        } else {
            $db->insert('mahsulot_zahirasi', [
                'mahsulot_id' => $mahsulot_id,
                'soni' => $miqdor
            ]);
        }
        echo json_encode(['status' => 'success', 'message' => "Mahsulot zahiraga qo‘shildi"]);
    } else {
        echo json_encode(['status' => 'error', 'message' => "Bazaga yozishda xatolik"]);
    }
?>