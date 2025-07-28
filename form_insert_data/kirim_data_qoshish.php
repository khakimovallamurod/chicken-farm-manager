<?php
    include_once '../config.php';
    header('Content-Type: application/json');
    $input = json_decode(file_get_contents("php://input"), true);
    $db = new Database();
    $taminotchi_id = $input['taminotchi_id'];
    $sana = $input['sana'];
    $izoh = $input['izoh'] ?? '';
    $umumiy_summa = $input['umumiy_summa'];
    $mahsulotlar = $input['mahsulotlar'];

    $kirim_data = [
        'taminotchi_id' => $taminotchi_id,
        'sana' => $sana,
        'summa' => $umumiy_summa,
        'izoh' => $izoh,
    ];

    $insert_query = $db->insert('kirimlar', $kirim_data);

    if (!$insert_query) {
        echo json_encode(['success' => false, 'message' => 'Kirim yozishda xatolik']);
        exit;
    }
    $kirim_id = $db->get_data_by_table_all('kirimlar', 'ORDER BY id DESC')[0]['id'];
    foreach ($mahsulotlar as $item) {
        $mahsulot_id = intval($item['mahsulot_nomi']); // bu id bo'lsa
        $miqdor = floatval($item['miqdor']);
        $narx = floatval($item['narx']);
        $summa = floatval($item['summa']);

        $item_data = [
            'kirim_id' => $kirim_id,
            'mahsulot_id' => $mahsulot_id,
            'narxi' => $narx,
            'soni' => $miqdor,
            'summa' => $summa
        ];
        
        $db->insert('kirim_mahsulotlar', $item_data);
        $mahsulot_zahira = [
            'mahsulot_id' => $mahsulot_id,
            'soni' => $miqdor
        ];
        $m_zahira_get = $db->get_data_by_table('mahsulot_zahirasi', ['mahsulot_id' => $mahsulot_id]);
        if ($m_zahira_get) {
            $soni = (int)$m_zahira_get['soni'] + $mahsulot_zahira['soni'];
            $db->update('mahsulot_zahirasi', ['soni' => $soni], 'mahsulot_id = ' . $mahsulot_id);;
        } else {
            $db->insert('mahsulot_zahirasi', $mahsulot_zahira);
        }
    }
   
    $taminotchi_user = $db->get_data_by_table_all('taminotchilar', ' WHERE id = '.$taminotchi_id);
    $taminotchi_balans = floatval($taminotchi_user[0]['balans']) + floatval($umumiy_summa);
    $db->update('taminotchilar', ['balans'=>$taminotchi_balans], 'id = '.$taminotchi_id);
    
    echo json_encode(['success' => true, 'message' => 'Kirim muvaffaqiyatli qo‘shildi']);
?>
