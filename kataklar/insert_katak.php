<?php
    include_once '../config.php';
    $db = new Database();
    $katak_nomi = $_POST['katak_nomi'];
    $katak_sigimi = $_POST['katak_sigimi'];
    $katak_izoh = $_POST['katak_izoh'];
    $katak_data = [
        'katak_nomi' => $katak_nomi,
        'sigimi' => $katak_sigimi,
        'izoh' => $katak_izoh
    ];
    $insert = $db->insert('kataklar', $katak_data);
    if ($insert) {
        echo json_encode(['status' => 'success', 'message' => 'Yangi katak muvaffaqiyatli qo\'shildi!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Katak qo\'shishda xatolik yuz berdi.']);
    }
?>
