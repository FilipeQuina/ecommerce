<?php 
session_start();
require_once("vendor/autoload.php");
use \Slim\Slim;
use \Hcode\Page;
use \Hcode\PageAdmin;
use \Hcode\Model\User;

$app = new Slim();

$app->config('debug', true);

$app->get('/', function() {
	$page = new Page();
	$page->setTpl("index");
});
$app->get('/admin/', function() {
	User::verify_login();
    
	$page = new PageAdmin();
	$page->setTpl("index");
});
$app->get('/admin/login/', function() {
	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);
	$page->setTpl("login");

});
$app->post('/admin/login/', function() {
    
	User::login($_POST["login"], $_POST["password"]);
	header("Location: http://localhost/ecommerce/admin/");
	exit;
});
$app->get('/admin/logout/', function() {
	User::logout();
	header("Location: http://localhost/ecommerce/admin/login/");
	exit;
});
$app->get("/admin/users", function(){
	User::verify_login();
	$users = User::listAll();
	$page = new PageAdmin();
	$page->setTpl("users", array(
		"users"=>$users
	));

});
$app->post("/admin/users/create", function(){
	User::verify_login();
	//$page = new PageAdmin();
	$user = new User();
	$_POST["inadmin"] = (isset($_POST["inadmin"]))? 1:0;
	$user->setData($_POST);
	$user->save();
	header("Location: http://localhost/ecommerce/admin/users");
	exit;

});
$app->get("/admin/users/:iduser/delete", function($iduser){
	User::verify_login();
	$user = new User();
	$user->get((int)$iduser);
	echo($iduser);
	var_dump($user);
	exit;
	$user->delete();
	header("Location: http://localhost/ecommerce/admin/users");
	exit;
});
$app->get("/admin/users/create", function(){
	User::verify_login();
	$page = new PageAdmin();
	$page->setTpl("users-create");
});
$app->get("/admin/users/:iduser", function($iduser){
	User::verify_login();
	$user = new User();
	$user->get((int)$iduser);
	$page = new PageAdmin();
	$page->setTpl("users-update", array(
		"user"=>$user->getValues()
	));
});
$app->post("/admin/users/:iduser", function($iduser){
	User::verify_login();
	$user = new User();
	$user->get((int)$iduser);
	$user->setData($_POST);
	$user->update();
	header("Location: http://localhost/ecommerce/admin/users");
	exit;
});


$app->run();

?>