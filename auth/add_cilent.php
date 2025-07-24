<?php 
    session_start();
    include_once '../config.php';
    $ful_name = $_POST['full'];
    $login = $_POST['login'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $parol = md5($_POST['parol']);
    $parol_encoded = md5('parol123');
    
    $db = new Database();
    if ($parol == $parol_encoded && $login == 'admin') {
        $sql = $db->insert('users', ['fullname'=>$ful_name,  'username'=>$login, 'email'=>$email,'phone'=>$phone, 'password'=>$parol, 'rol'=>'admin']);
    } else {
        $sql = $db->insert(table: 'users', arr: ['fullname'=>$ful_name,  'username'=>$login, 'email'=>$email,'phone'=>$phone, 'password'=>$parol]);
    }
    $ret = [];
    if($sql){
        $_SESSION['login']=$login;
        $user = $db->get_data_by_table('users', ['username'=>$login]);
        $_SESSION['id'] = $user['id'];
        $ret += ['xatolik'=>0, 'xabar'=>"Muvaffaqiyatli qo'shildi!"];
    }else{
        $ret += ['xatolik'=>1, 'xabar'=>'Xatolik! Ma\'lumotingizni qaytadan kiritib ko\'ring'];
    }
    echo json_encode($ret);

?>