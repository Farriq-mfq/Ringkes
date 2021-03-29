<?php 
    
header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Max-Age: 86400');  
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); 
include_once('Auth.php');
class reset{
    protected $host = 'localhost';
    protected $username = 'root';
    protected $password = '';
    protected $DB = 'shortner';
    protected $connect;
   public function __construct()
   {
    $this->connect = new mysqli($this->host,$this->username,$this->password,$this->DB);
   }
   public function reset($id,$pass_lama,$pass_baru)
   {
      if(empty($pass_lama)){
          return response([
            'error'=>'Password Lama tidak boleh kosong'
          ]);
        }elseif(empty($pass_baru)){
          return response([
            'error'=>'Password Baru tidak boleh kosong'
          ]);
          
        }else{
            if(strlen($pass_baru) <= 5){
            return response([
              'error'=>'Password Baru Harus 5 Karakter'
            ]);
        }else{
            if($this->chekcpass($id,$pass_lama)){
                $passworBaru = password_hash(trim($pass_baru),PASSWORD_DEFAULT);
                $query = $this->connect->query("UPDATE `user` SET `password`='$passworBaru' WHERE id='$id' ");
                if($query){
                    return response([
                        'success'=>'Sukses Reset Password'
                    ]);
                }
            }
        }
      }
   }
   protected function chekcpass($id,$pass_lama)
   {
       $query = $this->connect->query("SELECT * FROM user WHERE id='$id'");
       $fetch = $query->fetch_assoc();
       $hash = $fetch['password'];
       if(password_verify($pass_lama,$hash)){
            return true;
       }else{
           return response([
            'error'=>'Password Lama salah'
           ]);
       }
   }
}
if($_SERVER['REQUEST_METHOD']=='POST'){
    $id = htmlentities($_POST['id']);
    $pass_lama = htmlentities($_POST['pass_lama']);
    $pass_baru = htmlentities($_POST['pass_baru']);
    $reset = new reset;
    $reset->reset($id,$pass_lama,$pass_baru);
    
}