<?php

use App\Autoloader;
use App\Database;
use HTML\Content;

require '../app/Autoloader.php';
Autoloader::register();

$pdo = new Database();

ob_start();

if (isset($_GET)) {
	$page = key($_GET);
	$page = htmlspecialchars($page);

	if(!@include('../pages/'.$page.'.php')){
		header("Location: index.php?accueil");
	}

}

$content = ob_get_clean();

require '../pages/template/default.php';