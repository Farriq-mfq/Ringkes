<?php 
session_start();
include_once('core.php');
header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Max-Age: 86400');  
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
if(session_destroy()){
return response([
    'success'=>true
]);
}else{
    return response([
        'error'=>true
    ]);
}