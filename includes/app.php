<?php

require 'funciones.php';
require 'config/databases.php';
require __DIR__ . '/../vendor/autoload.php';

// Conectarnos a la BD
$db = conectarDB();

use Model\ActiveRecord;

ActiveRecord::setDB($db);