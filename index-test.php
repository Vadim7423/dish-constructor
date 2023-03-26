<?php
//declare(strict_types=1);

const KEY = 0;
const VALUE = 1;

require 'app.php';
$config = require 'config.php';

$url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$params =  explode("=", parse_url($url, PHP_URL_QUERY));

App::Init($config)->FillIngredientData($params[VALUE])->Print();
