<?php
    include_once '../config.php';
    $db = new Database();

    $mijoz_id = $_POST['mijoz_id']; 
    $query = "SELECT po.summa, po.sana, po.izoh, tb.nomi as tolov_turi
    FROM pul_olish po
    JOIN tolov_birligi tb ON po.tolov_birlik_id = tb.id
    WHERE po.mijoz_id = $mijoz_id
    ORDER BY po.sana DESC;";
    
    $result = $db->query($query);

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode($data);

?>