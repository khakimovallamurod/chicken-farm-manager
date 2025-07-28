<?php
    include_once '../config.php';
    $db = new Database();   
    $data = json_decode(file_get_contents('php://input'), true);
    header('Content-Type: application/json');
    $taminotchi_nomi = $data['nomi'] ?? '';
    $taminotchi_kontakt = $data['kontakt'] ?? '';
    $taminotchi_address = $data['manzil'] ?? '';
    $taminotchi_telefon = $data['telefon'] ?? '';
    $taminotchi_mahsulotlar = $data['mahsulotlar'] ?? '';
    $taminotchi_data = [
        'kompaniya_nomi' => $taminotchi_nomi,
        'fio' => $taminotchi_kontakt,
        'manzil' => $taminotchi_address,
        'telefon' => $taminotchi_telefon,
        'mahsulotlar' => $taminotchi_mahsulotlar
    ];
    $insert = $db->insert('taminotchilar', $taminotchi_data);
    if ($insert) {
        echo json_encode(['success' => true, 'message' => 'Yangi ta\'minotchi muvaffaqiyatli qo\'shildi!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Ta\'minotchi qo\'shishda xatolik yuz berdi.']);
    }

?>