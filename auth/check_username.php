<?php
    include_once '../config.php';
    $login = $_POST['login'];
    $arr = ['username'=>$login];
    $db = new Database();
    $sql = $db->get_data_by_table('users', $arr);
    $ret = [];
    if($sql['user_id']){
        $ret += ['xatolik'=>0, 'xabar'=>'Ushbu login band'];
    }else{
        $ret += ['xatolik'=>1, 'xabar'=>'Muvaffaqiyatli'];
    }
    echo json_encode($ret);

?>