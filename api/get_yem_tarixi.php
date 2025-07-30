<?php
    header('Content-Type: application/json');
    include_once '../config.php';
    $db = new Database();

    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['katak_id'])) {
        echo json_encode(['success' => false, 'message' => 'katak_id yuborilmadi']);
        exit;
    }
    $katakId = intval($data['katak_id']); 
    $sql = "
        SELECT 
            y.sana,
            m.nomi AS mahsulot_nomi,
            y.miqdori,
            m.narxi,
            ROUND(y.miqdori * m.narxi, 2) AS umumiy_summa,
            y.izoh
        FROM 
            yem_berish y
        JOIN 
            mahsulotlar m ON y.mahsulot_id = m.id
        WHERE 
            y.katak_id = $katakId
        ORDER BY 
            y.sana DESC;
    ";

    $result = $db->query($sql);

    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    echo json_encode([
        'success' => true,
        'data' => $rows
    ]);
?>