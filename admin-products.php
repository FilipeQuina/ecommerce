<?php
use \Hcode\PageAdmin;

use \Hcode\Model\Product;
use \Hcode\Model\User;

$app->get("/admin/products",function(){
    User::verify_login();
    $page = new PageAdmin();
    $products =  Product::listAll();
    $page->setTpl("products",[
        "products"=>$products

    ]);
});

$app->get("/admin/products/create",function(){
    User::verify_login();
    $page = new PageAdmin();
    $page->setTpl("products-create");
});
$app->post("/admin/products/create",function(){
    User::verify_login();
    $product = new Product();
    $product->setData($_POST);
    $product->save();
    header("Location: http://localhost/ecommerce/admin/products");
    exit;

});
$app->get("/admin/products/:idproduct",function($idproduct){
    User::verify_login();
    $product = new Product();
    $product->get((int)$idproduct);
   
    $page = new PageAdmin();
    
    $page->setTpl("products-update",[
        "product"=>$product->getValues()
    ]);
});
$app->post("/admin/products/:idproduct",function($idproduct){
    User::verify_login();
    $product = new Product();
    $product->get((int)$idproduct);
    $product->setData($_POST);
    $product->save();
    $product->setphoto($_FILES["name"]);
    header("Location: http://localhost/ecommerce/admin/products");
    exit;
});
