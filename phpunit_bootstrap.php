<?php

date_default_timezone_set('America/Sao_Paulo');

if (file_exists($file = __DIR__.'/bootstrap.php')) {
    require_once $file;
}