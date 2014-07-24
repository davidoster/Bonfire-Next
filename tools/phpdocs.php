#!/usr/bin/env php
<?php

/**
 * Builds the API Docs for Bonfire Next.
 *
 * Uses phpDoc and expects the phpDocumentor.phar file
 * to be located in the project root.
 */

$basepath = str_replace('tools', '', dirname(__FILE__));


// Where we should create the files at.
$build_path = realpath("{$basepath}../BonfireNextDocs");

// If the build folder doesn't exist, create it.
if (! is_dir($build_path))
{
    mkdir($build_path, 0777, true);
}

$result = shell_exec("php phpDocumentor.phar --directory=\"{$basepath}bonfire\" --target=\"{$build_path}\"");

die(var_dump($result));
