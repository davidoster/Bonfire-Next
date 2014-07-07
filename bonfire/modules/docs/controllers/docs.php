<?php

defined('BASEPATH') || exit('No direct script access allowed');

class Docs extends Base_Controller
{
    protected $docsDir = 'docs';

    protected $docsExt = '.md';

    protected $docsGroup = NULL;

    protected $docsTypeApp = 'application';

    protected $docsTypeBf = 'developer';

    protected $docsTypeMod = 'module';

    protected $ignoreFiles = array('_404.md');

    protected $tocFile;

    private $showAppDocs;

    private $showDevDocs;

    //--------------------------------------------------------------------------

    /**
     * Constructor
     *
     * @return \Docs
     */
    public function __construct ()
    {
        parent::__construct();

        $this->load->config('docs');
        $this->lang->load('docs');

        $this->docsGroup = $this->uri->segment(2);

        // Is displaying docs permitted for this environment?
        if (config_item('docs.permitted_environments')
            && ! in_array(ENVIRONMENT, config_item('docs.permitted_environments'))
        )
        {
            Template::set_message(lang('docs_env_disabled'), 'error');
            redirect();
        }

        // Was a doc group provided?
        if (! $this->docsGroup)
        {
            redirect('docs/' . config_item('docs.default_group'));
        }

        $this->showAppDocs = config_item('docs.show_app_docs');
        $this->showDevDocs = config_item('docs.show_dev_docs');
        $this->tocFile     = config_item('docs.toc_file') ? : '_toc.ini';

        // Make sure we can still get to the search method.
        if ($this->docsGroup == 'search')
        {
            $this->docsGroup = FALSE;
        }
        // Are we allowed to show developer docs in this environment?
        elseif ($this->docsGroup == 'developer'
                && ! $this->showDevDocs
                && ENVIRONMENT != 'development'
        )
        {
            if ($this->showAppDocs)
            {
                Template::set_message(lang('docs_not_allowed_dev'), 'warning');

                redirect('docs/application');
            }

            show_error(lang('docs_not_allowed'));
        }

        $this->template->setTheme('docs');

        $this->load->helper('form');
    }

    //--------------------------------------------------------------------

    /**
     * Display the list of documents available and the current document
     *
     * @return void
     */
    public function index ()
    {
        $data = array();

        $this->load->library('DocBuilder');

        // Make sure the builder knows where to look
        $this->docbuilder->addDocFolder('app', APPPATH .'docs');
        $this->docbuilder->addDocFolder('bonfire', BFPATH .'docs');

        $content = $this->docbuilder->readPage($this->uri->segment_array());
        $content = $this->docbuilder->postProcess($content, site_url(), current_url());

        $data['sidebar'] = $this->buildSidebar();
        $data['content'] = $content;

        $this->render($data);
    }

    //--------------------------------------------------------------------

    /**
     * Display search results and handles the search itself.
     *
     * @return void
     */
    public function search ()
    {
        $this->benchmark->mark('search_start');
        $this->load->library('docs/docsearch');

        $data = array();

        $terms = $this->input->post('search_terms');
        if ($terms)
        {
            $search_folders = array();
            if ($this->showAppDocs)
            {
                $search_folders[] = APPPATH . $this->docsDir;
            }

            if ($this->showDevDocs)
            {
                $search_folders[] = BFPATH . $this->docsDir;
            }

            $data['results'] = $this->docsearch->search($terms, $search_folders);
        }

        $this->benchmark->mark('search_end');

        $data['search_time']  = $this->benchmark->elapsed_time('search_start', 'search_end');
        $data['search_terms'] = $terms;

        $this->render($data);
    }

    //--------------------------------------------------------------------------
    // Private Methods
    //--------------------------------------------------------------------------

    /**
     * Builds a TOC for the sidebar out of files found in the following folders:
     *      - application/docs
     *      - bonfire/docs
     *      - {module}/docs
     *
     * @return string The HTML for the sidebar.
     */
    private function buildSidebar ()
    {
        $data = array();

        // Get the list of docs based on the current docs group
        // (application-specific or developer docs)
        if ($this->docsGroup == 'application')
        {
            $data['docs'] = $this->get_folder_files(APPPATH . $this->docsDir, $this->docsTypeApp);
        }
        elseif ($this->docsGroup == 'developer')
        {
            $data['docs'] = $this->get_folder_files(BFPATH . $this->docsDir, $this->docsTypeBf);
        }

        // Get the docs for the modules
        $data['module_docs'] = $this->get_module_docs();

        // Set the remaining data for the view
        $data['docsDir'] = $this->docsDir;
        $data['docsExt'] = $this->docsExt;

        return $this->post_process($this->load->view('docs/_sidebar', $data, TRUE));
    }

    //--------------------------------------------------------------------

    /**
     * Retrieves the list of files in a folder and preps the name and filename
     * so it's ready for creating the HTML.
     *
     * @param  String $folder         The path to the folder to retrieve.
     * @param  String $type           The type of documentation being retrieved
     *                                ('application', 'bonfire', or the name of the module).
     * @param  Array  $ignoredFolders A list of sub-folders we should ignore.
     *
     * @return Array  An associative array @see parse_ini_file for format
     * details.
     */
    private function get_folder_files ($folder, $type, $ignoredFolders = array())
    {
        if (! is_dir($folder))
        {
            return array();
        }

        // If the toc file exists in the folder, use it to build the links.
        if (is_file("{$folder}/{$this->tocFile}"))
        {
            return parse_ini_file("{$folder}/{$this->tocFile}", TRUE);
        }

        // If the toc file does not exist, build the links by listing the files
        // in the directory (and any sub-directories)
        $this->load->helper('directory');
        $map = directory_map($folder);

        // If directory_map can not open the directory or find any files inside
        // the directory, return an empty array.
        if (empty($map))
        {
            return array();
        }

        // If these docs are located in the /application/docs or /bonfire/docs
        // directory, just use $this->docsGroup for the root.
        // Module docs need $this->docsGroup and $type.
        $tocRoot = $this->docsGroup;
        if ($this->docsGroup != strtolower($type))
        {
            $tocRoot .= '/' . strtolower($type);
        }

        $toc = array();
        foreach ($map as $new_folder => $files)
        {
            // Is this a folder that should be ignored?
            if (is_string($new_folder) && in_array($new_folder, $ignoredFolders))
            {
                continue;
            }

            // If $files isn't an array, then make it one so that all situations
            // may be dealt with cleanly.
            if (! is_array($files))
            {
                $files = array($files);
            }

            foreach ($files as $file)
            {
                if (in_array($file, $this->ignoreFiles))
                {
                    continue;
                }

                // The title for the index is the passed $type. Otherwise,
                // build the title from the file's name.
                if (strpos($file, 'index') === FALSE)
                {
                    $title = str_replace($this->docsExt, '', $file);
                    $title = str_replace('_', ' ', $title);
                    $title = ucwords($title);

                    $toc["{$tocRoot}/{$file}"] = $title;
                }
                else
                {
                    $toc[$tocRoot] = $type;
                }
            }
        }

        return $toc;
    }

    //--------------------------------------------------------------------

    /**
     * Checks all modules to see if they include docs and prepares their doc
     * information for use in the sidebar.
     *
     * @return array
     */
    private function get_module_docs ()
    {
        $docs_modules = array();
        foreach (\Bonfire\Modules::list_modules() as $module)
        {
            $ignored_folders = array();
            $path            = \Bonfire\Modules::path($module) . $this->docsDir;

            // If these are developer docs, add the folder to the path.
            if ($this->docsGroup == $this->docsTypeBf)
            {
                $path .= '/' . $this->docsTypeBf;
            }
            // For Application docs, ignore the 'developers' folder.
            else
            {
                $ignored_folders[] = $this->docsTypeBf;
            }

            if (is_dir($path))
            {
                $files = $this->get_folder_files($path, $module, $ignored_folders);
                if (is_array($files) && count($files))
                {
                    $docs_modules[$module] = $files;
                }
            }
        }

        return $docs_modules;
    }

    //--------------------------------------------------------------------



}