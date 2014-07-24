#!/usr/bin/env php
<?php

use Symfony\Component\Console\Application;

$command_folders = [
    __DIR__ .'/application/commands/',
    __DIR__ .'/bonfire/commands/'
];

/**
 * Register the Autoloader
 *
 * We use Composer's autoload here, since it's already setup
 * for this project and can find our Bonfire stuff.
 */
require __DIR__ .'/vendor/autoload.php';

/**
 * Setup our application. This is provided by the Symfony Console
 * package.
 *
 * http://symfony.com/doc/current/components/console/introduction.html
 */
$bf = new Application('Bonfire Console', '1.0-dev');

/**
 * Register all of the commands that we can find in the system.
 */
registerCommands($bf, $command_folders);

/**
 * Run the Console application
 */
$status = $bf->run();

/**
 * Shutdown the application.
 */
exit($status);

//--------------------------------------------------------------------

function registerCommands($bf, $folders) {

    die(var_dump($folders));
}