<?php 
function response($data, $status_code = 200)
{
    // List kode status HTTP yang sering dipakai
    $statuses = [
        200 => 'OK',
        201 => 'Created',
        204 => 'No Content',
        206 => 'Partial Content',

        301 => 'Moved Permanently',
        302 => 'Found',

        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        409 => 'Conflict',
        413 => 'Payload Too Large',
        415 => 'Unsupported Media Type',
        422 => 'Unprocessable Entity',
        429 => 'Too Many Requests',
    ];

    // RESTful: selalu berikan kode status yang tepat!
    header("{$_SERVER['SERVER_PROTOCOL']} {$status_code} {$statuses[$status_code]}");
    // RESTful: selalu berikan tipe konten!
    header('Content-Type: application/json');

    // Jika datanya null, jangan tampilkan apapun
    echo $data === null ? null : json_encode($data);
}
function getUserIP()
{
    // Get real visitor IP behind CloudFlare network
    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
              $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
              $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
    }
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    return $ip;
}