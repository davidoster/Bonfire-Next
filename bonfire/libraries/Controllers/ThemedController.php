<?php

namespace Bonfire\Controllers;

class ThemedController extends BaseController {

    // Stores data variables to be sent to the view.
    protected $vars = array();

    // For status messages
    protected $message;

    //--------------------------------------------------------------------

    public function __construct ()
    {
        parent::__construct();

        // Setup our Template Engine
        $this->container['templateEngineName']  = config_item('di.templateEngine');
        $this->container['templateEngine']      = function ($c) {
            return new $c['templateEngineName']();
        };

        $this->template = $this->container['templateEngine'];
    }

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
        $data['notice'] = $this->load->view("themes/{$this->template->theme()}/notice", array('notice' => $this->message()), TRUE);

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

}