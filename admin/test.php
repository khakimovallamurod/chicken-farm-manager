<?php
    include_once '../config.php';
    $db = new Database();
    $harajat_turlari = $db->get_data_by_table_all('harajat_turlari');
    print_r($harajat_turlari);
    foreach ($harajat_turlari as $harajat){
                    $harajat_nomi = $db->get_data_by_table('harajat_turlari', ['id'=>$harajat['harajat_turi_id']])['nomi'];
                    $tolov_nomi = $db->get_data_by_table('tolov_birligi', ['id'=>$harajat['tolov_birlik_id']])['nomi'];
    }
?>