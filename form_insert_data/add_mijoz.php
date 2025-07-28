<?php
    include_once '../config.php';
    header('Content-Type: application/json');

    $input = json_decode(file_get_contents("php://input"), true);
    $db = new Database();   
    $mijoz_nomi = $input['ismi'];
    $mijoz_tel = $input['telefon'];
    $mijoz_address = $input['manzil'];
    $mijoz_izoh = $input['izoh'] ?? '';
    $mijoz_data = [
        'mijoz_nomi' => $mijoz_nomi,
        'mijoz_tel' => $mijoz_tel,
        'mijoz_address' => $mijoz_address,
        'mijoz_izoh' => $mijoz_izoh,
    ];
    $insert = $db->insert('mijozlar', $mijoz_data);
    if ($insert) {
        echo json_encode(['success' => true, 'message' => 'Yangi mijoz muvaffaqiyatli qo\'shildi!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Mijoz qo\'shishda xatolik yuz berdi.']);
    }

?>