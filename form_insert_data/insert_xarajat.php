<?php
    include_once '../config.php';

    $db = new Database();
    $input = json_decode(file_get_contents('php://input'), true);

    $turi = $input['turi'];
    $tolov = $input['tolov'];
    $miqdor = intval(str_replace(' ', '', $input['miqdor']));
    $sana = $input['sana'];
    $izoh = $input['izoh'];
    $data = [
        'harajat_turi_id'=>$turi,
        'tolov_birlik_id'=>$tolov,
        'miqdori'=>$miqdor,
        'sana'=>$sana,
        'izoh'=>$izoh
    ];
    $insert = $db->insert('harajatlar', $data);

    if ($insert) {
        echo json_encode(['success' => true, 'message' => '✅ Harajat muvaffaqiyatli qo‘shildi']);
    } else {
        echo json_encode(['success' => false, 'message' => '❌ Harajat qo‘shishda xatolik yuz berdi']);
    }
?>
