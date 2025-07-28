<?php
    include_once '../config.php';

    $db = new Database();
    $id = $_POST['id'] ?? 0;

    $result = $db->delete('kataklar', "id = '$id'");
    
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Katak muvaffaqiyatli o\'chirildi']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Katakni o\'chirishda xatolik']);
    }
?>