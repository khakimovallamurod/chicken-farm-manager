<?php
    include_once '../config.php';
    $db = new Database();

    $kirim_id = $_POST['id']; 
    $query = "SELECT km.id, km.narxi, km.soni, km.summa
    FROM kirim_mahsulotlar km
    JOIN kirimlar k ON km.kirim_id = k.id
    WHERE k.id = $kirim_id;";
    
    $result = $db->query($query);

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode($data);

?>