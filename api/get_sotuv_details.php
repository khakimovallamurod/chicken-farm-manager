<?php
    include_once '../config.php';
    $db = new Database();
    $sotuv_id = $_POST['id']; 
    $query = "SELECT 
        sm.narxi,
        sm.soni,
        sm.summa,
        m.nomi
    FROM sotuv_mahsulotlar sm
    LEFT JOIN mahsulotlar m ON sm.mahsulot_id = m.id
    WHERE sm.sotuv_id = $sotuv_id
    ORDER BY sm.summa DESC;";
    $result = $db->query($query);

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode($data);

?>