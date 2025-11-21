<?php
    include_once '../../config.php';
    $db = new Database();
    
    $id = $_POST['id'] ?? 0;
    $delete = $db->delete('gosht_soyish', 'id='. $id);
    if ($delete) {
        $gosht_mahsulot_delete = $db->delete('gosht_soyish_mahsulot', 'gosht_soyish_id='. $id);
        echo json_encode(['success' => true, 'message' => 'Go‘sht so‘yish muvaffaqiyatli o‘chirildi!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Go‘sht so‘yishni o‘chirishda xatolik yuz berdi.']);
    }
        
?>