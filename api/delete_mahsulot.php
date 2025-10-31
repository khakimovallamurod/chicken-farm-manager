<?php
    include_once '../config.php';

    $db = new Database();
    $id = $_POST['id'] ?? 0;

    $result = $db->delete('mahsulotlar', "id = '$id'");
    
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Mahsulot muvaffaqiyatli o\'chirildi']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Mahsulot o\'chirishda xatolik']);
    }
?>