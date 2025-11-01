<?php
    include_once '../config.php';
    $db = new Database();
    header('Content-Type: application/json'); 
    $mahsulot_nomi = $_POST['nomi'] ?? '';
    $mahsulot_kategoriya = $_POST['kategoriya'] ?? '';
    $mahsulot_narxi = floatval(str_replace(' ', '', $_POST['narxi'])) ?? 0;
    $mahsulot_tavsif = $_POST['tavsif'] ?? '';
    $mahsulot_birlik = $_POST['birlik'] ?? '';
    
    $mahsulot_data = [
        'nomi' => $mahsulot_nomi,
        'categoriya_id' => $mahsulot_kategoriya,
        'narxi' => $mahsulot_narxi,
        'tavsif' => $mahsulot_tavsif,
        'birlik_id' => $mahsulot_birlik
    ];
    $insert = $db->insert('mahsulotlar', $mahsulot_data);
    header('Content-Type: application/json');
    if ($insert) {
        echo json_encode(['status' => 'success', 'message' => 'Yangi mahsulot muvaffaqiyatli qo\'shildi!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Mahsulot qo\'shishda xatolik yuz berdi.']);
    }

?>