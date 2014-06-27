<?php

/**
 * Bonfire
 *
 * An open source project to allow developers get a jumpstart their development of CodeIgniter applications
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2013, Bonfire Dev Team
 * @license   http://guides.cibonfire.com/license.html
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

namespace Bonfire;

interface AssetInterface {

    /**
     * Adds a new folder for our assets to be found in.
     *
     * @param $path
     * @return mixed
     */
    public function addAssetPath ($path);

    //--------------------------------------------------------------------

    /**
     * Removes a folder from our assets to search in.
     *
     * @param $path
     * @return mixed
     */
    public function removeAssetPath ($path);

    //--------------------------------------------------------------------

    /**
     * Adds a single CSS file to the list of files to be rendered.
     *
     * @param $file
     * @return mixed
     */
    public function addStyle ($file);

    //--------------------------------------------------------------------

    /**
     * Adds a single file within a module's `assets` folder to be rendered.
     *
     * @param $module
     * @param $file
     * @return mixed
     */
    public function addModuleStyle ($module, $file);

    //--------------------------------------------------------------------

    /**
     * Adds a single JS file to the list of files to be rendered.
     *
     * @param $file
     * @return mixed
     */
    public function addScript ($file);

    //--------------------------------------------------------------------

    /**
     * Adds a single file within a module's `assets` folder to be rendered.
     *
     * @param $module
     * @param $file
     * @return mixed
     */
    public function addModuleScript ($module, $file);

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
    public function setOption($option, $value);

    //--------------------------------------------------------------------

    /**
     * Provides a generic way to retrieve child-class specific setting values.
     *
     * @param $option
     * @return mixed
     */
    public function getOption($option);

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
    public function javascriptTags($file=null);

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
    public function styleTags($file=null);

    //--------------------------------------------------------------------

}