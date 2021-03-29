<?php 
header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Max-Age: 86400');  
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    require_once('Auth.php');
    $auth = new Auth;

    if($_SERVER['REQUEST_METHOD']=='POST'){
        $auth->login($_POST);
    }