<?php 

header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Max-Age: 86400');  
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");  
include_once('Auth.php');
if($_SERVER['REQUEST_METHOD']==='POST'){
    if(isset($_POST['email'])||isset($_POST['password'])){
        $auth = new Auth;
        $auth->change($_POST);
    }else{
        echo 'kokok';
    }
}
if($_SERVER['REQUEST_METHOD']==='GET'){
    $id = htmlentities($_GET['id']);
    $auth = new Auth;
    $auth->getUSER($id);
}