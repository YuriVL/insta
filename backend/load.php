<?php
// Composer
require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../classes/autoload.php');

use classes\FileAction;

header('Content-Type: application/json');
echo (new FileAction())->upload();
