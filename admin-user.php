<?php
use \Hcode\PageAdmin;
use \Hcode\Model\User;

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
$app->get("/admin/forgot",function(){
	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);
	$page->setTpl("forgot");
});
$app->post("/admin/forgot",function(){
	$user = User::getForgot($_POST["email"]);
	header("Location: http://localhost/ecommerce/admin/forgot/sent");
	exit;
});
$app->get("/admin/forgot/sent", function(){
	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);
	$page->setTpl("forgot-sent");
});
$app->get("/admin/forgot/reset",function(){
	$user= User::validForgotDecrypt($_GET["code"]);
	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);
	$page->setTpl("forgot-reset", array(
		"name"=>$user["desperson"],
		"code"=>$_GET["code"]
	));
});
$app->post("/admin/forgot/reset",function(){
	$forgot= User::validForgotDecrypt($_POST["code"]);
	User::setForgotUsed($forgot["idrecovery"]);
	$user = new User();
	$user->get((int)$forgot["iduser"]);
	$password = password_hash($_POST["password"], PASSWORD_DEFAULT,[
		"cost"=>12
	]);
	$user->setPassword($password);
	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);
	$page->setTpl("forgot-reset-success");
});