<?php

/**
 * Bonfire
 *
 * An open source project to allow developers get a jumpstart their development of CodeIgniter applications
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2014, Bonfire Dev Team
 * @license   http://opensource.org/licenses/MIT
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

require_once BFPATH .'libraries/Pimple.php';

/**
 * Base Controller
 *
 * A controller that your controllers can extend.
 *
 * This allows any tasks that need to be performed sitewide to be done in one
 *
 * @package    Bonfire\Core\Controllers\Base_Controller
 * @author     Bonfire Dev Team
 * @link       http://cibonfire.com/docs/bonfire/bonfire_controllers
 */
class Base_Controller extends MX_Controller {

    /**
     * Autoload functionality specific to this single controller.
     * Provided by HMVC. See the MX/Loader class, _autoloader() method.
     * @var array
     */
    public $autoload = array(
        'helper'    => array(),
        'language'  => array(),
        'libraries' => array(),
        'model'     => array(),
        'modules'   => array(),
        'sparks'    => array()
    );

    /**
     * DI Container
     * @var
     */
    protected $container;



    //--------------------------------------------------------------------

    public function __construct ()
    {
        parent::__construct();

        $this->init();

    }

    //--------------------------------------------------------------------

    /**
     * Handles setting up our DI container and other core setup tasks.
     * Placed here to get out of the way of application requirements.
     */
    public function init ()
    {
        // Get our DI container up and running
        $this->container = new Pimple();

        // Setup our Template Engine
        $this->container['templateEngineName']  = 'Bonfire\PlatesTemplate';
        $this->container['templateEngine']      = function ($c) {
            return new $c['templateEngineName']();
        };

        $this->template = $this->container['templateEngine'];
    }

    //--------------------------------------------------------------------
}