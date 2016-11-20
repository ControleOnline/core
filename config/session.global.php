<?php
return array(
    'session' => array(
        'sessionConfig' => array(
            'cache_expire' => 86400,
            //'cookie_domain' => 'localhost',
            //'name' => 'localhost',
            'cookie_lifetime' => 1800,
            'gc_maxlifetime' => 1800,
            'cookie_path' => '/',
            'cookie_secure' => TRUE,
            'remember_me_seconds' => 3600,
            'use_cookies' => true,
        ),
        'serviceConfig' => array(
            'base64Encode' => false
        )
    )
);
