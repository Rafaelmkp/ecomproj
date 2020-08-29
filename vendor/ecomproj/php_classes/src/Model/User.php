<?php

namespace Ecomproj\Model;

use \Ecomproj\DB\Sql;
use \Ecomproj\Model;
use \Ecomproj\Mailer;

class User extends Model {

    const CIPHER = 'aes-128-cbc';

    const SESSION = "User";
    //student is aware of security issues by publishing SECRET on github
    //used for academic purposes only
    const SECRET  = "t3ngLkihD8gf0uEn";

    const ERROR = 'UserError';

    const ERROR_REGISTER = 'UserErrorRegister';

    public static function getFromSession()
    {
        $user = new User();

        if (isset($_SESSION[User::SESSION]) && (int)$_SESSION[User::SESSION]['iduser'] > 0)
        {
            $user->setData($_SESSION[User::SESSION]);
        }

        return $user;
    }

    public static function checkLogin($inadmin)
    {
        if(
            !isset($_SESSION[User::SESSION])
            ||
            !$_SESSION[User::SESSION]
            ||
            !(int)$_SESSION[User::SESSION]["iduser"] > 0

        ) {
            //User isnt logged
            return false;

        } else {
            
            if ($inadmin === true && (bool)$_SESSION[User::SESSION]['inadmin'] === true) {
                
                return true; 
            
            } else if ($inadmin === false) {

                return true;

            } else {
                
                    return false;
            }
        }
    }

    public static function login($login, $password) 
    {
        $sql = new Sql();

        $results = $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b 
        ON a.idperson = b.idperson
        WHERE a.deslogin = :login", array(
            ":login"=>$login
        ));

        if(count($results) === 0)
        {
            throw new \Exception("Invalid user or incorrect password.");
        }

        $data = $results[0];

        if (password_verify($password, $data["despassword"]) === true)
        {
            $user = new User();
            $user->setData($data);

            $_SESSION[User::SESSION] = $user->getValues();
            return $user;
        } 
        else 
        {
            throw new \Exception("Invalid user or incorrect password");
        }

        
    } 

    public static function verifyLogin($inadmin = true) 
    {
        if (!User::checkLogin($inadmin)) {
            
            if($inadmin) {

                header("Location: /admin/login");
            
            } else {

                header("Location: /login");
            }
            exit;
        }
    }

    public static function logout()
    {
        $_SESSION[User::SESSION] = NULL;
    }

    public static function listAll() 
    {
        $sql = new Sql();

        return $sql->select("SELECT * FROM tb_users u 
            INNER JOIN tb_persons p USING(idperson) ORDER BY p.desperson");
    }

    public function save() 
    {
        $sql = new Sql();
       
        $results = $sql->select("CALL sp_users_save
            (:desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", array(
            ":desperson"=>$this->utf8_decode(getdesperson()),
            ":deslogin"=>$this->getdeslogin(),
            ":despassword"=>User::getPasswordHash($this->getdespassword()),
            ":desemail"=>$this->getdesemail(),
            ":nrphone"=>$this->getnrphone(),
            ":inadmin"=>$this->getinadmin()
        ));

        $this->setData($results[0]);
    }

    public function get($iduser)
    {
        $sql = new Sql();

        $results = $sql->select("SELECT * FROM tb_users u INNER JOIN tb_persons p
            USING(idperson) WHERE u.iduser = :iduser", array(
            ":iduser"=>$iduser
        ));
        
        $this->setData($results[0]);    
    }

    public function update() 
    {

        $sql = new Sql();
        $results = $sql->select("CALL sp_usersupdate_save
            (:iduser, :desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", array(
            ":iduser"=>$this->getiduser(),
            ":desperson"=>utf8_decode($this->getdesperson()),
            ":deslogin"=>$this->getdeslogin(),
            ":despassword"=>User::getPasswordHash($this->getdespassword()),
            ":desemail"=>$this->getdesemail(),
            ":nrphone"=>$this->getnrphone(),
            ":inadmin"=>$this->getinadmin()
        ));    
    }

    public function delete()
    {
        $sql = new Sql();

        $sql->query("CALL sp_users_delete(:iduser)", array(
            ":iduser"=>$this->getiduser()
        ));
    }

    public static function getForgot($email) 
    {
        $sql = new Sql();
        $results = $sql->select("SELECT * FROM tb_persons p INNER JOIN tb_users u USING(idperson)
            WHERE p.desemail = :email", array(
            ":email"=>$email    
        ));

        if(count($results) === 0) 
        {
            throw new \Exception("Não foi possível recuperar a senha.");
        } 
        else 
        {
            $data = $results[0];

            $results2 = $sql->select("CALL sp_userspasswordsrecoveries_create(:iduser, :desip)", array(
                ":iduser"=>$data['iduser'],
                ":desip"=>$_SERVER["REMOTE_ADDR"]
            ));

            if(count($results) === 0)
            {
                throw new \Exception("Não foi possível recuperar a senha.");
            }
            else
            {
                $dataRecovery = $results2[0];

                $code = base64_encode(openssl_encrypt(
                    $dataRecovery["idrecovery"],
                    "aes-128-cbc",
                    User::SECRET,
                    0,
                    User::SECRET
                ));

                $link = "http://hcodecommerce.com.br/admin/forgot/reset?code=$code";

                $mailer = new Mailer($data['desemail'], $data['desperson'],
                    "redefinir senha Ecomproj Store", "forgot", array(
                    "name"=>$data["desperson"],
                    "link"=>$link
                ));

                $mailer->send();

                return $data;
            }
        }
    }

    public static function validForgotDecrypt($code)
    {
        $decode = base64_decode($code);

        $decripted = openssl_decrypt(
            $decode,
            "aes-128-cbc",
            User::SECRET,
            0,
            User::SECRET
        );

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM tb_userspasswordsrecoveries upr
            INNER JOIN tb_users u USING (iduser)
            INNER JOIN tb_persons p USING (idperson)
            WHERE upr.idrecovery = :idrecovery
                AND 
                upr.dtrecovery IS NULL
                AND
                DATE_ADD(upr.dtregister, INTERVAL 1 HOUR) >= NOW();", 
        array(
            ":idrecovery"=>$decripted
        ));

        if(count($results) === 0)
        {
            throw new \Exception("Não foi possível recuperar a senha.");
        } 
        else 
        {
            return $results[0];
        }
    }

    public static function setForgotUsed($idrecovery) 
    {
        $sql = new Sql();

        $sql->query("UPDATE tb_userspasswordsrecovery SET dtrecovery = NOW()
            WHERE idrecovery = :idrecovery", array(
                ":idrecovery"=>$idrecovery
            ));
    }

    public function setPassword($password)
    {
        $sql = new Sql();

        $sql->select("UPDATE tb_users SET despassword = :password WHERE iduser = :iduser", array(
            ":password"=>$password,
            ":iduser"=>$this->getiduser()
        ));
    }

    public static function setError($msg)
    {
        $_SESSION[User::ERROR] = $msg;
    }

    public static function getError()
    {
        $msg = (isset($_SESSION[User::ERROR]) && $_SESSION[User::ERROR]) ? $_SESSION[User::ERROR] : "";

        User::clearError();

        return $msg;
    }

    public static function clearError()
    {
        $_SESSION[User::ERROR] = NULL;
    }

    public function getPasswordHash($password)
    {
        return password_hash($password, PASSWORD_DEFAULT, [
            'cost'=>12
        ]);
    }
}

?>