<?php
use \Hcode\PageAdmin;
use \Hcode\Model\User;
use \Hcode\Model\Category;
$app->get("/admin/categories", function(){
	User::verify_login();
	$categories = Category::listAll();
	$page = new PageAdmin();
	$page->setTpl("categories",[
		'categories'=>$categories
	]);
});
$app->get("/admin/categories/create", function(){
	User::verify_login();
	$page = new PageAdmin();
	$page->setTpl("categories-create");
});
$app->post("/admin/categories/create", function(){
	User::verify_login();
	$category = new Category();
	$category->setData($_POST);
	$category->save();
	header("Location: http://localhost/ecommerce/admin/categories");
	exit;
});
$app->get("/admin/categories/:idcategory/delete", function($idcategory){
	User::verify_login();
	$category = new Category();
	$category->get((int)$idcategory);
	$category->delete();
	header("Location: http://localhost/ecommerce/admin/categories");
	exit;
});
$app->get("/admin/categories/:idcategory", function($idcategory){
	User::verify_login();
	$category = new Category();
	$category->get((int)$idcategory);
	$page = new PageAdmin();
	$page->setTpl("categories-update",[
		"category"=>$category->getvalues()
	]);
});
$app->post("/admin/categories/:idcategory", function($idcategory){
	User::verify_login();
	$category = new Category();
	$category->get((int)$idcategory);
	$category->setData($_POST);
	$category->save();
	header("Location: http://localhost/ecommerce/admin/categories");
	exit;
});
$app->get("/categories/:idcategory", function($idcategory){
	$category = new Category();
	$category->get((int)$idcategory);
	$page = new PageAdmin();
	$page->setTpl("category", [
		'category'=>$category->getValues(),
		'products'=>[]
	]);
});