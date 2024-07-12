<?php



// Podesavanja za bazu
// define("SERVER", "localhost");
// define("DATABASE", "sneakersshop");
// define("USERNAME", "root");
// define("PASSWORD", "");


define("envPath",__DIR__."/.env");

define("SERVER", env("SERVER"));

define("DATABASE", env("DBNAME"));
define("USERNAME", env("USERNAME"));
define("PASSWORD", env("PASSWORD"));
function env($marker){
$niz = file(envPath);
$dbParametar = "";
foreach($niz as $red){
$red = trim($red);
list($identifikator, $vrednost) = explode("=", $red);
if($identifikator == $marker){
$dbParametar = $vrednost;
break;
}
}
return $dbParametar;
}
