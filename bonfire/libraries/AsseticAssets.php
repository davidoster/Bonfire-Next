<?php

namespace Bonfire;

use Assetic\Asset\AssetCollection;
use Assetic\Asset\FileAsset;
use Assetic\Asset\GlobAsset;

class AsseticAssets implements AssetInterface {

    /**
     * The folders to scan for assets.
     *
     * @var array
     */
    protected $folders = [];

    protected $live_path;

    protected $ci;

    //--------------------------------------------------------------------

    /**
     * Setup our paths and get our Assetic classes ready to go.
     */
    public function __construct ()
    {
        $this->ci =& get_instance();

        $this->folders = $this->determineFolders();
    }

    //--------------------------------------------------------------------

    /**
     * Adds a new folder for our assets to be found in.
     *
     * @param $path
     * @return mixed
     */
    public function addAssetPath ($path)
    {
        if (empty($path) || ! is_string($path))
        {
            return;
        }

        $this->folders = array_unshift($this->folders, $path);
    }

    //--------------------------------------------------------------------

    /**
     * Removes a folder from our assets to search in.
     *
     * @param $path
     * @return mixed
     */
    public function removeAssetPath ($path)
    {
        unset($this->folders[ array_search($path, $this->folders) ]);
    }

    //--------------------------------------------------------------------

    /**
     * Adds a single CSS file to the list of files to be rendered.
     *
     * @param $file
     * @param $group
     * @return mixed
     */
    public function addStyle ($file, $group = null)
    {

    }

    //--------------------------------------------------------------------

    /**
     * Adds a single file within a module's `assets` folder to be rendered.
     *
     * @param $module
     * @param $file
     * @return mixed
     */
    public function addModuleStyle ($module, $file)
    {

    }

    //--------------------------------------------------------------------

    /**
     * Adds a single JS file to the list of files to be rendered.
     *
     * @param $file
     * @param $group
     * @return mixed
     */
    public function addScript ($file, $group = null)
    {

    }

    //--------------------------------------------------------------------

    /**
     * Adds a single file within a module's `assets` folder to be rendered.
     *
     * @param $module
     * @param $file
     * @return mixed
     */
    public function addModuleScript ($module, $file)
    {

    }

    //--------------------------------------------------------------------

    /**
     * Provides a generic interface into the various settings of the
     * child class without needing to specify a generic list of methods
     * that should be common between implementations.
     *
     * @param $option
     * @param $value
     * @return mixed
     */
    public function setOption($option, $value)
    {

    }

    //--------------------------------------------------------------------

    /**
     * Provides a generic way to retrieve child-class specific setting values.
     *
     * @param $option
     * @return mixed
     */
    public function getOption($option)
    {

    }

    //--------------------------------------------------------------------

    //--------------------------------------------------------------------
    // Renderer Methods
    //--------------------------------------------------------------------

    /**
     * Renders out all of the links for our script tags into a string,
     * which is returned for use within templates.
     *
     * If $file is included, will do it for only that single file.
     *
     * @param null $file
     * @return mixed
     */
    public function javascriptTags($file=null)
    {

    }

    //--------------------------------------------------------------------

    /**
     * Renders out all of the links for our stylesheets into a string,
     * which is returned for use withing templates.
     *
     * If $file is include, will do it for only that single file.
     *
     * @param null $file
     * @return mixed
     */
    public function styleTags($file=null)
    {

    }

    //--------------------------------------------------------------------

    //--------------------------------------------------------------------
    // Private Methods
    //--------------------------------------------------------------------

    /**
     * Builds our correct set of asset folders to search in based on
     * the default paths, the active module, and any custom paths
     * set in config item `assets.other_paths`.
     *
     * @return array
     */
    private function determineFolders ()
    {
        // Current Module's Path
        $module_path = \Bonfire\Modules::path($this->ci->router->fetch_module(), 'assets');
        if (strpos($module_path, 'assets') === false)
        {
            $module_path .= '/assets/';
            $module_path = str_replace('//', '/', $module_path);
        }

        // Grab our Theme Paths
        $theme_paths = array_values(config_item('template.theme_paths'));
        array_walk($theme_paths, function(&$value) {
            return $value .= '/assets/';
        });

        // Grab our Other Paths from the config file...
        // These get a priority just less than the
        // Themes themselves, but higher than the application.
        $other_paths = config_item('assets.other_paths');

        // Include the app and Bonfire assets
        $folders = [
            APPPATH .'assets/',
            $module_path,
            BFPATH .'assets/'
        ];

        $theme_paths = array_merge($theme_paths, $other_paths);

        $folders = array_merge($theme_paths, $folders);

        return $folders;
    }

    //--------------------------------------------------------------------

}