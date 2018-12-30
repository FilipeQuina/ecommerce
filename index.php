<?php 

require_once("vendor/autoload.php");
use \Slim\Slim;
use \Hcode\Page;
use \Hcode\PageAdmin;
$app = new Slim();

$app->config('debug', true);


$app->get('/restrito', function() {
    
	$page = new PageAdmin();
	echo("as");
	$page->setTpl("index");

});
$app->get('/', function() {
    
	$page = new Page();
	$page->setTpl("index");

});

$app->run();

?>