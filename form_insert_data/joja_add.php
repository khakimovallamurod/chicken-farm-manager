<?php
    include_once '../config.php';
    $db = new Database();

    $data = json_decode(file_get_contents('php://input'), true);
    header('Content-Type: application/json');
    $katak_id = $data['katak_id'] ?? 0;
    $mahsulot_id = $data['kategoriya'] ?? '';
    $soni = $data['soni'] ?? 0;
    $sana = $data['sana'] ?? '';
    $izoh = $data['izoh'] ?? '';
    $kirim_by_mahsulot = $db->get_data_by_table('kirim_mahsulotlar', ['mahsulot_id'=>$mahsulot_id], " ORDER BY id DESC");
    $joja_data = [
        'katak_id' => $katak_id,
        'soni' => $soni,
        'sana' => $sana,
        'izoh' => $izoh,
        'mahsulot_id' => $mahsulot_id,
        'narxi' => $kirim_by_mahsulot['narxi'],
        'summa' => floatval($kirim_by_mahsulot['narxi'] * $soni)
    ];
    $mahsulot_one = $db->get_data_by_table_all('mahsulot_zahirasi', ' WHERE mahsulot_id = '.$mahsulot_id);
    $delta = (int)$mahsulot_one[0]['soni'] - (int)$soni;
    if ($delta < 0){
        echo json_encode(['success' => false, 'message' => 'Joja yetarli emas.']);
    }else{
        $insert = $db->insert('joja', $joja_data);
        if ($insert) {
            $db->update('kataklar', ['status' => 'active'], "id = '$katak_id'");
            $db->update('mahsulot_zahirasi', ['soni'=>$delta], 'mahsulot_id = '.$mahsulot_id);
            echo json_encode(['success' => true, 'message' => 'Yangi jo\'jalar muvaffaqiyatli qo\'shildi!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Jo\'jalar qo\'shishda xatolik yuz berdi.']);
        }
    }
?>
