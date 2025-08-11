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
            oj.sana,
            oj.soni,
            (
                SELECT j.narxi 
                FROM joja j 
                WHERE j.katak_id = oj.katak_id 
                ORDER BY j.sana DESC 
                LIMIT 1
            ) AS narxi,
            (oj.soni * (
                SELECT j.narxi 
                FROM joja j 
                WHERE j.katak_id = oj.katak_id 
                ORDER BY j.sana DESC 
                LIMIT 1
            )) AS summa,
            oj.izoh
        FROM 
            olgan_jojalar oj
        WHERE 
            oj.katak_id = $katakId
        ORDER BY 
            oj.sana DESC;
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