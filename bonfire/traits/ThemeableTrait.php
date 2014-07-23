<?php

namespace Bonfire;

/**
 * Themeable Trait
 *
 * Attaches methods used for rendering out views that
 * work with the Template engine.
 */
trait ThemeableTrait {

    // Stores data variables to be sent to the view.
    protected $vars = array();

    //--------------------------------------------------------------------

    public function init_themes()
    {
        // Setup our Template Engine
        $this->container['templateEngineName']  = config_item('di.templateEngine');
        $this->container['templateEngine']      = function ($c) {
            return new $c['templateEngineName']();
        };

        $this->template = $this->container['templateEngine'];
    }

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
}