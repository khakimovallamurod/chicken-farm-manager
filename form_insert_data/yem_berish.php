<?php
    include_once '../config.php';
    $db = new Database();
    $data = json_decode(file_get_contents('php://input'), true);
    header('Content-Type: application/json');
    $katak_id = $data['katak_id'] ?? 0;
    $sana = $data['sana'] ?? '';
    $yem_turi = $data['yem_turi'] ?? '';
    $yem_miqdori = $data['miqdori'] ?? 0;
    $izoh = $data['izoh'] ?? '';
    $yem_data = [
        'katak_id' => $katak_id,
        'sana' => $sana,
        'mahsulot_id' => $yem_turi,
        'miqdori' => $yem_miqdori,
        'izoh' => $izoh
    ];
    
    $mahsulot_one = $db->get_data_by_table_all('mahsulot_zahirasi', ' WHERE mahsulot_id = '.$yem_turi);
    $delta = (int)$mahsulot_one[0]['soni'] - (int)$yem_miqdori;
    if ($delta < 0){
        echo json_encode(['success' => false, 'message' => 'Yem mahsuloti yetarli emas.']);
    }else{
        $insert = $db->insert('yem_berish', $yem_data);
        if ($insert) {
            $db->update('mahsulot_zahirasi', ['soni'=>$delta], 'mahsulot_id = '.$yem_turi);
            echo json_encode(['success' => true, 'message' => 'Yem muvaffaqiyatli berildi!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Yem berishda xatolik yuz berdi.']);
        }
    }

?>