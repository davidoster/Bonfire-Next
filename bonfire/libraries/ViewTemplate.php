<?php

namespace Bonfire\Libraries;

use Bonfire\Interfaces\TemplateInterface;

class ViewTemplate implements TemplateInterface
{

    protected $theme = '';

    protected $layout = 'index';

    protected $view = '';

    protected $vars = [];

    protected $folders = [];

    protected $variants = [];

    protected $current_variant = '';

    protected $ci;

    //--------------------------------------------------------------------

    public function __construct()
    {
        $this->ci =& get_instance();

        // Register our paths with the engin
        $paths = config_item('template.theme_paths');

        foreach ($paths as $key => $path) {
            $this->addThemePath($key, $path);
        }

        // Register our variants with the engine.
        $variants = config_item('template.variants');

        foreach ($variants as $key => $value) {
            $this->addVariant($key, $value);
        }
    }

    //--------------------------------------------------------------------

    /**
     * The main entryway into rendering a view. This is called from the
     * controller and is generally the last method called.
     *
     * @param string $layout If provided, will override the default layout.
     */
    public function render($layout = null)
    {
        $data = $this->vars;

        // Make the template engine available within the views.
        $data['template'] = $this;

        // Render our current view content
        $data['view_content'] = $this->content();

        if (! isset($this->folders[$this->theme])) {
            throw new \LogicException("No folder found for theme: {$this->theme}.");
        }

        // Make the path available within views.
        $data['theme_path'] = $this->folders[$this->theme];

        echo $this->display($this->folders[$this->theme] . '/' . $this->layout, $data);
    }

    //--------------------------------------------------------------------

    /**
     * Used within the template layout file to render the current content.
     * This content is typically used to display the current view.
     */
    public function content()
    {
        // Calc our view name based on current method/controller
        $dir = $this->ci->router->fetch_directory();
        if (strpos($dir, 'modules')) {
            $dir = str_replace(APPPATH . 'modules/', '', $dir);
            $dir = str_replace(BFPATH . 'modules/', '', $dir);
            $dir = str_replace('controllers/', '', $dir);
        }

        if ($dir == $this->ci->router->fetch_module() . '/') {
            $dir = '';
        }

        $view = ! empty($this->view) ? $this->view :
            $dir . $this->ci->router->fetch_class() . '/' . $this->ci->router->fetch_method();

        return $this->display($view);
    }

    //--------------------------------------------------------------------

    /**
     * Loads a view file. Useful to control caching. Intended for use
     * from within view files.
     *
     * You can specify that a view should belong to a theme by prefixing
     * the name of the theme and a colon to the view name. For example,
     * "admin:header" would try to display the "header.php" file within
     * the "admin" theme.
     *
     * If a variant has been specified, it will be added to the end
     * of the view name before looking for the file.
     *
     * @param $view
     * @return mixed
     */
    public function display($view, $data=null)
    {
        $theme = null;
        $variant_view = null;

        // Pull out the theme from the view, if given.
        if (strpos($view, ':') !== false) {
            list($theme, $view) = explode(':', $view);
        }

        if (! empty($theme) && isset($this->folders[$theme])) {
            $view = rtrim($this->folders[$theme], '/') . '/' . $view;
        }

        $data = is_array($data) ? $data : $this->vars;

        // if using a variant, add it to the view name.
        if (! empty($this->current_variant))
        {
            $variant_view = $this->variants[ $this->current_variant ];

            $output = $this->ci->load->view($variant_view, $data, true);
        }

        // If that didn't find anything, then try it without a variant
        if (empty($output))
        {
            $output = $this->ci->load->view($view, $data, true);
        }

        return $output;
    }

    //--------------------------------------------------------------------

    /**
     * Sets the active theme to use. This theme should be
     * relative to one of the 'theme_paths' folders.
     *
     * @param $theme
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;
    }

    //--------------------------------------------------------------------

    /**
     * Returns the current theme.
     *
     * @return mixed|string
     */
    public function theme()
    {
        return $this->theme;
    }

    //--------------------------------------------------------------------

    /**
     * Sets the current view file to render.
     *
     * @param $file
     * @return mixed
     */
    public function setView($file)
    {
        $this->view = $file;
    }

    //--------------------------------------------------------------------

    /**
     * Returns the current view.
     *
     * @return mixed|string
     */
    public function view()
    {
        return $this->view;
    }

    //--------------------------------------------------------------------

    /**
     * Stores one or more pieces of data to be passed to the views when
     * they are rendered out.
     *
     * If both $key and $value are ! empty, then it will treat it as a
     * key/value pair. If $key is an array of key/value pairs, then $value
     * is ignored and each element of the array are made available to the
     * view as if it was a single $key/$value pair.
     *
     * @param string|array $key
     * @param mixed        $value
     * @return $this
     */
    public function set($key, $value = null)
    {
        if (is_array($key)) {
            $this->vars = array_merge($this->vars, $key);
            return;
        }

        $this->vars[$key] = $value;

        return $this;
    }

    //--------------------------------------------------------------------

    /**
     * Returns a value that has been previously set().
     *
     * @param $key
     * @return mixed
     */
    public function get($key)
    {
        return isset($this->vars[$key]) ? $this->vars[$key] : null;
    }

    //--------------------------------------------------------------------

    /**
     * Determines whether or not the view should be parsed with the
     * CodeIgniter's parser.
     *
     * @param bool $parse
     * @return mixed
     */
    public function parseViews($parse = false)
    {
        return $this;
    }

    //--------------------------------------------------------------------

    /**
     * Theme paths allow you to have multiple locations for themes to be
     * stored. This might be used for separating themes for different sub-
     * applications, or a core theme and user-submitted themes.
     *
     * @param $alias The name the theme can be referenced by
     * @param $path  A new path where themes can be found.
     *
     * @return mixed
     */
    public function addThemePath($alias, $path)
    {
        $this->folders[$alias] = $path;

        return $this;
    }

    //--------------------------------------------------------------------

    /**
     * Removes a single theme path.
     *
     * @param $alias
     * @return $this
     */
    public function removeThemePath($alias)
    {
        unset($this->folders[$alias]);

        return $this;
    }

    //--------------------------------------------------------------------
    // Variants
    //--------------------------------------------------------------------

    /**
     * Sets the variant used when creating view names. These variants can
     * be anything, but by default are used to render specific templates
     * for desktop, tablet, and phone. The name of the variant is added
     * to the view name, joined by a "+" symbol.
     *
     * Example:
     *      $this->setVariant('phone');
     *      $this->display('header');
     *
     *      Tries to display "views/header+phone.php"
     *
     * @param $variant
     * @return $this
     */
    public function setVariant($variant)
    {
        if (isset($this->variants[$variant])) {
            $this->current_variant = $variant;
        }

        return $this;
    }
    //--------------------------------------------------------------------

    /**
     * Adds a new variant to the system.
     *
     * @param $name
     * @param $postfix
     * @return $this|mixed
     */
    public function addVariant($name, $postfix)
    {
        $this->variants[$name] = $postfix;

        return $this;
    }

    //--------------------------------------------------------------------

    /**
     * Removes a variant from the system.
     *
     * @param $name
     * @return $this|mixed
     */
    public function removeVariant($name)
    {
        if (isset($this->variants[$name])) {
            unset($this->variants[$name]);
        }

        return $this;
    }
}