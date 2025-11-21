<?php

    include_once '../../config.php';
    $db = new Database();
    
    $delete = $db->delete('gosht_soyish');
    if ($delete) {
        $gosht_mahsulot_delete = $db->delete('gosht_soyish_mahsulot');
        echo json_encode(['success' => true, 'message' => 'Barcha go‘sht so‘yish muvaffaqiyatli o‘chirildi!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Barcha go‘sht so‘yishni o‘chirishda xatolik yuz berdi.']);
    }
?>