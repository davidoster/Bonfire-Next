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

namespace Bonfire\Libraries\Controllers;

require_once BFPATH . 'libraries/Pimple.php';

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
class BaseController extends \MX_Controller
{

    /**
     * Autoload functionality specific to this single controller.
     * Provided by HMVC. See the MX/Loader class, _autoloader() method.
     *
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
     *
     * @var
     */
    protected $container;

    /**
     * Stores an array of methods in this class
     * and the names of any filters that should be applied:
     *
     *  $filtered_methods = [
     *      'index' => [
     *          'before' => ['filter1', 'filter2'],
     *          'after'  => ['filter3', 'filter4']
     *      ]
     *  ];
     *
     * @var array
     */
    protected $filtered_methods = [];

    /**
     * An array of method names that should be
     * called during the init() method. Used
     * by traits to allow them to be inserted
     * into the DI container and made ready.
     */
    protected $init_methods = [];

    //--------------------------------------------------------------------

    public function __construct()
    {
        parent::__construct();

        $this->init();

        /*
         * Auto-Migration Support
         */

        /*
         * Profiler
         */
        if (config_item('show_profiler') &&
            !$this->input->is_cli_request() &&
            !$this->input->is_ajax_request()
        ) {
            $this->load->add_package_path(APPPATH . 'third_party/codeigniter-forensics');
//            $this->load->library('Console');
            $this->output->enable_profiler(true);
        }

        /*
         * Call any filters that may be active on this route.
         */
        $this->callFilters('before');
    }

    //--------------------------------------------------------------------

    //--------------------------------------------------------------------
    // Basic Rendering Methods
    //--------------------------------------------------------------------

    /**
     * Renders a string of arbitrary text. This is best used during an AJAX
     * call or web service request that are expecting something other then
     * proper HTML.
     *
     * @param  string $text       The text to render.
     * @param  bool   $typography If TRUE, will run the text through 'Auto_typography'
     *                            before outputting to the browser.
     *
     * @todo Allow a custom output type to be set (ie, text/css)
     *
     * @return void
     */
    public function renderText($text, $typography = false)
    {
        // Note that, for now anyway, we don't do any cleaning of the text
        // and leave that up to the client to take care of.

        // However, we can auto_typography the text if we're asked nicely.
        if ($typography === true) {
            $this->load->helper('typography');
            $text = auto_typography($text);
        }

        $this->output->enable_profiler(false)
                     ->set_content_type('text/plain')
                     ->set_output($text);
    }

    //--------------------------------------------------------------------

    /**
     * Converts the provided array or object to JSON, sets the proper MIME type,
     * and outputs the data.
     *
     * Do NOT do any further actions after calling this action.
     *
     * @param  mixed $json The data to be converted to JSON.
     * @return [type]       [description]
     */
    public function renderJson($json)
    {
        if (is_resource($json)) {
            throw new \LogicException('Resources can not be converted to JSON data.');
        }

        // If there is a fragments array and we've enabled profiling,
        // then we need to add the profile results to the fragments
        // array so it will be updated on the site, since we disable
        // all profiling below to keep the results clean.
        if (is_array($json)) {
            /*
             * This section MAY come back during initial development
             * so it's being left here.
             *
            if (! isset($json['fragments']))
            {
                $json['fragments'] = array();
            }

            if ($this->config->item('show_profile'))
            {
                $this->load->library('profiler');
                $json['fragments']['#profiler'] = $this->profiler->run();
            }

            // Also, include our notices in the fragments array.
            if ($this->ajax_notices === TRUE)
            {
                $json['fragments']['#notices'] = $this->load->view('theme/notice', array('notice' => $this->message()), TRUE);
            }
            */
        }

        $this->output->enable_profiler(false)
                     ->set_content_type('application/json')
                     ->set_output(json_encode($json));
    }

    //--------------------------------------------------------------------

    /**
     * Sends the supplied string to the browser with a MIME type of text/javascript.
     *
     * Do NOT do any further processing after this command or you may receive a
     * Headers already sent error.
     *
     * @param  mixed $js The javascript to output.
     * @throws \LogicException
     * @return void
     */
    public function renderJS($js = null)
    {
        if (!is_string($js)) {
            throw new \LogicException('No javascript passed to the render_js() method.');
        }

        $this->output->enable_profiler(false)
                     ->set_content_type('application/x-javascript')
                     ->set_output($js);
    }

    //--------------------------------------------------------------------

    /**
     * Breaks us out of any output buffering so that any content echo'd out
     * will echo out as it happens, instead of waiting for the end of all
     * content to echo out. This is especially handy for long running
     * scripts like might be involved in cron scripts.
     *
     * @return void
     */
    public function renderRealtime()
    {
        if (ob_get_level() > 0) {
            end_end_flush();
        }
        ob_implicit_flush(true);
    }

    //--------------------------------------------------------------------

    /**
     * Integrates with the bootstrap-ajax javascript file to
     * redirect the user to a new url.
     *
     * If the URL is a relative URL, it will be converted to a full URL for this site
     * using site_url().
     *
     * @param  string $location [description]
     */
    public function ajaxRedirect($location = '')
    {
        $location = empty($location) ? '/' : $location;

        if (strpos($location, '/') !== 0 || strpos($location, '://') !== false) {
            if (!function_exists('site_url')) {
                $this->load->helper('url');
            }

            $location = site_url($location);
        }

        $this->render_json(array('location' => $location));
    }

    //--------------------------------------------------------------------

    /**
     * Attempts to get any information from php://input and return it
     * as JSON data. This is useful when your javascript is sending JSON data
     * to the application.
     *
     * @param  string $format The type of element to return, either 'object' or 'array'
     * @param  int    $depth  The number of levels deep to decode
     *
     * @return mixed    The formatted JSON data, or NULL.
     */
    public function getJson($format = 'object', $depth = 512)
    {
        $as_array = $format == 'array' ? true : false;

        return json_decode(file_get_contents('php://input'), $as_array, $depth);
    }

    //--------------------------------------------------------------------

    //--------------------------------------------------------------------
    // Utility Methods
    //--------------------------------------------------------------------

    /**
     * Handles setting up our DI container and other core setup tasks.
     * Placed here to get out of the way of application requirements.
     */
    public function init()
    {
        // Get our DI container up and running
        $this->container = new \Pimple();

        foreach ($this->init_methods as $method) {
            if (method_exists($this, $method)) {
                $this->{$method}();
            }
        }
    }

    //--------------------------------------------------------------------

    /**
     * Calls any filters that might exist on the route, as set in the
     * routes config file (typically either 'before' or 'after'.
     *
     * @param $type
     */
    public function callFilters($type)
    {
        $this->load->config('filters', true);

        $method         = $this->router->fetch_method();
        $method_filters = isset($this->filtered_methods[$method][$type]) ?
            explode('|', $this->filtered_methods[$method][$type]) : [];

        $params = $this->uri->segment_array();

        $ci =& get_instance();

        $available_filters = $this->config->item('filters');

        try {
            foreach ($method_filters as $filter) {
                if (array_key_exists($filter, $available_filters)) {
                    $action = $available_filters[$filter];
                    $action($params, $ci);
                }
            }
        } catch (RuntimeException $e) {
            // @todo - log filter execution errors.
        }
    }
    //--------------------------------------------------------------------

}