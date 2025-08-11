<?php
    include_once '../config.php';
    header('Content-Type: application/json');

    $db = new Database();
    $mijoz_id = $_POST['mijoz_id'] ?? null;
    $summa = $_POST['summa'] ?? null;
    $sana = $_POST['sana'] ?? null;
    $tolov_usuli = $_POST['tolov_usuli'] ?? null;
    $izoh = $_POST['izoh'] ?? '';

    if (!$mijoz_id || !$summa || !$sana || !$tolov_usuli) {
        echo json_encode(['success' => false, 'message' => 'Barcha maydonlarni to‘ldiring.']);
        exit;
    }
    $data = [
        'mijoz_id' => $mijoz_id,
        'summa' => $summa,
        'sana' => $sana,
        'tolov_birlik_id' => $tolov_usuli,
        'izoh' => $izoh
    ];
    $insert = $db->insert('pul_olish', $data);

    if ($insert) {
        $mijozlar_balans = $db->get_data_by_table('mijozlar', ['id'=>$mijoz_id]);
        $db->update('mijozlar', ['balans'=>floatval($mijozlar_balans['balans'])-$summa], 'id = '.$mijoz_id);
        echo json_encode(['success' => true, 'message' => '✅ Pul muvaffaqiyatli qayd etildi.']);
    } else {
        echo json_encode(['success' => false, 'message' => '❌ Ma’lumotni qo‘shishda xatolik yuz berdi.']);
    }
?>
