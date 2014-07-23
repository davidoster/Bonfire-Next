<?php

/**
 * This file defines all of the filters available to
 * classes. They are expected to be anonymous functions.
 *
 * The method should look like:
 *
 *      $config['auth'] = function ($params, $ci)
 *      {
 *          . . .
 *      }
 */

/*
 * Sets a constant 'DEBUG_MODE' when the $_GET
 * variable 'debug' exists. This constant can
 * then be referenced by any code during page
 * execution to display additional information.
 *
 * Currently only works in development environment.
 */
$config['debug'] = function($params, &$ci)
{
    if (defined('DEBUG_MODE'))
    {
        return;
    }

    if (isset($_GET['debug']) && ENVIRONMENT == 'development')
    {
        define('DEBUG_MODE', TRUE);
    }
};

//--------------------------------------------------------------------
