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

    // Stores data variables to be sent to the view.
    protected $vars = array();

    // For status messages
    protected $message;

    //--------------------------------------------------------------------

    public function __construct ()
    {
        parent::__construct();

        $this->init();
    }

    //--------------------------------------------------------------------

    //--------------------------------------------------------------------
    // Output (Rendering) Methods
    //--------------------------------------------------------------------

    /**
     * Provides a common interface with the other rendering methods to
     * set the output of the method. Uses the current instance of $this->template.
     * Ensures that any data we've stored through $this->set_var() are present
     * and includes the status messages into the data.
     *
     * @param array $data
     */
    public function render ($data = array())
    {
        // Merge any saved vars into the data
        $data = array_merge($data, $this->vars);

        // Build our notices from the theme's view file.
        //$data['notice'] = $this->load->view('theme/notice', array('notice' => $this->message()), TRUE);

        $this->template->set($data);

        $this->template->render();
    }
    
    //--------------------------------------------------------------------

    /**
     * Sets a data variable to be sent to the view during the render() method.
     *
     * @param string $name
     * @param mixed  $value
     */
    public function set_var ($name, $value = NULL)
    {
        if (is_array($name))
        {
            foreach ($name as $k => $v)
            {
                $this->vars[$k] = $v;
            }
        }
        else
        {
            $this->vars[$name] = $value;
        }
    }

    //--------------------------------------------------------------------

    //--------------------------------------------------------------------
    // Status Messages
    //--------------------------------------------------------------------

    /**
     * Sets a status message (for displaying small success/error messages).
     * This is used in place of the session->flashdata functions since you
     * don't always want to have to refresh the page to show the message.
     *
     * @param string $message The message to save.
     * @param string $type    The string to be included as the CSS class of the containing div.
     */
    public function set_message ($message = '', $type = 'info')
    {
        if (! empty($message))
        {
            if (isset($this->session))
            {
                $this->session->set_flashdata('message', $type . '::' . $message);
            }

            $this->message = array(
                'type'    => $type,
                'message' => $message
            );
        }
    }

    //--------------------------------------------------------------------

    /**
     * Retrieves the status message to display (if any).
     *
     * @param  string $message [description]
     * @param  string $type    [description]
     * @return array
     */
    public function message ($message = '', $type = 'info')
    {
        $return = array(
            'message' => $message,
            'type'    => $type
        );

        // Does session data exist?
        if (empty($message) && class_exists('CI_Session'))
        {
            $message = $this->session->flashdata('message');

            if (! empty($message))
            {
                // Split out our message parts
                $temp_message      = explode('::', $message);
                $return['type']    = $temp_message[0];
                $return['message'] = $temp_message[1];

                unset($temp_message);
            }
        }

        // If message is empty, we need to check our own storage.
        if (empty($message))
        {
            if (empty($this->message['message']))
            {
                return '';
            }

            $return = $this->message;
        }

        // Clear our session data so we don't get extra messages on rare occasions.
        if (class_exists('CI_Session'))
        {
            $this->session->set_flashdata('message', '');
        }

        return $return;
    }

    //--------------------------------------------------------------------

    //--------------------------------------------------------------------
    // Other Rendering Methods
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
     * @return void
     */
    public function renderText ($text, $typography = FALSE)
    {
        // Note that, for now anyway, we don't do any cleaning of the text
        // and leave that up to the client to take care of.

        // However, we can auto_typography the text if we're asked nicely.
        if ($typography === TRUE)
        {
            $this->load->helper('typography');
            $text = auto_typography($text);
        }

        $this->output->enable_profiler(FALSE)
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
    public function renderJson ($json)
    {
        if (is_resource($json))
        {
            throw new \LogicException('Resources can not be converted to JSON data.');
        }

        // If there is a fragments array and we've enabled profiling,
        // then we need to add the profile results to the fragments
        // array so it will be updated on the site, since we disable
        // all profiling below to keep the results clean.
        if (is_array($json))
        {
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

        $this->output->enable_profiler(FALSE)
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
    public function render_js ($js = NULL)
    {
        if (! is_string($js))
        {
            throw new \LogicException('No javascript passed to the render_js() method.');
        }

        $this->output->enable_profiler(FALSE)
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
    public function render_realtime ()
    {
        if (ob_get_level() > 0)
        {
            end_end_flush();
        }
        ob_implicit_flush(TRUE);
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
    public function ajax_redirect ($location = '')
    {
        $location = empty($location) ? '/' : $location;

        if (strpos($location, '/') !== 0 || strpos($location, '://') !== FALSE)
        {
            if (! function_exists('site_url'))
            {
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
     * @param  int           $depth  The number of levels deep to decode
     *
     * @return mixed    The formatted JSON data, or NULL.
     */
    public function get_json ($format = 'object', $depth = 512)
    {
        $as_array = $format == 'array' ? TRUE : FALSE;

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
    public function init ()
    {
        // Get our DI container up and running
        $this->container = new Pimple();

        // Setup our Template Engine
        $this->container['templateEngineName']  = config_item('di.templateEngine');
        $this->container['templateEngine']      = function ($c) {
            return new $c['templateEngineName']();
        };

        $this->template = $this->container['templateEngine'];
    }

    //--------------------------------------------------------------------
}