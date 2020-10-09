<?php

// permission for write an exp default controller, config, view etc for the first time
define('PERMISSION', 0755);

// one folder for one controller
define('OFFOC', true);
// if value is true, each controllers must be placed in one folder
// true exp: /App/home/HomeController.php, /App/Profil/ProfilController.php, etc
// false exp: /App/HomeController.php, /App/ProfilController.php, etc


// load all helpers
require 'Foundation/Registry.php';
require 'Foundation/Application.php';

$kernel = new Gi\Foundation\Application();
$kernel->run();
