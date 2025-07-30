<?php
    include_once '../config.php';
    header('Content-Type: application/json');

    $input = json_decode(file_get_contents("php://input"), true);
    $db = new Database();

    $mijoz_id = $input['mijoz_id'];
    $sana = $input['sana'];
    $izoh = $input['izoh'] ?? '';
    $umumiy_summa = $input['umumiy_summa'];
    $mahsulotlar = $input['mahsulotlar'];

    $sotuv_data = [
        'mijoz_id' => $mijoz_id,
        'sana' => $sana,
        'izoh' => $izoh,
        'summa' => $umumiy_summa
    ];

    $insert_query = $db->insert('sotuvlar', $sotuv_data);
    if ($insert_query) {
        $sotuv_id = $db->get_data_by_table_all('sotuvlar', 'ORDER BY id DESC')[0]['id'];
        
        foreach ($mahsulotlar as $m) {
            $soni = floatval($m['miqdor']);
            $narxi = floatval($m['narx']);
            $summa = floatval($m['summa']);
            $product_data = [
                'sotuv_id' => $sotuv_id,
                'mahsulot_id' => $m['mahsulot_id'],
                'soni' => $soni,
                'narxi' => $narxi,
                'summa' => $summa
            ];
            $db->insert('sotuv_mahsulotlar', $product_data);
            $mahsulot_by_id = $db->get_data_by_table('mahsulot_zahirasi', ['mahsulot_id'=>$m['mahsulot_id']]);
            $db->update('mahsulot_zahirasi', ['soni'=>$mahsulot_by_id['soni'] - $soni], ' mahsulot_id = '.$m['mahsulot_id']);
        }
        $mijoz_by_id = $db->get_data_by_table('mijozlar', ['id'=>$mijoz_id]);
        $db->update('mijozlar', ['balans'=>$umumiy_summa + $mijoz_by_id['balans']], ' id='.$mijoz_id);
        echo json_encode(['success' => true, 'message' => 'Sotuv muvaffaqiyatli qo‘shildi!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Sotuvni qo‘shishda xatolik yuz berdi.']);
    }
?>
