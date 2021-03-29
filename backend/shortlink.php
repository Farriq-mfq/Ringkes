<?php 
header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Max-Age: 86400');  
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE");  
require_once('shortnerClass.php');

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    // create
    if($_POST['uid']){
        $uid = htmlentities($_POST['uid']);
    }
    $url = htmlentities($_POST['url']);
    $shortlink = new shortner;
    $shortlink->urlShortcode($url,$uid);
}
if($_SERVER['REQUEST_METHOD'] == 'GET'){
    // get
    $id = htmlentities($_GET['id']);
    $shortlink = new shortner;
    $shortlink->getallShortner($id);
}
if($_SERVER['REQUEST_METHOD'] == 'DELETE'){
    $id = htmlentities($_GET['id']);
    $shortlink = new shortner;
    $shortlink->delete($id);
}