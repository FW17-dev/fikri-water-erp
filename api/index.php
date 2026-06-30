<?php

declare(strict_types=1);

$_SERVER['SCRIPT_FILENAME'] = realpath(__DIR__ . '/../public/index.php');
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['PHP_SELF'] = '/index.php';
$_SERVER['DOCUMENT_ROOT'] = realpath(__DIR__ . '/../public');

require $_SERVER['SCRIPT_FILENAME'];
