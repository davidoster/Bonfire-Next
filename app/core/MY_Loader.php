<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX_Loader class */
require APPPATH."third_party/MX/Loader.php";

class MY_Loader extends MX_Loader {

    /**
     * Constructor
     *
     * Sets the path to the view files and gets the initial output buffering level.
     *
     * Completely overridden here so that we can add in Bonfire's folders for loading support.
     */
    public function __construct()
    {
        $this->_ci_ob_level  = ob_get_level();
        $this->_ci_library_paths = array(APPPATH, BFPATH, BASEPATH);
        $this->_ci_helper_paths = array(APPPATH, BFPATH, BASEPATH);
        $this->_ci_model_paths = array(APPPATH, BFPATH);
        $this->_ci_view_paths = array(APPPATH.'views/'	=> TRUE, BFPATH .'views/' => true);

        log_message('debug', "Loader Class Initialized");

        // Call MX/Loaders constructor. We've commented out it loading
        // CI_Loader's there so our paths don't get overwritten.
        parent::__construct();
    }

    // --------------------------------------------------------------------
}