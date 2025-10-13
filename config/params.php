<?php
return [
    'fromEmail' => 'your@gmail.com', 

    'smtp_prod' => [
        'host'        => 'smtp.gmail.com',
        'port'        => 587,
        'auth'        => true,
        'username'    => 'your@gmail.com',     
        'password'    => null,                 
        'encryption'  => PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS,
        'autotls'     => true,
        'timeout'     => 20,
        'ipv4'        => false,
        'debug'       => true,                
    ],
];
