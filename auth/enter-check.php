<?php 
    session_start();
    include_once '../config.php';
    $login = $_POST['login'];
    $parol = md5(string: $_POST['parol']);
    $db = new Database();
    $fetch = $db->get_data_by_table(table: 'users', arr: ['username'=>$login, 'password'=>$parol]);
    $ret = [];
    if($fetch){
        $_SESSION['login'] = $fetch['username'];
        $_SESSION['id'] = $fetch['id'];
        $_SESSION['fullname'] = $fetch['fullname'];
        $_SESSION['rol'] = $fetch['rol'];
        $rol = $fetch['rol'];
        $ret += ['xatolik'=>0, 'xabar'=>"Muvaffaqiyatli kirdingiz!", 'manzil'=>$rol];
    }else{
        $ret += ['xatolik'=>1, 'xabar'=>"Xatolik! Login yoki parol xato"];

    }
    echo json_encode(value: $ret);
?>