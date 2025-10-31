<?php
    include_once '../config.php';
    $db = new Database();
    $soyish_id = $_POST['id']; 
    $query = "SELECT gsm.*, m.nomi AS mahsulot_nomi, m.narxi, m.tavsif
            FROM gosht_soyish_mahsulot gsm
            JOIN mahsulotlar m ON gsm.mahsulot_id = m.id
            WHERE gsm.gosht_soyish_id = ".$soyish_id;
    $result = $db->query($query);

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    echo json_encode($data);

?>