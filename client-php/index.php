<?php
    require 'vendor/autoload.php';

    session_start();
    
    $settings = require __DIR__ . '/src/settings.php';
    $app = new \Slim\App($settings);

    // config
    require __DIR__ . '/src/config.php';

    // Register routes
    require __DIR__ . '/src/routes.php';

    $app->run();