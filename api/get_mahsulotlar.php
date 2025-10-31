<?php

    include_once '../config.php'; 
    $db = new Database();
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = $_POST['id'] ?? null;
        $mahsulot_data = $db->get_data_by_table('mahsulotlar', ['id'=>$id]);
        echo json_encode(['status'=> true, 'mahsulot'=> $mahsulot_data]);        
    }else{
        echo json_encode(['status'=> false, 'message'=> "Method not found"]);
    }
    
?>