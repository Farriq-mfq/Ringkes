<?php 
require_once('core.php');
class shortner{
    protected $randomKarakter = 'abcdefghijklmnopqrstuvwxyz|ABCDEFGHIJKLMNOPQRSTUVWXYZ|0123456789';
    protected $host = 'localhost';
    protected $username = 'root';
    protected $password = '';
    protected $DB = 'shortner';
    protected $date;
    protected $connect;

    public function __construct()
    {
        $this->connect = new mysqli($this->host,$this->username,$this->password,$this->DB);
        $this->date = date('Y-m-d H:i:s');
    }
    public function urlShortcode($url,$uid = NULL)
    {
        if(empty($url)){
            return response([
                'error'=>'Mohon isi url'
            ]);
        }else{
            if(filter_var($url,FILTER_SANITIZE_URL)){
                if(filter_var($url,FILTER_VALIDATE_URL,FILTER_FLAG_PATH_REQUIRED)){
                    if($this->urlTRue($url)){
                        if($this->urlExist($url)){
                            $short_code = $this->created_code();
                            if($short_code){
                            $this->Insert($short_code,$url,$uid);
                        }else{
                            return response([
                                'error'=>'somthing went wrong'
                            ]);
                            
                            }
                        }else{
                            return response([
                                'error'=>'Url sudah ada'
                            ]);
                            
                        }
                    }else{
                        return response([
                            'error'=>'Url tidak aktif'
                        ]);
                    }
                }else{
                    return response([
                        'error'=>'Ini bukan Url'
                    ]);
                }
            };
        }
    }
    protected function urlTRue($url)
    {
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_NOBODY,true);
        curl_exec($ch);
        $response = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        if(!empty($response) && $response != 404){
            return true;
        }else{
            return false;
        }
    }
    protected function urlExist($url)
    {
        $sQL = $this->connect->query("SELECT * FROM shortner_tbl WHERE long_url = '$url' LIMIT 1");
        $rows = $sQL->num_rows;
        if($rows == 0){
            return true;
        }else{
            return false;
        }   
    }
    protected function created_code($length = 6)
    {
        $explode = explode('|',$this->randomKarakter);
        $get = '';
        $string = '';
        foreach ($explode as $str) {
            $random = $str[array_rand(str_split($str))];
            $string .= $random;
            $get .= $str;
        }
        $get = str_split($get);
        for ($i=0; $i < $length; $i++) { 
            $random .= $get[array_rand($get)];
        }
        $random = str_shuffle($random);
        return $random;
    }
    protected function Insert($short_url,$long_url,$uid=NULL)
    {
        if(!empty($short_url)&&!empty($long_url)){
            $query = $this->connect->query("INSERT INTO shortner_tbl (`short_url`,`long_url`,`uid`,`created_at`) VALUES ('$short_url','$long_url','$uid','$this->date')");
            if($query){
                return response([
                    'success'=>'sukses ',
                    'insert_id'=>$this->connect->insert_id
                ]);
            }else{
                return response([
                    'error'=>'somthing went wrong'
                ]);
            }
        }
    }
    public function redirectshortner($code)
    {
        if($code){
            $query = $this->connect->query("SELECT * FROM shortner_tbl WHERE short_url = '$code' LIMIT 1");
            if($query->num_rows === 1){
                $fetch = $query->fetch_assoc();
                $id = $fetch['id'];
                $ip = getUserIP();
                $stmtView = $this->connect->query("SELECT * FROM view WHERE id_s = '$id' AND ip = '$ip'  LIMIT 1");
                $count =  $stmtView->num_rows;
                if($count === 0){
                    // insert
                    $this->connect->query("INSERT INTO `view`(`id_s`, `ip`) VALUES ('$id','$ip')");
                }
                $handelView = $this->connect->query("SELECT * FROM view WHERE id_s = '$id'");
                $countis = $handelView->num_rows;
                $this->handleView($id,$countis);
                return response([
                    'data'=>$fetch,
                ]);
            }
        }else{
            return false;
        }
    }
    protected function handleView($id,$count = 1)
    {
        $query = "UPDATE `shortner_tbl` SET `view`='$count' WHERE id = '$id'";
        $this->connect->query($query);
    }
    public function getallShortner($last_id)
    {
        if(!$_SERVER['REQUEST_METHOD']==='get'){
            exit;
        }else{
            $query = $this->connect->query("SELECT * FROM shortner_tbl WHERE id = '$last_id'");
                $fetch = $query->fetch_assoc();
                return response([
                    'data'=> $fetch,
                    'status'=>http_response_code(),
                ]);
        }
    }
    public function getall($id)
    {
        $query = $this->connect->query("SELECT * FROM shortner_tbl WHERE uid = '$id'");
        $fetch = array();
        foreach ($query as $key => $value) {
            $fetch[] = $value;
        }
        return response([
            'data'=>$fetch
        ]);
    }
    public function delete($id)
    {
        if(isset($id)){
            $query = $this->connect->query("DELETE FROM shortner_tbl WHERE id='$id'");
            if($query){
                $this->connect->query("DELETE FROM view WHERE id_s='$id'");
                return response([
                    'success'=>'sukses hapus'
                ]);
            }else{
                return response([
                    'error'=>'Gagal Hapus'
                ]);
            }
        }
    }
}