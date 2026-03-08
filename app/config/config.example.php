<?php
//Примера, как должен выглядеть файл config.php, файл config.php необходимо разместить в папке app\config
return [
    'recaptcha' => [
        'site_key' => 'your_site_key_here',
        'secret_key' => 'your_secret_key_here'
    ], 
    'database' => [
        'host' => 'localhost',
        'dbname' => 'your_name_database',
        'username' => 'your_name',
        'password' => 'your_password',
        'charset' => 'utf8mb4'
    ],
];