<?php
include_once '../config.php';
    header('Content-Type: application/json');

    $db = new Database();

    $mijoz_id = $_POST['taminotchi_id'];
    $summa = intval(str_replace(' ', '', $_POST['summa']));
    $sana = $_POST['sana'];
    $tolov_usuli = $_POST['tolov_usuli'];
    $izoh = $_POST['izoh'] ?? '';

    if (!$mijoz_id || !$summa || !$sana || !$tolov_usuli) {
        echo json_encode(['success' => false, 'message' => 'Barcha maydonlarni to‘ldiring.']);
        exit;
    }

    $data = [
        'taminotchi_id' => $mijoz_id,
        'summa' => $summa,
        'sana' => $sana,
        'tolov_birlik_id' => $tolov_usuli,
        'izoh' => $izoh,
    ];

    $insert = $db->insert('pul_berish', $data); 

    if ($insert) {
        $taminotchi_balans = $db->get_data_by_table('taminotchilar', ['id'=>$mijoz_id]);
        $db->update('taminotchilar', ['balans'=>floatval($taminotchi_balans['balans'])-$summa], 'id = '.$mijoz_id);
        echo json_encode(['success' => true, 'message' => '✅ Pul muvaffaqiyatli berildi.']);
    } else {
        echo json_encode(['success' => false, 'message' => '❌ Ma’lumotni qo‘shishda xatolik yuz berdi.']);
    }
?>