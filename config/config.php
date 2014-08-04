<?php
return array(
	'swiftmailer.options' => array(
	    'host' => 'smtp.gmail.com',
	    'port' => '465',
	    'username' => 'contato@coderockr.com',
	    'password' => 'H&m6&mUE',
	    'encryption' => 'ssl',
	    'auth_mode' => 'login'
	),
	'db.options' => array(
        'driver' => 'pdo_mysql',
        'host' => 'localhost',
        'port' => '3306',
        'user' => 'orcamentos',
        'password' => 'orcamentos',
        'dbname' => 'orcamentos'
    ),
    'bitly' => array(
    	'url' => 'http://orcamentos.coderockr.com/share/',
    	'token' => 'ed0e929d7ff5b92c480f34e4851a96945dd4702b'
    )
);
