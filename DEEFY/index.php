<?php
declare(strict_types=1);
require_once("vendor/autoload.php");
session_start();

use deefy\repository\DeefyRepository;
DeefyRepository::setConfig( 'config.ini' );
//$r = DeefyRepository::getInstance();
//$pl = $r->findPlaylistById(1);

if (isset($_SESSION["User"])){
    var_dump($_SESSION["User"]);
}else{
    echo "Pas connecté";
}
$d = new \deefy\dispatch\Dispatcher();
$d->run();

