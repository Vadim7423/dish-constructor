<?php
//declare(strict_types=1);

require 'app.php';
$config = require 'config.php';

App::Init($config)->Print();
