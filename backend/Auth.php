<?php 
    require_once('core.php');
    class Auth {
        protected $host = 'localhost';
        protected $username = 'root';
        protected $password = '';
        protected $DB = 'shortner';
        protected $connect;
        public function __construct()
        {
            $this->connect = new mysqli($this->host,$this->username,$this->password,$this->DB);
        }
        public function login($data)
        {   
            $email = htmlentities($data['email']);
            $password = htmlentities( $data['password']);
            if(empty($email)){
                return response([
                    'error_email'=>'Email di perlukan'
                ]);
            }elseif(empty($password)){
                return response([
                    'error_password'=>'Password Di perlukan'
                ]);
            }else{
                if(filter_var($email,FILTER_VALIDATE_EMAIL)){
                    $query = $this->connect->query("SELECT * FROM user WHERE email= '$email'");
                    $fetch = $query->fetch_assoc();
                    $passwordFromdatabse = $fetch['password'];
                    if($query->num_rows === 1){
                        if(password_verify($password,$passwordFromdatabse)){
                            $_SESSION['login'] = true;
                            $_SESSION['user'] = $fetch;
                            return response([
                                'success'=>true,
                                'user'=>$fetch
                            ]);
                        }else{
                            return response([
                            'error'=>'Login Gagal'
                            ]);
                        }
                    }else{
                        return response([
                            'error'=>'Email Belum Register'
                        ]);
                    }
                }else{
                    return response([
                        'error'=>'ini bukan email'
                    ]);
                }
            }
        }
        public function register($data)
        {
            $nama = htmlentities($data['nama']);
            $email = htmlentities($data['email']);
            $password = htmlentities($data['password']);
            if(empty($nama)){
                return response([
                    'error'=>'Nama Harus id isi'  
                    ]);
                }elseif(empty($email)){
                    
                    return response([
                    'error'=>'Email Harus id isi'  
                    
                    ]);
                }elseif(empty($password)){
                    return response([
                    'error'=>'Password Harus id isi'  
                
                ]);
                
            }else{
                if(filter_var($email,FILTER_VALIDATE_EMAIL)){
                    if(strlen($password) <= 5){
                        return response([
                            'error'=>'Password Minimal 5 Karakter'
                        ]);
                    }else{
                        $hash = password_hash($password,PASSWORD_DEFAULT);
                        $query = $this->connect->query("INSERT INTO `user`(`email`, `nama`, `password`) VALUES ('$email','$nama','$hash')");
                        if($query){
                            return response([
                                'success'=>'Register Sukses '
                            ]);
                        }

                    }
                }else{
                    return response([
                        'error'=>'Email Tidak valid'
                    ]);
                }
            }
        }
        public function change($data)
        {   
            $nama = htmlentities($data['nama']);
            $email = htmlentities($data['email']);
            if(filter_var($email,FILTER_VALIDATE_EMAIL)){
                $id = htmlentities($data['id']);
                $query= $this->connect->query("UPDATE `user` SET `email`='$email',`nama`='$nama' WHERE id = '$id' ");
                if($query){
                    return response([
                        'success'=>"sukses ubah data"
                    ]);
                }else{
                    return response([
                        'error'=>"error ubah data"
                    ]);
    
                }
                
            }else{
                return response([
                    'error'=>"Bukan email"
                ]);

            }
        }
        public function getUSER($id)
        {
            $query  = $this->connect->query("SELECT * FROM user WHERE id='$id' LIMIT 1");
            return response([
                'data'=>$query->fetch_assoc()
            ]);
        }
    }
    