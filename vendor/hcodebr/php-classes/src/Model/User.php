<?php
namespace Hcode\Model;
use Hcode\Model;//namespace
use \Hcode\DB\Sql;

class User extends Model{
    const SESSION = "User";
    //protected $fields = ["iduser", "idperson", "deslogin", "despassword","inadmin", "desemail", "nrphone","dtregister","desperson"];
    public static function login($login,$password){
        $sql = new Sql();
        $results = $sql->select("SELECT * FROM tb_users WHERE deslogin = :LOGIN", array(
            ":LOGIN"=>$login
        ));
        if (count($results) === 0){
            throw new \Exception("usuario inexistente ou senha inválida.",404);
        }
        $data = $results[0];
        if(password_verify($password,$data["despassword"]) === true){
            $user = new User();
            $user->setData($data);
            $_SESSION[User::SESSION] = $user->getValues();
            return $user;
        }
        else{
            throw new \Exception("usuario inexistente ou senha inválida.",404);
        }
    }
    public static function verify_login($inadmin = true){
        if(
            !isset($_SESSION[User::SESSION]) ||
            !$_SESSION[User::SESSION] ||
            !(int)$_SESSION[User::SESSION]["iduser"] > 0 ||
            (bool)$_SESSION[USER::SESSION]["inadmin"] !== $inadmin
        ){
            header("Location: http://localhost/ecommerce/admin/login/");
            exit;
        }
    }
    public static function logout(){
        $_SESSION[User::SESSION] = NULL;
    }
    public static function listAll(){
        $sql = new Sql();
        return $sql->select("Select * from tb_users a INNER JOIN tb_persons b USING(idperson) ORDER BY b.desperson");
    }
    public function save(){
        $sql = new Sql();
       
        $results = $sql->select("CALL sp_users_save(:desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", array(
            ":desperson"=>$this->getdesperson(),
            ":deslogin"=>$this->getdeslogin(),
            ":despassword"=>$this->getdespassword(),
            ":desemail"=>$this->getdesemail(),
            ":nrphone"=>$this->getnrphone(),
            ":inadmin"=>$this->getinadmin()
        ));
        var_dump($results);
        $this->setData($results[0]);
    }
    public function get($iduser){
        $sql = new Sql();
        $results = $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) WHERE a.iduser = iduser", array(
            ":iduser"=>$iduser
        ));
        echo($iduser);
        var_dump($results[0]);
        $this->setData($results[0]);

    }
    public function update(){
        $sql = new Sql();
       
        $results = $sql->select("CALL sp_usersupdate_save(:iduser, :desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", array(
            ":iduser"=>$this->getiduser(),
            ":desperson"=>$this->getdesperson(),
            ":deslogin"=>$this->getdeslogin(),
            ":despassword"=>$this->getdespassword(),
            ":desemail"=>$this->getdesemail(),
            ":nrphone"=>$this->getnrphone(),
            ":inadmin"=>$this->getinadmin()
        ));
        var_dump($results);
        $this->setData($results[0]);
    }
    public function delete(){
        $sql = new Sql();
       
        $sql->query("CALL sp_users_delete(:iduser)",array(
            ":iduser"=>$this->getiduser()
        ));
        }
}
?>


