<?php
use \Hcode\PageAdmin;
use \Hcode\Model\User;
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