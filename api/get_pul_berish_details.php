<?php
    include_once '../config.php';
    $db = new Database();

    $taminotchi_id = $_POST['id']; 
    $query = "SELECT pb.summa, pb.sana, pb.izoh, tb.nomi as tolov_turi
    FROM pul_berish pb
    JOIN tolov_birligi tb ON pb.tolov_birlik_id = tb.id
    WHERE pb.taminotchi_id = $taminotchi_id
    ORDER BY pb.sana DESC;";
    
    $result = $db->query($query);

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode($data);

?>